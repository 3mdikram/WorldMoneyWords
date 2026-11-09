<?php

declare(strict_types=1);

namespace AmountToWords\Tests;

use AmountToWords\AmountToWords;
use AmountToWords\Currency;
use AmountToWords\ConversionOptions;
use AmountToWords\Exception\InvalidCountryCodeException;
use AmountToWords\Exception\InvalidCurrencyCodeException;
use AmountToWords\LanguagePack;
use PHPUnit\Framework\TestCase;

final class AmountToWordsTest extends TestCase
{
    public function testRejectsInvalidCustomCurrencyDefinitions(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Currency('Token', 'Tokens', 'Bit', 'Bits', -1);
    }

    public function testCustomOfficialLanguagePack(): void
    {
        $pack = new class implements LanguagePack {
            public function code(): string { return 'xx'; }
            public function spell(string $number): string { return "number-{$number}"; }
            public function labels(): array { return ['minus' => 'negative', 'conjunction' => 'with', 'only' => 'complete', 'minorSingular' => 'part', 'minorPlural' => 'parts']; }
            public function currencyName(string $currencyCode, bool $singular): string { return strtolower($currencyCode).($singular ? '-unit' : '-units'); }
        };

        $converter = (new AmountToWords())->addLanguagePack($pack);
        self::assertSame(
            'number-2 usd-units with number-50 parts complete',
            $converter->convert('2.50', 'US', 'USD', new ConversionOptions(language: 'xx')),
        );
    }
    public function testIndianRupees(): void
    {
        self::assertSame(
            'One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only',
            (new AmountToWords())->convert('1234.50', 'IND', 'INR'),
        );
    }

    public function testZeroAndNoMinorUnits(): void
    {
        self::assertSame('Zero Yen Only', (new AmountToWords())->convert(0, 'JPN', 'JPY'));
    }

    public function testCustomCurrency(): void
    {
        $converter = (new AmountToWords())->addCurrency('XTS', new Currency('Token', 'Tokens', 'Bit', 'Bits'));
        self::assertSame('One Token and One Bit Only', $converter->convert('1.01', 'USA', 'XTS'));
    }

    public function testCurrencyFromIcuFallback(): void
    {
        self::assertSame(
            'Two Brazilian Reals and Fifty Minor Units Only',
            (new AmountToWords())->convert('2.50', 'BRA', 'BRL'),
        );
    }

    public function testRejectsUnknownCountry(): void
    {
        $this->expectException(InvalidCountryCodeException::class);
        (new AmountToWords())->convert(10, 'ZZ', 'INR');
    }

    public function testAcceptsAlpha2CountryCode(): void
    {
        $converter = new AmountToWords();
        self::assertSame('Ten Rupees Only', $converter->convert(10, 'IN', 'INR'));
        self::assertSame('IND', $converter->normalizeCountryCode('in'));
        self::assertTrue($converter->isSupportedCountry('US'));
        self::assertSame('USA', $converter->convertDetailed(1, 'US', 'USD')->countryCode);
    }

    public function testRejectsMinorTerritoryOutsideSupportedCountries(): void
    {
        $this->expectException(InvalidCountryCodeException::class);
        (new AmountToWords())->convert(10, 'AIA', 'USD');
    }

    public function testCodesAreCaseInsensitive(): void
    {
        self::assertSame('One US Dollar Only', (new AmountToWords())->convert(1, 'usa', 'usd'));
    }

    public function testNegativeAmount(): void
    {
        self::assertSame(
            'Minus Ten Pounds and Twenty Five Pence Only',
            (new AmountToWords())->convert('-10.25', 'GBR', 'GBP'),
        );
    }

    public function testRejectsUnknownCurrency(): void
    {
        $this->expectException(InvalidCurrencyCodeException::class);
        (new AmountToWords())->convert(10, 'USA', 'ZZZ');
    }

    public function testSupportsEveryIsoCountry(): void
    {
        $converter = new AmountToWords();
        $countries = $converter->supportedCountries();

        self::assertCount(200, $countries);
        self::assertArrayHasKey('IND', $countries);
        self::assertArrayHasKey('USA', $countries);
        self::assertArrayHasKey('GBR', $countries);

        foreach (array_keys($countries) as $countryCode) {
            self::assertSame('One US Dollar Only', $converter->convert(1, $countryCode, 'USD'));
        }
    }

    public function testFormattedAmount(): void
    {
        self::assertSame(
            'One Million Two Hundred Thirty Four Thousand Five Hundred Sixty Seven US Dollars and Eighty Nine Cents Only',
            (new AmountToWords())->convert('1,234,567.89', 'USA', 'USD'),
        );
    }

    public function testConversionOptions(): void
    {
        $options = new ConversionOptions(includeOnly: false, includeZeroMinor: true, conjunction: 'plus');
        self::assertSame(
            'Ten US Dollars plus Zero Cents',
            (new AmountToWords())->convert(10, 'USA', 'USD', $options),
        );
    }

    public function testShortCurrencyNameOption(): void
    {
        $options = new ConversionOptions(includeOnly: false, includeZeroMinor: true, conjunction: 'plus', shortCurrencyName: true);
        self::assertSame('Ten US Dollars plus Zero Cents', (new AmountToWords())->convert('10.00', 'US', 'USD', $options));
    }

    public function testShortCurrencyNameRemovesPakistaniQualifier(): void
    {
        $options = new ConversionOptions(shortCurrencyName: true);
        self::assertSame(
            'Seven Hundred Forty Five PK Rupees and Sixty One Paisa Only',
            (new AmountToWords())->convert('745.61', 'PK', 'PKR', $options),
        );
    }

    public function testShortCurrencyNameWorksForEveryBundledCurrency(): void
    {
        $converter = new AmountToWords();
        $expected = [
            'AUD' => ['AU', 'Two AU Dollars Only'], 'BDT' => ['BD', 'Two BD Taka Only'],
            'BHD' => ['BH', 'Two BH Dinars Only'], 'CAD' => ['CA', 'Two CA Dollars Only'],
            'CHF' => ['CH', 'Two CH Francs Only'], 'CNY' => ['CN', 'Two CN Yuan Only'],
            'HKD' => ['HK', 'Two HK Dollars Only'], 'IDR' => ['ID', 'Two ID Rupiah Only'],
            'KES' => ['KE', 'Two KE Shillings Only'], 'KWD' => ['KW', 'Two KW Dinars Only'],
            'LKR' => ['LK', 'Two LK Rupees Only'], 'MYR' => ['MY', 'Two MY Ringgit Only'],
            'NGN' => ['NG', 'Two NG Naira Only'], 'NPR' => ['NP', 'Two NP Rupees Only'],
            'NZD' => ['NZ', 'Two NZ Dollars Only'], 'OMR' => ['OM', 'Two OM Rials Only'],
            'PKR' => ['PK', 'Two PK Rupees Only'], 'QAR' => ['QA', 'Two QA Riyals Only'],
            'SAR' => ['SA', 'Two SA Riyals Only'], 'SGD' => ['SG', 'Two SG Dollars Only'],
            'THB' => ['TH', 'Two TH Baht Only'], 'ZAR' => ['ZA', 'Two ZA Rand Only'],
        ];

        foreach ($expected as $currency => [$country, $text]) {
            self::assertSame(
                $text,
                $converter->convert('2', $country, $currency, new ConversionOptions(shortCurrencyName: true)),
                $currency,
            );
        }
    }

    public function testIgnoreCountryNameOptionUsesShortCurrencyName(): void
    {
        self::assertSame(
            'Two Rupees Only',
            (new AmountToWords())->convert(
                '2',
                'PK',
                'PKR',
                new ConversionOptions(ignoreCountryName: true),
            ),
        );
    }

    public function testShortCurrencyNameUsesIsoFallbackCurrencyUnit(): void
    {
        self::assertSame(
            'Two BR Reals Only',
            (new AmountToWords())->convert(
                '2',
                'BR',
                'BRL',
                new ConversionOptions(shortCurrencyName: true),
            ),
        );
    }

    public function testHindiLanguageOptionWorksForEveryCountry(): void
    {
        $converter = new AmountToWords();
        foreach (array_keys($converter->supportedCountries()) as $countryCode) {
            self::assertSame('एक हज़ार दो सौ चौंतीस भारतीय रुपया और पचास उप-इकाई मात्र', $converter->convert('1234.50', $countryCode, 'INR', new ConversionOptions(language: 'hi')));
            self::assertSame('ألف ومائتان وأربعة وثلاثون دولار أمريكي و خمسون وحدة فرعية فقط', $converter->convert('1234.50', $countryCode, 'USD', new ConversionOptions(language: 'ar')));
        }
    }

    public function testArabicLanguageOption(): void
    {
        self::assertSame(
            'ألف ومائتان وأربعة وثلاثون دولار أمريكي و خمسون وحدة فرعية فقط',
            (new AmountToWords())->convert('1234.50', 'USA', 'USD', new ConversionOptions(language: 'ar')),
        );
    }

    public function testUnavailableLanguageFallsBackToEnglish(): void
    {
        $converter = new AmountToWords();
        self::assertSame('Ten Pakistani Rupees Only', $converter->convert('10', 'PK', 'PKR', new ConversionOptions(language: 'ur')));
    }

    public function testDetailedResultIsJsonSerializable(): void
    {
        $result = (new AmountToWords())->convertDetailed('12.05', 'ind', 'inr');
        self::assertSame('Twelve Rupees and Five Paise Only', $result->text);
        self::assertSame('12', $result->majorAmount);
        self::assertSame('05', $result->minorAmount);
        self::assertSame('IND', $result->jsonSerialize()['countryCode']);
    }

    public function testBatchConversion(): void
    {
        $results = (new AmountToWords())->convertMany([
            ['amount' => 1, 'country' => 'USA', 'currency' => 'USD'],
            ['amount' => 2, 'country' => 'GBR', 'currency' => 'GBP'],
        ]);
        self::assertSame(['One US Dollar Only', 'Two Pounds Only'], $results);
    }

    public function testExactLargeStringDoesNotLosePrecision(): void
    {
        self::assertSame(
            'Nine Quadrillion Seven Trillion One Hundred Ninety Nine Billion Two Hundred Fifty Four Million Seven Hundred Forty Thousand Nine Hundred Ninety Three US Dollars and Forty Nine Cents Only',
            (new AmountToWords())->convert('9007199254740993.49', 'USA', 'USD'),
        );
    }

    public function testRoundingCarriesIntoMajorAmount(): void
    {
        self::assertSame('Two US Dollars Only', (new AmountToWords())->convert('1.999', 'USA', 'USD'));
    }

    public function testNegativeRoundedZeroIsNotNegative(): void
    {
        self::assertSame('Zero US Dollars Only', (new AmountToWords())->convert('-0.004', 'USA', 'USD'));
    }

    public function testDiscoveryAndValidationHelpers(): void
    {
        $converter = new AmountToWords();
        self::assertTrue($converter->isSupportedCountry('ind'));
        self::assertFalse($converter->isSupportedCountry('ZZZ'));
        self::assertTrue($converter->isSupportedCurrency('brl'));
        self::assertFalse($converter->isSupportedCurrency('ZZZ'));
        self::assertSame(0, $converter->supportedCurrencies()['JPY']['fractionDigits']);
        self::assertSame(3, $converter->supportedCurrencies()['KWD']['fractionDigits']);
    }

}
