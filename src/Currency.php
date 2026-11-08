<?php

declare(strict_types=1);

namespace AmountToWords;

use InvalidArgumentException;

final class Currency
{
    public function __construct(
        public readonly string $majorSingular,
        public readonly string $majorPlural,
        public readonly string $minorSingular,
        public readonly string $minorPlural,
        public readonly int $fractionDigits = 2,
    ) {
        foreach ([$majorSingular, $majorPlural] as $name) {
            if (trim($name) === '') {
                throw new InvalidArgumentException('Major currency names cannot be empty.');
            }
        }
        if ($fractionDigits < 0 || $fractionDigits > 9) {
            throw new InvalidArgumentException('Currency fraction digits must be between 0 and 9.');
        }
        if ($fractionDigits > 0 && (trim($minorSingular) === '' || trim($minorPlural) === '')) {
            throw new InvalidArgumentException('Minor currency names cannot be empty when fraction digits are used.');
        }
    }

    /** @return array<string, self> */
    public static function defaults(): array
    {
        return [
            'AED' => new self('Dirham', 'Dirhams', 'Fil', 'Fils'),
            'AUD' => new self('Australian Dollar', 'Australian Dollars', 'Cent', 'Cents'),
            'BDT' => new self('Taka', 'Taka', 'Paisa', 'Paisa'),
            'BHD' => new self('Bahraini Dinar', 'Bahraini Dinars', 'Fils', 'Fils', 3),
            'CAD' => new self('Canadian Dollar', 'Canadian Dollars', 'Cent', 'Cents'),
            'CHF' => new self('Swiss Franc', 'Swiss Francs', 'Rappen', 'Rappen'),
            'CNY' => new self('Yuan', 'Yuan', 'Jiao', 'Jiao'),
            'EUR' => new self('Euro', 'Euros', 'Cent', 'Cents'),
            'GBP' => new self('Pound', 'Pounds', 'Penny', 'Pence'),
            'HKD' => new self('Hong Kong Dollar', 'Hong Kong Dollars', 'Cent', 'Cents'),
            'IDR' => new self('Rupiah', 'Rupiah', 'Sen', 'Sen'),
            'INR' => new self('Rupee', 'Rupees', 'Paisa', 'Paise'),
            'JPY' => new self('Yen', 'Yen', '', '', 0),
            'KES' => new self('Kenyan Shilling', 'Kenyan Shillings', 'Cent', 'Cents'),
            'KWD' => new self('Kuwaiti Dinar', 'Kuwaiti Dinars', 'Fils', 'Fils', 3),
            'LKR' => new self('Sri Lankan Rupee', 'Sri Lankan Rupees', 'Cent', 'Cents'),
            'MYR' => new self('Ringgit', 'Ringgit', 'Sen', 'Sen'),
            'NGN' => new self('Naira', 'Naira', 'Kobo', 'Kobo'),
            'NPR' => new self('Nepalese Rupee', 'Nepalese Rupees', 'Paisa', 'Paisa'),
            'NZD' => new self('New Zealand Dollar', 'New Zealand Dollars', 'Cent', 'Cents'),
            'OMR' => new self('Omani Rial', 'Omani Rials', 'Baisa', 'Baisa', 3),
            'PKR' => new self('Pakistani Rupee', 'Pakistani Rupees', 'Paisa', 'Paisa'),
            'QAR' => new self('Qatari Riyal', 'Qatari Riyals', 'Dirham', 'Dirhams'),
            'SAR' => new self('Saudi Riyal', 'Saudi Riyals', 'Halala', 'Halalas'),
            'SGD' => new self('Singapore Dollar', 'Singapore Dollars', 'Cent', 'Cents'),
            'THB' => new self('Baht', 'Baht', 'Satang', 'Satang'),
            'USD' => new self('US Dollar', 'US Dollars', 'Cent', 'Cents'),
            'ZAR' => new self('Rand', 'Rand', 'Cent', 'Cents'),
        ];
    }
}
