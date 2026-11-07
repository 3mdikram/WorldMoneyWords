# WorldMoneyWords for Laravel

```bash
composer require 3mdikram/worldmoneywords-laravel
```

Laravel auto-discovers the provider and facade:

```php
use AmountToWords\Laravel\Facades\AmountToWords;

echo AmountToWords::convert('1234.50', 'IND', 'INR');
```

Use `shortCurrencyName: true` when the country qualifier should be omitted:

```php
use AmountToWords\ConversionOptions;
use AmountToWords\Laravel\Facades\AmountToWords;

$options = new ConversionOptions(
    includeOnly: false,
    includeZeroMinor: true,
    conjunction: 'plus',
    shortCurrencyName: true,
);

echo AmountToWords::convert('10.00', 'US', 'USD', $options);
// Ten Dollars plus Zero Cents
```
