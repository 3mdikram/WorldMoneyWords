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

## 🌍 Country and currency support

> **Quick reference:** These 15 commonly used countries stay visible. Expand the section below to browse every other supported ISO country.

|  | Country | ISO country | Currency | ISO currency |
|:---:|---|:---:|---|:---:|
| 🇮🇳 | **India** | `IND` | Indian Rupee | `INR` |
| 🇺🇸 | **United States** | `USA` | US Dollar | `USD` |
| 🇬🇧 | **United Kingdom** | `GBR` | Pound Sterling | `GBP` |
| 🇦🇪 | **United Arab Emirates** | `ARE` | UAE Dirham | `AED` |
| 🇨🇦 | **Canada** | `CAN` | Canadian Dollar | `CAD` |
| 🇦🇺 | **Australia** | `AUS` | Australian Dollar | `AUD` |
| 🇩🇪 | **Germany** | `DEU` | Euro | `EUR` |
| 🇫🇷 | **France** | `FRA` | Euro | `EUR` |
| 🇯🇵 | **Japan** | `JPN` | Japanese Yen | `JPY` |
| 🇨🇳 | **China** | `CHN` | Yuan | `CNY` |
| 🇸🇬 | **Singapore** | `SGP` | Singapore Dollar | `SGD` |
| 🇸🇦 | **Saudi Arabia** | `SAU` | Saudi Riyal | `SAR` |
| 🇧🇷 | **Brazil** | `BRA` | Brazilian Real | `BRL` |
| 🇿🇦 | **South Africa** | `ZAF` | Rand | `ZAR` |
| 🇳🇿 | **New Zealand** | `NZL` | New Zealand Dollar | `NZD` |

<details>
<summary><strong>🌐 Browse all other 235 supported countries</strong></summary>

<br>

|  | Country | Alpha-2 | Alpha-3 |
|:---:|---|:---:|:---:|
| 🇦🇫 | **Afghanistan** | `AF` | `AFG` |
| 🇦🇽 | **Åland Islands** | `AX` | `ALA` |
| 🇦🇱 | **Albania** | `AL` | `ALB` |
| 🇩🇿 | **Algeria** | `DZ` | `DZA` |
| 🇦🇸 | **American Samoa** | `AS` | `ASM` |
| 🇦🇩 | **Andorra** | `AD` | `AND` |
| 🇦🇴 | **Angola** | `AO` | `AGO` |
| 🇦🇮 | **Anguilla** | `AI` | `AIA` |
| 🇦🇶 | **Antarctica** | `AQ` | `ATA` |
| 🇦🇬 | **Antigua and Barbuda** | `AG` | `ATG` |
| 🇦🇷 | **Argentina** | `AR` | `ARG` |
| 🇦🇲 | **Armenia** | `AM` | `ARM` |
| 🇦🇼 | **Aruba** | `AW` | `ABW` |
| 🇦🇹 | **Austria** | `AT` | `AUT` |
| 🇦🇿 | **Azerbaijan** | `AZ` | `AZE` |
| 🇧🇸 | **Bahamas** | `BS` | `BHS` |
| 🇧🇭 | **Bahrain** | `BH` | `BHR` |
| 🇧🇩 | **Bangladesh** | `BD` | `BGD` |
| 🇧🇧 | **Barbados** | `BB` | `BRB` |
| 🇧🇾 | **Belarus** | `BY` | `BLR` |
| 🇧🇪 | **Belgium** | `BE` | `BEL` |
| 🇧🇿 | **Belize** | `BZ` | `BLZ` |
| 🇧🇯 | **Benin** | `BJ` | `BEN` |
| 🇧🇲 | **Bermuda** | `BM` | `BMU` |
| 🇧🇹 | **Bhutan** | `BT` | `BTN` |
| 🇧🇴 | **Bolivia (Plurinational State of)** | `BO` | `BOL` |
| 🇧🇶 | **Bonaire, Sint Eustatius and Saba** | `BQ` | `BES` |
| 🇧🇦 | **Bosnia and Herzegovina** | `BA` | `BIH` |
| 🇧🇼 | **Botswana** | `BW` | `BWA` |
| 🇧🇻 | **Bouvet Island** | `BV` | `BVT` |
| 🇮🇴 | **British Indian Ocean Territory** | `IO` | `IOT` |
| 🇧🇳 | **Brunei Darussalam** | `BN` | `BRN` |
| 🇧🇬 | **Bulgaria** | `BG` | `BGR` |
| 🇧🇫 | **Burkina Faso** | `BF` | `BFA` |
| 🇧🇮 | **Burundi** | `BI` | `BDI` |
| 🇨🇻 | **Cabo Verde** | `CV` | `CPV` |
| 🇰🇭 | **Cambodia** | `KH` | `KHM` |
| 🇨🇲 | **Cameroon** | `CM` | `CMR` |
| 🇰🇾 | **Cayman Islands** | `KY` | `CYM` |
| 🇨🇫 | **Central African Republic** | `CF` | `CAF` |
| 🇹🇩 | **Chad** | `TD` | `TCD` |
| 🇨🇱 | **Chile** | `CL` | `CHL` |
| 🇨🇽 | **Christmas Island** | `CX` | `CXR` |
| 🇨🇨 | **Cocos (Keeling) Islands** | `CC` | `CCK` |
| 🇨🇴 | **Colombia** | `CO` | `COL` |
| 🇰🇲 | **Comoros** | `KM` | `COM` |
| 🇨🇬 | **Congo** | `CG` | `COG` |
| 🇨🇩 | **Congo (Democratic Republic of the)** | `CD` | `COD` |
| 🇨🇰 | **Cook Islands** | `CK` | `COK` |
| 🇨🇷 | **Costa Rica** | `CR` | `CRI` |
| 🇨🇮 | **Côte d'Ivoire** | `CI` | `CIV` |
| 🇭🇷 | **Croatia** | `HR` | `HRV` |
| 🇨🇺 | **Cuba** | `CU` | `CUB` |
| 🇨🇼 | **Curaçao** | `CW` | `CUW` |
| 🇨🇾 | **Cyprus** | `CY` | `CYP` |
| 🇨🇿 | **Czechia** | `CZ` | `CZE` |
| 🇩🇰 | **Denmark** | `DK` | `DNK` |
| 🇩🇯 | **Djibouti** | `DJ` | `DJI` |
| 🇩🇲 | **Dominica** | `DM` | `DMA` |
| 🇩🇴 | **Dominican Republic** | `DO` | `DOM` |
| 🇪🇨 | **Ecuador** | `EC` | `ECU` |
| 🇪🇬 | **Egypt** | `EG` | `EGY` |
| 🇸🇻 | **El Salvador** | `SV` | `SLV` |
| 🇬🇶 | **Equatorial Guinea** | `GQ` | `GNQ` |
| 🇪🇷 | **Eritrea** | `ER` | `ERI` |
| 🇪🇪 | **Estonia** | `EE` | `EST` |
| 🇪🇹 | **Ethiopia** | `ET` | `ETH` |
| 🇸🇿 | **Eswatini** | `SZ` | `SWZ` |
| 🇫🇰 | **Falkland Islands (Malvinas)** | `FK` | `FLK` |
| 🇫🇴 | **Faroe Islands** | `FO` | `FRO` |
| 🇫🇯 | **Fiji** | `FJ` | `FJI` |
| 🇫🇮 | **Finland** | `FI` | `FIN` |
| 🇬🇫 | **French Guiana** | `GF` | `GUF` |
| 🇵🇫 | **French Polynesia** | `PF` | `PYF` |
| 🇹🇫 | **French Southern Territories** | `TF` | `ATF` |
| 🇬🇦 | **Gabon** | `GA` | `GAB` |
| 🇬🇲 | **Gambia** | `GM` | `GMB` |
| 🇬🇪 | **Georgia** | `GE` | `GEO` |
| 🇬🇭 | **Ghana** | `GH` | `GHA` |
| 🇬🇮 | **Gibraltar** | `GI` | `GIB` |
| 🇬🇷 | **Greece** | `GR` | `GRC` |
| 🇬🇱 | **Greenland** | `GL` | `GRL` |
| 🇬🇩 | **Grenada** | `GD` | `GRD` |
| 🇬🇵 | **Guadeloupe** | `GP` | `GLP` |
| 🇬🇺 | **Guam** | `GU` | `GUM` |
| 🇬🇹 | **Guatemala** | `GT` | `GTM` |
| 🇬🇬 | **Guernsey** | `GG` | `GGY` |
| 🇬🇳 | **Guinea** | `GN` | `GIN` |
| 🇬🇼 | **Guinea-Bissau** | `GW` | `GNB` |
| 🇬🇾 | **Guyana** | `GY` | `GUY` |
| 🇭🇹 | **Haiti** | `HT` | `HTI` |
| 🇭🇲 | **Heard Island and McDonald Islands** | `HM` | `HMD` |
| 🇻🇦 | **Holy See** | `VA` | `VAT` |
| 🇭🇳 | **Honduras** | `HN` | `HND` |
| 🇭🇰 | **Hong Kong** | `HK` | `HKG` |
| 🇭🇺 | **Hungary** | `HU` | `HUN` |
| 🇮🇸 | **Iceland** | `IS` | `ISL` |
| 🇮🇩 | **Indonesia** | `ID` | `IDN` |
| 🇮🇷 | **Iran (Islamic Republic of)** | `IR` | `IRN` |
| 🇮🇶 | **Iraq** | `IQ` | `IRQ` |
| 🇮🇪 | **Ireland** | `IE` | `IRL` |
| 🇮🇲 | **Isle of Man** | `IM` | `IMN` |
| 🇮🇱 | **Israel** | `IL` | `ISR` |
| 🇮🇹 | **Italy** | `IT` | `ITA` |
| 🇯🇲 | **Jamaica** | `JM` | `JAM` |
| 🇯🇪 | **Jersey** | `JE` | `JEY` |
| 🇯🇴 | **Jordan** | `JO` | `JOR` |
| 🇰🇿 | **Kazakhstan** | `KZ` | `KAZ` |
| 🇰🇪 | **Kenya** | `KE` | `KEN` |
| 🇰🇮 | **Kiribati** | `KI` | `KIR` |
| 🇰🇵 | **Korea (Democratic People's Republic of)** | `KP` | `PRK` |
| 🇰🇷 | **Korea (Republic of)** | `KR` | `KOR` |
| 🇽🇰 | **Kosovo** | `XK` | `XKX` |
| 🇰🇼 | **Kuwait** | `KW` | `KWT` |
| 🇰🇬 | **Kyrgyzstan** | `KG` | `KGZ` |
| 🇱🇦 | **Lao People's Democratic Republic** | `LA` | `LAO` |
| 🇱🇻 | **Latvia** | `LV` | `LVA` |
| 🇱🇧 | **Lebanon** | `LB` | `LBN` |
| 🇱🇸 | **Lesotho** | `LS` | `LSO` |
| 🇱🇷 | **Liberia** | `LR` | `LBR` |
| 🇱🇾 | **Libya** | `LY` | `LBY` |
| 🇱🇮 | **Liechtenstein** | `LI` | `LIE` |
| 🇱🇹 | **Lithuania** | `LT` | `LTU` |
| 🇱🇺 | **Luxembourg** | `LU` | `LUX` |
| 🇲🇴 | **Macao** | `MO` | `MAC` |
| 🇲🇰 | **North Macedonia** | `MK` | `MKD` |
| 🇲🇬 | **Madagascar** | `MG` | `MDG` |
| 🇲🇼 | **Malawi** | `MW` | `MWI` |
| 🇲🇾 | **Malaysia** | `MY` | `MYS` |
| 🇲🇻 | **Maldives** | `MV` | `MDV` |
| 🇲🇱 | **Mali** | `ML` | `MLI` |
| 🇲🇹 | **Malta** | `MT` | `MLT` |
| 🇲🇭 | **Marshall Islands** | `MH` | `MHL` |
| 🇲🇶 | **Martinique** | `MQ` | `MTQ` |
| 🇲🇷 | **Mauritania** | `MR` | `MRT` |
| 🇲🇺 | **Mauritius** | `MU` | `MUS` |
| 🇾🇹 | **Mayotte** | `YT` | `MYT` |
| 🇲🇽 | **Mexico** | `MX` | `MEX` |
| 🇫🇲 | **Micronesia (Federated States of)** | `FM` | `FSM` |
| 🇲🇩 | **Moldova (Republic of)** | `MD` | `MDA` |
| 🇲🇨 | **Monaco** | `MC` | `MCO` |
| 🇲🇳 | **Mongolia** | `MN` | `MNG` |
| 🇲🇪 | **Montenegro** | `ME` | `MNE` |
| 🇲🇸 | **Montserrat** | `MS` | `MSR` |
| 🇲🇦 | **Morocco** | `MA` | `MAR` |
| 🇲🇿 | **Mozambique** | `MZ` | `MOZ` |
| 🇲🇲 | **Myanmar** | `MM` | `MMR` |
| 🇳🇦 | **Namibia** | `NA` | `NAM` |
| 🇳🇷 | **Naoero** | `NR` | `NRU` |
| 🇳🇵 | **Nepal** | `NP` | `NPL` |
| 🇳🇱 | **Netherlands** | `NL` | `NLD` |
| 🇳🇨 | **New Caledonia** | `NC` | `NCL` |
| 🇳🇮 | **Nicaragua** | `NI` | `NIC` |
| 🇳🇪 | **Niger** | `NE` | `NER` |
| 🇳🇬 | **Nigeria** | `NG` | `NGA` |
| 🇳🇺 | **Niue** | `NU` | `NIU` |
| 🇳🇫 | **Norfolk Island** | `NF` | `NFK` |
| 🇲🇵 | **Northern Mariana Islands** | `MP` | `MNP` |
| 🇳🇴 | **Norway** | `NO` | `NOR` |
| 🇴🇲 | **Oman** | `OM` | `OMN` |
| 🇵🇰 | **Pakistan** | `PK` | `PAK` |
| 🇵🇼 | **Palau** | `PW` | `PLW` |
| 🇵🇸 | **Palestine, State of** | `PS` | `PSE` |
| 🇵🇦 | **Panama** | `PA` | `PAN` |
| 🇵🇬 | **Papua New Guinea** | `PG` | `PNG` |
| 🇵🇾 | **Paraguay** | `PY` | `PRY` |
| 🇵🇪 | **Peru** | `PE` | `PER` |
| 🇵🇭 | **Philippines** | `PH` | `PHL` |
| 🇵🇳 | **Pitcairn** | `PN` | `PCN` |
| 🇵🇱 | **Poland** | `PL` | `POL` |
| 🇵🇹 | **Portugal** | `PT` | `PRT` |
| 🇵🇷 | **Puerto Rico** | `PR` | `PRI` |
| 🇶🇦 | **Qatar** | `QA` | `QAT` |
| 🇷🇪 | **Réunion** | `RE` | `REU` |
| 🇷🇴 | **Romania** | `RO` | `ROU` |
| 🇷🇺 | **Russian Federation** | `RU` | `RUS` |
| 🇷🇼 | **Rwanda** | `RW` | `RWA` |
| 🇧🇱 | **Saint Barthélemy** | `BL` | `BLM` |
| 🇸🇭 | **Saint Helena, Ascension and Tristan da Cunha** | `SH` | `SHN` |
| 🇰🇳 | **Saint Kitts and Nevis** | `KN` | `KNA` |
| 🇱🇨 | **Saint Lucia** | `LC` | `LCA` |
| 🇲🇫 | **Saint Martin (French part)** | `MF` | `MAF` |
| 🇵🇲 | **Saint Pierre and Miquelon** | `PM` | `SPM` |
| 🇻🇨 | **Saint Vincent and the Grenadines** | `VC` | `VCT` |
| 🇼🇸 | **Samoa** | `WS` | `WSM` |
| 🇸🇲 | **San Marino** | `SM` | `SMR` |
| 🇸🇹 | **Sao Tome and Principe** | `ST` | `STP` |
| 🇸🇳 | **Senegal** | `SN` | `SEN` |
| 🇷🇸 | **Serbia** | `RS` | `SRB` |
| 🇸🇨 | **Seychelles** | `SC` | `SYC` |
| 🇸🇱 | **Sierra Leone** | `SL` | `SLE` |
| 🇸🇽 | **Sint Maarten (Dutch part)** | `SX` | `SXM` |
| 🇸🇰 | **Slovakia** | `SK` | `SVK` |
| 🇸🇮 | **Slovenia** | `SI` | `SVN` |
| 🇸🇧 | **Solomon Islands** | `SB` | `SLB` |
| 🇸🇴 | **Somalia** | `SO` | `SOM` |
| 🇬🇸 | **South Georgia and the South Sandwich Islands** | `GS` | `SGS` |
| 🇸🇸 | **South Sudan** | `SS` | `SSD` |
| 🇪🇸 | **Spain** | `ES` | `ESP` |
| 🇱🇰 | **Sri Lanka** | `LK` | `LKA` |
| 🇸🇩 | **Sudan** | `SD` | `SDN` |
| 🇸🇷 | **Suriname** | `SR` | `SUR` |
| 🇸🇯 | **Svalbard and Jan Mayen** | `SJ` | `SJM` |
| 🇸🇪 | **Sweden** | `SE` | `SWE` |
| 🇨🇭 | **Switzerland** | `CH` | `CHE` |
| 🇸🇾 | **Syrian Arab Republic** | `SY` | `SYR` |
| 🇹🇼 | **Taiwan (Province of China)** | `TW` | `TWN` |
| 🇹🇯 | **Tajikistan** | `TJ` | `TJK` |
| 🇹🇿 | **Tanzania, United Republic of** | `TZ` | `TZA` |
| 🇹🇭 | **Thailand** | `TH` | `THA` |
| 🇹🇱 | **Timor-Leste** | `TL` | `TLS` |
| 🇹🇬 | **Togo** | `TG` | `TGO` |
| 🇹🇰 | **Tokelau** | `TK` | `TKL` |
| 🇹🇴 | **Tonga** | `TO` | `TON` |
| 🇹🇹 | **Trinidad and Tobago** | `TT` | `TTO` |
| 🇹🇳 | **Tunisia** | `TN` | `TUN` |
| 🇹🇷 | **Türkiye** | `TR` | `TUR` |
| 🇹🇲 | **Turkmenistan** | `TM` | `TKM` |
| 🇹🇨 | **Turks and Caicos Islands** | `TC` | `TCA` |
| 🇹🇻 | **Tuvalu** | `TV` | `TUV` |
| 🇺🇬 | **Uganda** | `UG` | `UGA` |
| 🇺🇦 | **Ukraine** | `UA` | `UKR` |
| 🇺🇲 | **United States Minor Outlying Islands** | `UM` | `UMI` |
| 🇺🇾 | **Uruguay** | `UY` | `URY` |
| 🇺🇿 | **Uzbekistan** | `UZ` | `UZB` |
| 🇻🇺 | **Vanuatu** | `VU` | `VUT` |
| 🇻🇪 | **Venezuela (Bolivarian Republic of)** | `VE` | `VEN` |
| 🇻🇳 | **Viet Nam** | `VN` | `VNM` |
| 🇻🇬 | **Virgin Islands (British)** | `VG` | `VGB` |
| 🇻🇮 | **Virgin Islands (U.S.)** | `VI` | `VIR` |
| 🇼🇫 | **Wallis and Futuna** | `WF` | `WLF` |
| 🇪🇭 | **Western Sahara** | `EH` | `ESH` |
| 🇾🇪 | **Yemen** | `YE` | `YEM` |
| 🇿🇲 | **Zambia** | `ZM` | `ZMB` |
| 🇿🇼 | **Zimbabwe** | `ZW` | `ZWE` |

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
