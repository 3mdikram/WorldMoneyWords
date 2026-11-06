# WorldMoneyWords

Convert an amount to English words using the same three-argument API across PHP, Laravel, Python, Go, React, browsers, and Node.js.

| Platform | Package directory | Function |
|---|---|---|
| PHP | root | `$converter->convert($amount, $country, $currency)` |
| Laravel | `laravel/` | `AmountToWords::convert($amount, $country, $currency)` |
| Python | `python/` | `converter.convert(amount, country, currency)` |
| Go | `go/` | `amountwords.Convert(amount, country, currency)` |
| React/Node | `react/` | `amountToWords(amount, country, currency)` |

## PHP

```bash
composer require worldmoneywords/core
```

```php
use AmountToWords\AmountToWords;

$converter = new AmountToWords();

echo $converter->convert('1234.50', 'IND', 'INR');
// One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only

echo $converter->convert('99.95', 'USA', 'USD');
// Ninety Nine US Dollars and Ninety Five Cents Only
```

The country argument accepts all ISO 3166-1 alpha-3 values such as `IND`, `USA`, `GBR`, `ARE`, and `JPN`. Common currencies have precise major/minor names and zero-, two-, or three-decimal handling. Other ISO 4217 currencies use their English ICU display name and the generic `Minor Unit`; register a definition when a currency needs a specific minor-unit name.

More than 200 countries are included through the complete ISO 3166-1 dataset. Retrieve the supported list when building a dropdown or validating an API request:

```php
$countries = $converter->supportedCountries();

// ['ABW' => 'Aruba', 'AFG' => 'Afghanistan', ...]
```

Add or override a currency without changing the package:

```php
use AmountToWords\AmountToWords;
use AmountToWords\Currency;

$converter = (new AmountToWords())->addCurrency(
    'XTS',
    new Currency('Token', 'Tokens', 'Bit', 'Bits', 2),
);
```

Requirements: PHP 8.1+ and the `intl` extension.

## Python

```bash
cd python
pip install .
```

```python
from amount_to_words import AmountToWords

text = AmountToWords().convert("1234.50", "IND", "INR")
```

## Go

```go
import amountwords "github.com/worldmoneywords/go"

text, err := amountwords.Convert(1234.50, "IND", "INR")
```

## React, browser, and Node.js

```bash
cd react
npm install
npm run build
```

```tsx
import { amountToWords } from '@worldmoneywords/core';

export function AmountLabel() {
  return <span>{amountToWords('1234.50', 'IND', 'INR')}</span>;
}
```

## Laravel

See [`laravel/README.md`](laravel/README.md) for auto-discovery and facade usage.

## API

```php
convert(int|float|string $amount, string $countryCode, string $currencyCode): string
```

- `amount`: plain numeric value, without grouping commas or a currency symbol
- `countryCode`: ISO 3166-1 alpha-3 code, case-insensitive
- `currencyCode`: ISO 4217 code, case-insensitive

Invalid input throws one of the exceptions in `AmountToWords\Exception`.

## Advanced functionality

Formatted input, such as `1,234,567.89`, is accepted. Customize the wording without changing the original three-argument API:

```php
use AmountToWords\ConversionOptions;

$options = new ConversionOptions(
    includeOnly: false,
    includeZeroMinor: true,
    conjunction: 'plus',
);

echo $converter->convertWithOptions('10.00', 'USA', 'USD', $options);
// Ten US Dollars plus Zero Cents
```

Use `convertDetailed()` for API responses:

```php
$result = $converter->convertDetailed('12.05', 'IND', 'INR');

echo json_encode($result);
// {"text":"Twelve Rupees and Five Paise Only","countryCode":"IND",...}
```

Use `convertMany()` to process several amounts in one call:

```php
$words = $converter->convertMany([
    ['amount' => 1, 'country' => 'USA', 'currency' => 'USD'],
    ['amount' => 2, 'country' => 'GBR', 'currency' => 'GBP'],
]);
```

### Financial accuracy

Pass financial values as strings to preserve every digit. Conversion uses decimal-string rounding, so large values do not pass through binary floating point and carry rounding is exact:

```php
$converter->convert('9007199254740993.49', 'USA', 'USD');
$converter->convert('1.999', 'USA', 'USD'); // Two US Dollars Only
```

Use discovery helpers to build validated API inputs and dropdowns:

```php
$converter->isSupportedCountry('IND');
$converter->isSupportedCurrency('INR');
$converter->supportedCountries();
$converter->supportedCurrencies(); // name and fraction digits
```

## Development

```bash
composer install
composer check
php examples/basic.php
```

Language-specific tests:

```bash
cd python && PYTHONPATH=src python3 -m unittest discover -s tests
cd go && go test ./...
cd react && npm test
```
