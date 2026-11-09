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

    /** @var array<string, LanguagePack> */
    private array $languagePacks = [];

    /** @param array<string, Currency> $currencies */
    public function __construct(array $currencies = [])
    {
        $this->currencies = array_change_key_case(
            array_replace(Currency::defaults(), $currencies),
            CASE_UPPER,
        );
    }

    public function convert(
        int|float|string $amount,
        string $countryCode,
        string $currencyCode,
        ?ConversionOptions $options = null,
    ): string
    {
        return $this->convertWithOptions($amount, $countryCode, $currencyCode, $options);
    }

    public function convertWithOptions(
        int|float|string $amount,
        string $countryCode,
        string $currencyCode,
        ?ConversionOptions $options = null,
    ): string
    {
        $options ??= new ConversionOptions();
        $countryCode = $this->normalizeCountryCode($countryCode);
        $language = $this->resolveLanguage($options->language);
        $languagePack = $this->languagePacks[$language] ?? null;
        $currencyCode = strtoupper(trim($currencyCode));

        $this->validateCountry($countryCode);
        $currency = $this->currency($currencyCode);
        $normalized = $this->normalizeAmount($amount, $currency->fractionDigits);

        [$major, $minor] = $this->split($normalized, $currency->fractionDigits);
        $negative = str_starts_with($major, '-');
        $majorAbsolute = ltrim($major, '-');

        $parts = [];
        if ($negative) {
            $parts[] = $languagePack?->labels()['minus'] ?? ['en' => 'Minus', 'hi' => 'ऋण', 'ar' => 'سالب'][$language];
        }

        $parts[] = $languagePack?->spell($majorAbsolute) ?? $this->spellLocalized($majorAbsolute, $language);
        $majorName = $languagePack?->currencyName($currencyCode, $majorAbsolute === '1')
            ?? $this->localizedCurrencyName($currencyCode, $currency, $majorAbsolute === '1', $language);
        $parts[] = $options->shortCurrencyName
            ? $this->shortCurrencyName($currencyCode, $language, $majorAbsolute === '1', $majorName)
            : $majorName;

        if ($currency->fractionDigits > 0 && ((int) $minor > 0 || $options->includeZeroMinor)) {
            $parts[] = $options->conjunction === 'and'
                ? ($languagePack?->labels()['conjunction'] ?? ['en' => 'and', 'hi' => 'और', 'ar' => 'و'][$language])
                : $options->conjunction;
            $parts[] = $languagePack?->spell($minor) ?? $this->spellLocalized($minor, $language);
            $parts[] = $languagePack !== null
                ? ($languagePack->labels()[(int) $minor === 1 ? 'minorSingular' : 'minorPlural'])
                : ($language === 'en'
                ? ((int) $minor === 1 ? $currency->minorSingular : $currency->minorPlural)
                : ['hi' => 'उप-इकाई', 'ar' => 'وحدة فرعية'][$language]);
        }

        if ($options->includeOnly) {
            $parts[] = $languagePack?->labels()['only'] ?? ['en' => 'Only', 'hi' => 'मात्र', 'ar' => 'فقط'][$language];
        }

        return implode(' ', array_filter($parts, static fn (string $part): bool => $part !== ''));
    }

    private function resolveLanguage(string $requested): string
    {
        $language = strtolower(trim($requested));
        if (in_array($language, ['en', 'hi', 'ar'], true) || isset($this->languagePacks[$language])) {
            return $language;
        }
        return 'en';
    }

    private function spellLocalized(string $number, string $language): string
    {
        if ($language === 'en') {
            return $this->spell($number);
        }

        $formatter = new NumberFormatter($language, NumberFormatter::SPELLOUT);
        $formatted = $formatter->format((int) $number);
        if ($formatted === false) {
            return $this->spell($number);
        }

        return $formatted;
    }

    private function localizedCurrencyName(
        string $code,
        Currency $currency,
        bool $singular,
        string $language,
    ): string {
        if ($language === 'en') {
            return $singular ? $currency->majorSingular : $currency->majorPlural;
        }

        $name = Currencies::getName($code, $language);
        return $name === $code ? $code : $name;
    }

    private function shortCurrencyName(string $code, string $language, bool $singular, string $fallback): string
    {
        $names = [
            'en' => [
                'AED' => ['Dirham', 'Dirhams'], 'JPY' => ['Yen', 'Yen'],
                'AUD' => ['Dollar', 'Dollars'], 'BDT' => ['Taka', 'Taka'],
                'BHD' => ['Dinar', 'Dinars'], 'CAD' => ['Dollar', 'Dollars'],
                'CHF' => ['Franc', 'Francs'], 'CNY' => ['Yuan', 'Yuan'],
                'EUR' => ['Euro', 'Euros'], 'GBP' => ['Pound', 'Pounds'],
                'HKD' => ['Dollar', 'Dollars'], 'IDR' => ['Rupiah', 'Rupiah'],
                'INR' => ['Rupee', 'Rupees'], 'KES' => ['Shilling', 'Shillings'],
                'KWD' => ['Dinar', 'Dinars'], 'LKR' => ['Rupee', 'Rupees'],
                'MYR' => ['Ringgit', 'Ringgit'], 'NGN' => ['Naira', 'Naira'],
                'NPR' => ['Rupee', 'Rupees'], 'NZD' => ['Dollar', 'Dollars'],
                'OMR' => ['Rial', 'Rials'], 'PKR' => ['Rupee', 'Rupees'],
                'QAR' => ['Riyal', 'Riyals'], 'SAR' => ['Riyal', 'Riyals'],
                'SGD' => ['Dollar', 'Dollars'], 'THB' => ['Baht', 'Baht'],
                'USD' => ['Dollar', 'Dollars'], 'ZAR' => ['Rand', 'Rand'],
            ],
            'hi' => ['INR' => ['रुपया', 'रुपये'], 'USD' => ['डॉलर', 'डॉलर'], 'GBP' => ['पाउंड', 'पाउंड'], 'EUR' => ['यूरो', 'यूरो'], 'AED' => ['दिरहम', 'दिरहम'], 'JPY' => ['येन', 'येन']],
            'ar' => ['INR' => ['روبية', 'روبية'], 'USD' => ['دولار', 'دولار'], 'GBP' => ['جنيه', 'جنيه'], 'EUR' => ['يورو', 'يورو'], 'AED' => ['درهم', 'درهم'], 'JPY' => ['ين', 'ين']],
        ];

        return $names[$language][$code][$singular ? 0 : 1] ?? $fallback;
    }

    public function convertDetailed(
        int|float|string $amount,
        string $countryCode,
        string $currencyCode,
        ?ConversionOptions $options = null,
    ): ConversionResult {
        $countryCode = $this->normalizeCountryCode($countryCode);
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

    public function addLanguagePack(LanguagePack $pack): self
    {
        $code = strtolower(trim($pack->code()));
        if ($code === '' || !preg_match('/^[a-z]{2,8}(?:-[a-z0-9]{2,8})*$/', $code)) {
            throw new \InvalidArgumentException('Language pack code must be a valid language tag.');
        }

        $labels = $pack->labels();
        foreach (['minus', 'conjunction', 'only', 'minorSingular', 'minorPlural'] as $label) {
            if (!isset($labels[$label]) || trim($labels[$label]) === '') {
                throw new \InvalidArgumentException("Language pack label {$label} cannot be empty.");
            }
        }

        $clone = clone $this;
        $clone->languagePacks[$code] = $pack;
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
            if (in_array($country['alpha3'], SupportedCountries::ALPHA3, true)) {
                $countries[$country['alpha3']] = $country['name'];
            }
        }

        ksort($countries);

        return $countries;
    }

    public function isSupportedCountry(string $countryCode): bool
    {
        try {
            $this->normalizeCountryCode($countryCode);
            return true;
        } catch (InvalidCountryCodeException) {
            return false;
        }
    }

    /** Convert an ISO Alpha-2 or Alpha-3 country code to canonical Alpha-3. */
    public function normalizeCountryCode(string $countryCode): string
    {
        $code = strtoupper(trim($countryCode));
        $iso = new ISO3166();

        try {
            if (preg_match('/^[A-Z]{2}$/', $code)) {
                $resolved = $iso->alpha2($code)['alpha3'];
                if (in_array($resolved, SupportedCountries::ALPHA3, true)) {
                    return $resolved;
                }
            }
            if (preg_match('/^[A-Z]{3}$/', $code)) {
                $resolved = $iso->alpha3($code)['alpha3'];
                if (in_array($resolved, SupportedCountries::ALPHA3, true)) {
                    return $resolved;
                }
            }
        } catch (\Throwable) {
            // Convert all lookup failures to the library's public exception.
        }

        throw new InvalidCountryCodeException(sprintf(
            'Unknown ISO 3166-1 Alpha-2 or Alpha-3 country code: %s.',
            $countryCode,
        ));
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
            throw new InvalidCountryCodeException('Country code must resolve to ISO 3166-1 Alpha-3.');
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
