<div align="center">

# 🌍 WorldMoneyWords

**Convert monetary amounts into clear, human-readable words across multiple programming languages.**

[![Tests](https://github.com/3mdikram/WorldMoneyWords/actions/workflows/tests.yml/badge.svg)](https://github.com/3mdikram/WorldMoneyWords/actions/workflows/tests.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Countries](https://img.shields.io/badge/ISO_countries-250-1f6feb)](#country-and-currency-support)
[![Languages](https://img.shields.io/badge/platforms-PHP_%7C_Python_%7C_Go_%7C_TypeScript-orange)](#platforms)

[Quick start](#quick-start) · [Platforms](#platforms) · [Advanced usage](#advanced-php-usage) · [Contributing](CONTRIBUTING.md)

</div>

---

```text
Input:  1234.50, IND, INR
Output: One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only
```

WorldMoneyWords provides one familiar three-value contract everywhere:

```text
convert(amount, countryCode, currencyCode)
```

## Why WorldMoneyWords?

| Feature | Description |
|---|---|
| 🌐 Global country support | Validates all 250 ISO 3166-1 alpha-3 country codes |
| 💱 Currency aware | Supports ISO 4217 names, minor units, and 0/2/3 decimal currencies |
| 🎯 Financial accuracy | Exact decimal-string rounding avoids binary floating-point loss in PHP |
| 🧩 Multi-platform | PHP, Laravel, Python, Go, React, browsers, and Node.js |
| 📦 Batch and API friendly | Batch conversion and JSON-serializable detailed results |
| 🔧 Extensible | Register or override currency names and minor units |
| ✅ Tested | Automated tests and GitHub Actions CI |

## Quick start

### PHP

```bash
composer require 3mdikram/worldmoneywords
```

```php
<?php

use AmountToWords\AmountToWords;

$converter = new AmountToWords();

echo $converter->convert('1234.50', 'IND', 'INR');
// One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only
```

> [!TIP]
> Pass financial amounts as strings to preserve every digit exactly.

## Platforms

| Platform | Package | Main API |
|---|---|---|
| PHP | `3mdikram/worldmoneywords` | `$converter->convert(...)` |
| Laravel | `3mdikram/worldmoneywords-laravel` | `AmountToWords::convert(...)` |
| Python | `3mdikram-worldmoneywords` | `converter.convert(...)` |
| Go | `github.com/3mdikram/WorldMoneyWords/go` | `amountwords.Convert(...)` |
| React / Node.js | `@3mdikram/worldmoneywords` | `amountToWords(...)` |

<details>
<summary><strong>Laravel example</strong></summary>

```bash
composer require 3mdikram/worldmoneywords-laravel
```

```php
use AmountToWords\Laravel\Facades\AmountToWords;

echo AmountToWords::convert('99.95', 'USA', 'USD');
// Ninety Nine US Dollars and Ninety Five Cents Only
```

Laravel discovers the provider and facade automatically. See the [Laravel guide](laravel/README.md).

</details>

<details>
<summary><strong>Python example</strong></summary>

```bash
pip install 3mdikram-worldmoneywords
```

```python
from amount_to_words import AmountToWords

converter = AmountToWords()
print(converter.convert("42.50", "GBR", "GBP"))
# Forty Two Pounds and Fifty Pence Only
```

</details>

<details>
<summary><strong>Go example</strong></summary>

```bash
go get github.com/3mdikram/WorldMoneyWords/go
```

```go
package main

import (
    "fmt"
    amountwords "github.com/3mdikram/WorldMoneyWords/go"
)

func main() {
    text, err := amountwords.Convert(42.50, "GBR", "GBP")
    if err != nil {
        panic(err)
    }
    fmt.Println(text)
}
```

</details>

<details>
<summary><strong>React / TypeScript example</strong></summary>

```bash
npm install @3mdikram/worldmoneywords
```

```tsx
import { amountToWords } from '@3mdikram/worldmoneywords';

export function PaymentTotal() {
  const words = amountToWords('1500.25', 'USA', 'USD');
  return <p>{words}</p>;
}
```

The TypeScript package has no React runtime dependency, so it also works in browsers and Node.js.

</details>

## Core API

```php
convert(int|float|string $amount, string $countryCode, string $currencyCode): string
```

| Argument | Example | Rules |
|---|---|---|
| `amount` | `'1,234.50'` | Plain or correctly grouped numeric value; strings are recommended |
| `countryCode` | `'IND'` | ISO 3166-1 alpha-3 code, case-insensitive |
| `currencyCode` | `'INR'` | ISO 4217 code, case-insensitive |

Invalid PHP input throws a specific exception from `AmountToWords\Exception`.

## Advanced PHP usage

### Customize the sentence

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

### Return structured API data

```php
$result = $converter->convertDetailed('12.05', 'IND', 'INR');
echo json_encode($result, JSON_PRETTY_PRINT);
```

```json
{
  "text": "Twelve Rupees and Five Paise Only",
  "countryCode": "IND",
  "currencyCode": "INR",
  "majorAmount": "12",
  "minorAmount": "05"
}
```

### Convert several amounts

```php
$results = $converter->convertMany([
    ['amount' => '1.00', 'country' => 'USA', 'currency' => 'USD'],
    ['amount' => '2.00', 'country' => 'GBR', 'currency' => 'GBP'],
]);
```

### Add a custom currency

```php
use AmountToWords\Currency;

$converter = $converter->addCurrency(
    'XTS',
    new Currency('Token', 'Tokens', 'Bit', 'Bits', 2),
);
```

### Handle exact financial values

```php
$converter->convert('9007199254740993.49', 'USA', 'USD');
$converter->convert('1.999', 'USA', 'USD');
// Two US Dollars Only
```

WorldMoneyWords performs decimal-string rounding in PHP, including correct carry behavior and negative-zero handling.

## Country and currency support

The 15 most commonly used countries are shown by default:

| Country | Country code | Currency | Currency code |
|---|---:|---|---:|
| India | `IND` | Indian Rupee | `INR` |
| United States | `USA` | US Dollar | `USD` |
| United Kingdom | `GBR` | Pound Sterling | `GBP` |
| United Arab Emirates | `ARE` | UAE Dirham | `AED` |
| Canada | `CAN` | Canadian Dollar | `CAD` |
| Australia | `AUS` | Australian Dollar | `AUD` |
| Germany | `DEU` | Euro | `EUR` |
| France | `FRA` | Euro | `EUR` |
| Japan | `JPN` | Japanese Yen | `JPY` |
| China | `CHN` | Yuan | `CNY` |
| Singapore | `SGP` | Singapore Dollar | `SGD` |
| Saudi Arabia | `SAU` | Saudi Riyal | `SAR` |
| Brazil | `BRA` | Brazilian Real | `BRL` |
| South Africa | `ZAF` | Rand | `ZAR` |
| New Zealand | `NZL` | New Zealand Dollar | `NZD` |

<details>
<summary><strong>🌐 Show all other 235 supported countries</strong></summary>

<br>

| Country | ISO alpha-3 code |
|---|---:|
| Afghanistan | `AFG` |
| Åland Islands | `ALA` |
| Albania | `ALB` |
| Algeria | `DZA` |
| American Samoa | `ASM` |
| Andorra | `AND` |
| Angola | `AGO` |
| Anguilla | `AIA` |
| Antarctica | `ATA` |
| Antigua and Barbuda | `ATG` |
| Argentina | `ARG` |
| Armenia | `ARM` |
| Aruba | `ABW` |
| Austria | `AUT` |
| Azerbaijan | `AZE` |
| Bahamas | `BHS` |
| Bahrain | `BHR` |
| Bangladesh | `BGD` |
| Barbados | `BRB` |
| Belarus | `BLR` |
| Belgium | `BEL` |
| Belize | `BLZ` |
| Benin | `BEN` |
| Bermuda | `BMU` |
| Bhutan | `BTN` |
| Bolivia (Plurinational State of) | `BOL` |
| Bonaire, Sint Eustatius and Saba | `BES` |
| Bosnia and Herzegovina | `BIH` |
| Botswana | `BWA` |
| Bouvet Island | `BVT` |
| British Indian Ocean Territory | `IOT` |
| Brunei Darussalam | `BRN` |
| Bulgaria | `BGR` |
| Burkina Faso | `BFA` |
| Burundi | `BDI` |
| Cabo Verde | `CPV` |
| Cambodia | `KHM` |
| Cameroon | `CMR` |
| Cayman Islands | `CYM` |
| Central African Republic | `CAF` |
| Chad | `TCD` |
| Chile | `CHL` |
| Christmas Island | `CXR` |
| Cocos (Keeling) Islands | `CCK` |
| Colombia | `COL` |
| Comoros | `COM` |
| Congo | `COG` |
| Congo (Democratic Republic of the) | `COD` |
| Cook Islands | `COK` |
| Costa Rica | `CRI` |
| Côte d'Ivoire | `CIV` |
| Croatia | `HRV` |
| Cuba | `CUB` |
| Curaçao | `CUW` |
| Cyprus | `CYP` |
| Czechia | `CZE` |
| Denmark | `DNK` |
| Djibouti | `DJI` |
| Dominica | `DMA` |
| Dominican Republic | `DOM` |
| Ecuador | `ECU` |
| Egypt | `EGY` |
| El Salvador | `SLV` |
| Equatorial Guinea | `GNQ` |
| Eritrea | `ERI` |
| Estonia | `EST` |
| Ethiopia | `ETH` |
| Eswatini | `SWZ` |
| Falkland Islands (Malvinas) | `FLK` |
| Faroe Islands | `FRO` |
| Fiji | `FJI` |
| Finland | `FIN` |
| French Guiana | `GUF` |
| French Polynesia | `PYF` |
| French Southern Territories | `ATF` |
| Gabon | `GAB` |
| Gambia | `GMB` |
| Georgia | `GEO` |
| Ghana | `GHA` |
| Gibraltar | `GIB` |
| Greece | `GRC` |
| Greenland | `GRL` |
| Grenada | `GRD` |
| Guadeloupe | `GLP` |
| Guam | `GUM` |
| Guatemala | `GTM` |
| Guernsey | `GGY` |
| Guinea | `GIN` |
| Guinea-Bissau | `GNB` |
| Guyana | `GUY` |
| Haiti | `HTI` |
| Heard Island and McDonald Islands | `HMD` |
| Holy See | `VAT` |
| Honduras | `HND` |
| Hong Kong | `HKG` |
| Hungary | `HUN` |
| Iceland | `ISL` |
| Indonesia | `IDN` |
| Iran (Islamic Republic of) | `IRN` |
| Iraq | `IRQ` |
| Ireland | `IRL` |
| Isle of Man | `IMN` |
| Israel | `ISR` |
| Italy | `ITA` |
| Jamaica | `JAM` |
| Jersey | `JEY` |
| Jordan | `JOR` |
| Kazakhstan | `KAZ` |
| Kenya | `KEN` |
| Kiribati | `KIR` |
| Korea (Democratic People's Republic of) | `PRK` |
| Korea (Republic of) | `KOR` |
| Kosovo | `XKX` |
| Kuwait | `KWT` |
| Kyrgyzstan | `KGZ` |
| Lao People's Democratic Republic | `LAO` |
| Latvia | `LVA` |
| Lebanon | `LBN` |
| Lesotho | `LSO` |
| Liberia | `LBR` |
| Libya | `LBY` |
| Liechtenstein | `LIE` |
| Lithuania | `LTU` |
| Luxembourg | `LUX` |
| Macao | `MAC` |
| North Macedonia | `MKD` |
| Madagascar | `MDG` |
| Malawi | `MWI` |
| Malaysia | `MYS` |
| Maldives | `MDV` |
| Mali | `MLI` |
| Malta | `MLT` |
| Marshall Islands | `MHL` |
| Martinique | `MTQ` |
| Mauritania | `MRT` |
| Mauritius | `MUS` |
| Mayotte | `MYT` |
| Mexico | `MEX` |
| Micronesia (Federated States of) | `FSM` |
| Moldova (Republic of) | `MDA` |
| Monaco | `MCO` |
| Mongolia | `MNG` |
| Montenegro | `MNE` |
| Montserrat | `MSR` |
| Morocco | `MAR` |
| Mozambique | `MOZ` |
| Myanmar | `MMR` |
| Namibia | `NAM` |
| Naoero | `NRU` |
| Nepal | `NPL` |
| Netherlands | `NLD` |
| New Caledonia | `NCL` |
| Nicaragua | `NIC` |
| Niger | `NER` |
| Nigeria | `NGA` |
| Niue | `NIU` |
| Norfolk Island | `NFK` |
| Northern Mariana Islands | `MNP` |
| Norway | `NOR` |
| Oman | `OMN` |
| Pakistan | `PAK` |
| Palau | `PLW` |
| Palestine, State of | `PSE` |
| Panama | `PAN` |
| Papua New Guinea | `PNG` |
| Paraguay | `PRY` |
| Peru | `PER` |
| Philippines | `PHL` |
| Pitcairn | `PCN` |
| Poland | `POL` |
| Portugal | `PRT` |
| Puerto Rico | `PRI` |
| Qatar | `QAT` |
| Réunion | `REU` |
| Romania | `ROU` |
| Russian Federation | `RUS` |
| Rwanda | `RWA` |
| Saint Barthélemy | `BLM` |
| Saint Helena, Ascension and Tristan da Cunha | `SHN` |
| Saint Kitts and Nevis | `KNA` |
| Saint Lucia | `LCA` |
| Saint Martin (French part) | `MAF` |
| Saint Pierre and Miquelon | `SPM` |
| Saint Vincent and the Grenadines | `VCT` |
| Samoa | `WSM` |
| San Marino | `SMR` |
| Sao Tome and Principe | `STP` |
| Senegal | `SEN` |
| Serbia | `SRB` |
| Seychelles | `SYC` |
| Sierra Leone | `SLE` |
| Sint Maarten (Dutch part) | `SXM` |
| Slovakia | `SVK` |
| Slovenia | `SVN` |
| Solomon Islands | `SLB` |
| Somalia | `SOM` |
| South Georgia and the South Sandwich Islands | `SGS` |
| South Sudan | `SSD` |
| Spain | `ESP` |
| Sri Lanka | `LKA` |
| Sudan | `SDN` |
| Suriname | `SUR` |
| Svalbard and Jan Mayen | `SJM` |
| Sweden | `SWE` |
| Switzerland | `CHE` |
| Syrian Arab Republic | `SYR` |
| Taiwan (Province of China) | `TWN` |
| Tajikistan | `TJK` |
| Tanzania, United Republic of | `TZA` |
| Thailand | `THA` |
| Timor-Leste | `TLS` |
| Togo | `TGO` |
| Tokelau | `TKL` |
| Tonga | `TON` |
| Trinidad and Tobago | `TTO` |
| Tunisia | `TUN` |
| Türkiye | `TUR` |
| Turkmenistan | `TKM` |
| Turks and Caicos Islands | `TCA` |
| Tuvalu | `TUV` |
| Uganda | `UGA` |
| Ukraine | `UKR` |
| United States Minor Outlying Islands | `UMI` |
| Uruguay | `URY` |
| Uzbekistan | `UZB` |
| Vanuatu | `VUT` |
| Venezuela (Bolivarian Republic of) | `VEN` |
| Viet Nam | `VNM` |
| Virgin Islands (British) | `VGB` |
| Virgin Islands (U.S.) | `VIR` |
| Wallis and Futuna | `WLF` |
| Western Sahara | `ESH` |
| Yemen | `YEM` |
| Zambia | `ZMB` |
| Zimbabwe | `ZWE` |

</details>

Build dropdowns and validate input using the discovery API:

```php
$converter->isSupportedCountry('IND');  // true
$converter->isSupportedCurrency('INR'); // true
$countries = $converter->supportedCountries();
$currencies = $converter->supportedCurrencies();
```

Unknown but valid ICU currencies use their English display name and generic `Minor Unit` wording. Register a custom definition when exact minor-unit terminology is required.

## Requirements

- PHP 8.1 or newer
- PHP `intl` extension
- Composer

The language-specific packages contain their own runtime requirements.

## Development

```bash
git clone https://github.com/3mdikram/WorldMoneyWords.git
cd WorldMoneyWords
composer install
composer check
```

Run each implementation's tests:

```bash
# PHP
composer test

# Python
cd python && PYTHONPATH=src python3 -m unittest discover -s tests

# Go
cd go && go test ./...

# TypeScript
cd react && npm install && npm test
```

## Contributing

Contributions are welcome. Read [CONTRIBUTING.md](CONTRIBUTING.md), add tests for behavioral changes, and open a pull request.

## License

WorldMoneyWords is open-source software released under the [MIT License](LICENSE).

<div align="center">

Made for developers building invoices, payments, banking tools, receipts, and financial applications.

</div>
