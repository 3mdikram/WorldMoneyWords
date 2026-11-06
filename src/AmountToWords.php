<?php

declare(strict_types=1);

namespace AmountToWords;

use AmountToWords\Exception\InvalidAmountException;
use AmountToWords\Exception\InvalidCountryCodeException;
use AmountToWords\Exception\InvalidCurrencyCodeException;
use League\ISO3166\ISO3166;
use NumberFormatter;
use Symfony\Component\Intl\Currencies;

final class AmountToWords
{
    /** @var array<string, Currency> */
    private array $currencies;

    /** @param array<string, Currency> $currencies */
    public function __construct(array $currencies = [])
    {
        $this->currencies = array_change_key_case(
            array_replace(Currency::defaults(), $currencies),
            CASE_UPPER,
        );
    }

    public function convert(int|float|string $amount, string $countryCode, string $currencyCode): string
    {
        return $this->convertWithOptions($amount, $countryCode, $currencyCode);
    }

    public function convertWithOptions(
        int|float|string $amount,
        string $countryCode,
        string $currencyCode,
        ?ConversionOptions $options = null,
    ): string
    {
        $options ??= new ConversionOptions();
        $countryCode = strtoupper(trim($countryCode));
        $currencyCode = strtoupper(trim($currencyCode));

        $this->validateCountry($countryCode);
        $currency = $this->currency($currencyCode);
        $normalized = $this->normalizeAmount($amount, $currency->fractionDigits);

        [$major, $minor] = $this->split($normalized, $currency->fractionDigits);
        $negative = str_starts_with($major, '-');
        $majorAbsolute = ltrim($major, '-');

        $parts = [];
        if ($negative) {
            $parts[] = 'Minus';
        }

        $parts[] = $this->spell($majorAbsolute);
        $parts[] = $majorAbsolute === '1' ? $currency->majorSingular : $currency->majorPlural;

        if ($currency->fractionDigits > 0 && ((int) $minor > 0 || $options->includeZeroMinor)) {
            $parts[] = $options->conjunction;
            $parts[] = $this->spell($minor);
            $parts[] = (int) $minor === 1 ? $currency->minorSingular : $currency->minorPlural;
        }

        if ($options->includeOnly) {
            $parts[] = 'Only';
        }

        return implode(' ', array_filter($parts, static fn (string $part): bool => $part !== ''));
    }

    public function convertDetailed(
        int|float|string $amount,
        string $countryCode,
        string $currencyCode,
        ?ConversionOptions $options = null,
    ): ConversionResult {
        $countryCode = strtoupper(trim($countryCode));
        $currencyCode = strtoupper(trim($currencyCode));
        $this->validateCountry($countryCode);
        $currency = $this->currency($currencyCode);
        $normalized = $this->normalizeAmount($amount, $currency->fractionDigits);
        [$major, $minor] = $this->split($normalized, $currency->fractionDigits);

        return new ConversionResult(
            $this->convertWithOptions($amount, $countryCode, $currencyCode, $options),
            $countryCode,
            $currencyCode,
            $major,
            $minor,
        );
    }

    /**
     * @param list<array{amount: int|float|string, country: string, currency: string}> $items
     * @return list<string>
     */
    public function convertMany(array $items, ?ConversionOptions $options = null): array
    {
        return array_map(
            fn (array $item): string => $this->convertWithOptions(
                $item['amount'],
                $item['country'],
                $item['currency'],
                $options,
            ),
            $items,
        );
    }

    public function addCurrency(string $code, Currency $currency): self
    {
        $clone = clone $this;
        $clone->currencies[strtoupper(trim($code))] = $currency;

        return $clone;
    }

    /**
     * Return every supported ISO 3166-1 country keyed by alpha-3 code.
     *
     * @return array<string, string>
     */
    public function supportedCountries(): array
    {
        $countries = [];

        foreach ((new ISO3166())->all() as $country) {
            $countries[$country['alpha3']] = $country['name'];
        }

        ksort($countries);

        return $countries;
    }

    public function isSupportedCountry(string $countryCode): bool
    {
        return isset($this->supportedCountries()[strtoupper(trim($countryCode))]);
    }

    /**
     * Return ISO currencies known by the runtime, including fraction digits.
     *
     * @return array<string, array{name: string, fractionDigits: int}>
     */
    public function supportedCurrencies(): array
    {
        $currencies = [];
        foreach (Currencies::getCurrencyCodes() as $code) {
            $currencies[$code] = [
                'name' => Currencies::getName($code, 'en'),
                'fractionDigits' => Currencies::getFractionDigits($code),
            ];
        }
        ksort($currencies);

        return $currencies;
    }

    public function isSupportedCurrency(string $currencyCode): bool
    {
        $code = strtoupper(trim($currencyCode));
        return isset($this->currencies[$code]) || in_array($code, Currencies::getCurrencyCodes(), true);
    }

    private function validateCountry(string $code): void
    {
        if (!preg_match('/^[A-Z]{3}$/', $code)) {
            throw new InvalidCountryCodeException('Country code must be a three-letter ISO 3166-1 alpha-3 code.');
        }

        try {
            (new ISO3166())->alpha3($code);
        } catch (\Throwable) {
            throw new InvalidCountryCodeException(sprintf('Unknown ISO country code: %s.', $code));
        }
    }

    private function currency(string $code): Currency
    {
        if (!preg_match('/^[A-Z]{3}$/', $code)) {
            throw new InvalidCurrencyCodeException('Currency must be a three-letter ISO 4217 code.');
        }

        if (!isset($this->currencies[$code])) {
            $singular = $this->currencyDisplayName($code, 1);
            $plural = $this->currencyDisplayName($code, 2);

            // ICU returns the code unchanged when it has no data for a currency.
            if ($singular === $code && $plural === $code) {
                throw new InvalidCurrencyCodeException(sprintf('Unknown ISO currency code: %s.', $code));
            }

            return new Currency(
                $singular,
                $plural,
                'Minor Unit',
                'Minor Units',
                Currencies::getFractionDigits($code),
            );
        }

        return $this->currencies[$code];
    }

    private function currencyDisplayName(string $code, int $amount): string
    {
        $formatter = new NumberFormatter('en', NumberFormatter::CURRENCY);
        $formatter->setPattern('¤¤¤');
        $formatted = $formatter->formatCurrency($amount, $code);
        if ($formatted === false) {
            return $code;
        }

        $name = preg_replace('/[\s\x{00A0}]*' . $amount . '$/u', '', $formatted);
        return $name === null || $name === '' ? $code : ucwords($name);
    }

    private function normalizeAmount(int|float|string $amount, int $digits): string
    {
        // Strings preserve financial input exactly. Floats are accepted for
        // convenience but callers should use strings for high-precision values.
        $value = is_float($amount)
            ? rtrim(rtrim(sprintf('%.14F', $amount), '0'), '.')
            : trim((string) $amount);
        if (str_contains($value, ',')) {
            if (!preg_match('/^-?\d{1,3}(?:,\d{3})+(?:\.\d+)?$/', $value)) {
                throw new InvalidAmountException('Amount contains invalid thousands grouping.');
            }
            $value = str_replace(',', '', $value);
        }
        if (!preg_match('/^-?\d+(?:\.\d+)?$/', $value)) {
            throw new InvalidAmountException('Amount must be a plain numeric value without commas or symbols.');
        }

        $negative = str_starts_with($value, '-');
        $unsigned = ltrim($value, '-');
        [$integer, $fraction] = array_pad(explode('.', $unsigned, 2), 2, '');
        $integer = ltrim($integer, '0');
        $integer = $integer === '' ? '0' : $integer;

        $kept = $digits === 0 ? '' : str_pad(substr($fraction, 0, $digits), $digits, '0');
        $discarded = substr($fraction, $digits);
        $roundUp = $discarded !== '' && $discarded[0] >= '5';

        if ($roundUp) {
            $combined = $this->addOne($integer . $kept);
            if ($digits === 0) {
                $integer = $combined;
            } elseif (strlen($combined) <= $digits) {
                $integer = '0';
                $kept = str_pad($combined, $digits, '0', STR_PAD_LEFT);
            } else {
                $integer = substr($combined, 0, -$digits);
                $kept = substr($combined, -$digits);
            }
        }

        $isZero = $integer === '0' && ($kept === '' || (int) $kept === 0);
        $sign = $negative && !$isZero ? '-' : '';

        return $sign . $integer . ($digits > 0 ? '.' . $kept : '');
    }

    private function addOne(string $digits): string
    {
        $result = $digits;
        $carry = 1;
        for ($index = strlen($result) - 1; $index >= 0 && $carry === 1; --$index) {
            $next = ((int) $result[$index]) + 1;
            $result[$index] = (string) ($next % 10);
            $carry = $next >= 10 ? 1 : 0;
        }

        return $carry === 1 ? '1' . $result : $result;
    }

    /** @return array{string, string} */
    private function split(string $amount, int $digits): array
    {
        if ($digits === 0) {
            return [$amount, ''];
        }

        $parts = explode('.', $amount, 2);
        return [$parts[0], $parts[1] ?? str_repeat('0', $digits)];
    }

    private function spell(string $number): string
    {
        $normalized = ltrim($number, '0');
        $normalized = $normalized === '' ? '0' : $normalized;
        if (strlen($normalized) > strlen((string) PHP_INT_MAX)
            || (strlen($normalized) === strlen((string) PHP_INT_MAX) && strcmp($normalized, (string) PHP_INT_MAX) > 0)) {
            throw new InvalidAmountException(sprintf('Amount exceeds the supported maximum of %s.', PHP_INT_MAX));
        }

        $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);
        $words = $formatter->format((int) $normalized);
        if ($words === false) {
            throw new InvalidAmountException('The amount is outside the supported number range.');
        }

        return ucwords(str_replace('-', ' ', $words));
    }
}
