<div align="center">

# 🌍 WorldMoneyWords

**Convert money amounts into accurate, human-readable words for invoices, cheques, receipts, banking forms, and financial documents.**

[![Tests](https://github.com/3mdikram/WorldMoneyWords/actions/workflows/tests.yml/badge.svg)](https://github.com/3mdikram/WorldMoneyWords/actions/workflows/tests.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Countries](https://img.shields.io/badge/important_countries-200-1f6feb)](#country-and-currency-support)
[![Languages](https://img.shields.io/badge/platforms-PHP_%7C_Python_%7C_Go_%7C_Java_%7C_TypeScript-orange)](#platforms)

[Quick start](#quick-start) · [Platforms](#platforms) · [Advanced usage](#advanced-php-usage) · [Contributing](CONTRIBUTING.md)

</div>

---

```text
Input:  1234.50, IND, INR
Output: One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only
```

WorldMoneyWords is an amount-to-words library for PHP, Laravel, Python, Go, Java, React, TypeScript, browsers, and Node.js. Give it an amount, an ISO country code, and an ISO currency code; it returns correctly formatted wording with the appropriate major and minor currency units.

Use it as an **amount to words**, **amount to text**, **money to words**, **money to text**, **number to words**, **number to text**, or **currency to words** converter. It is designed for cheque amounts, invoices, receipts, payment confirmations, banking forms, and other financial documents.

Common searches and use cases include **amount in words**, **amount converter**, **number converter**, **money converter**, **currency converter**, **spell amount**, **spell number**, **cheque writing**, **check writing**, **multi-currency**, and **international currency** formatting.

It supports 200 important countries, ISO 3166-1 Alpha-2 and Alpha-3 country codes, ISO 4217 currency codes, exact decimal-string handling in PHP, negative values, configurable wording, and singular/plural grammar.

The same simple contract is available on every supported platform:

```text
convert(amount, countryCode, currencyCode, options?)
```

## Why WorldMoneyWords?

| Feature | Description |
|---|---|
| 🌐 Important country support | Accepts ISO 3166-1 Alpha-2 and Alpha-3 codes for 200 important countries |
| 💱 Currency aware | Supports ISO 4217 names, minor units, and 0/2/3 decimal currencies |
| 🎯 Financial accuracy | Exact decimal-string rounding avoids binary floating-point loss in PHP |
| 🔄 Automatic code normalization | Converts Alpha-2 codes such as `IN` and `US` to canonical Alpha-3 codes |
| ⚙️ Configurable wording | Control the `Only` suffix, zero minor units, and conjunction text |
| 🗣️ Multilingual output | Generate amount wording in English (`en`), Hindi (`hi`), or Arabic (`ar`) for every supported country |
| 🔢 Formatted amount input | Accepts plain values and correctly grouped input such as `1,234,567.89` |
| ➖ Negative amount support | Produces readable wording for refunds, adjustments, and negative balances |
| ✍️ Correct grammar | Handles singular and plural major and minor currency units |
| 🧾 Structured results | Returns JSON-serializable text, country, currency, major amount, and minor amount |
| 📚 Discovery helpers | Lists supported countries and currencies for validation and form controls |
| 🔠 Case insensitive | Accepts country and currency codes in upper or lowercase |
| 🧩 Multi-platform | PHP, Laravel, Python, Go, Java, React, browsers, and Node.js |
| 📦 Batch friendly | Converts multiple monetary values in a single call |
| 🔧 Extensible | Register or override currency names and minor units |
| 🛡️ Clear validation | Provides specific errors for invalid amounts, countries, and currencies |

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
| Python | `worldmoneywords` | `converter.convert(...)` |
| Go | `github.com/3mdikram/WorldMoneyWords/go/v2` | `amountwords.Convert(...)` |
| Java | `io.github.3mdikram:worldmoneywords` | `converter.convert(...)` |
| React / Node.js | `@3mdikram/worldmoneywords` | `amountToWords(...)` |

### Multilingual support matrix

| Platform | `EN` | `HI` | `AR` | 200-country validation |
|---|:---:|:---:|:---:|:---:|
| PHP | ✅ | ✅ | ✅ | ✅ |
| Laravel | ✅ | ✅ | ✅ | ✅ |
| Python | ✅ | ✅ | ✅ | ✅ |
| Go | ✅ | ✅ | ✅ | ✅ |
| Java | ✅ | ✅ | ✅ | ✅ |
| React / TypeScript / Node.js | ✅ | ✅ | ✅ | ✅ |

Every native implementation runs the same three language options across all 200 Alpha-2/Alpha-3 country mappings. Laravel uses the tested PHP core implementation.

<details>
<summary><strong>Laravel example</strong></summary>

```bash
composer require 3mdikram/worldmoneywords-laravel
```

#### 1. Default conversion

```php
use AmountToWords\Laravel\Facades\AmountToWords;

echo AmountToWords::convert('99.95', 'USA', 'USD');
// Ninety Nine US Dollars and Ninety Five Cents Only
```

#### 2. Conversion with options

```php
use AmountToWords\ConversionOptions;
use AmountToWords\Laravel\Facades\AmountToWords;

$options = new ConversionOptions(
    includeOnly: false,
    includeZeroMinor: true,
    conjunction: 'plus',
);

echo AmountToWords::convert('10.00', 'US', 'USD', $options);
// Ten US Dollars plus Zero Cents
```

Laravel discovers the provider and facade automatically. See the [Laravel guide](laravel/README.md).

</details>

<details>
<summary><strong>Python example</strong></summary>

```bash
pip install worldmoneywords
```

#### 1. Default conversion

```python
from amount_to_words import AmountToWords

converter = AmountToWords()
print(converter.convert("42.50", "GBR", "GBP"))
# Forty Two Pounds and Fifty Pence Only
```

#### 2. Conversion with options

```python
from amount_to_words import AmountToWords, ConversionOptions

options = ConversionOptions(
    include_only=False,
    include_zero_minor=True,
    conjunction="plus",
)

print(AmountToWords().convert("10.00", "US", "USD", options))
# Ten US Dollars plus Zero Cents
```

</details>

<details>
<summary><strong>Go example</strong></summary>

```bash
go get github.com/3mdikram/WorldMoneyWords/go/v2
```

#### 1. Default conversion

```go
package main

import (
    "fmt"
    amountwords "github.com/3mdikram/WorldMoneyWords/go/v2"
)

func main() {
    text, err := amountwords.Convert(42.50, "GBR", "GBP")
    if err != nil {
        panic(err)
    }
    fmt.Println(text)
}
```

#### 2. Conversion with options

```go
options := amountwords.Options{
    IncludeOnly:      false,
    IncludeZeroMinor: true,
    Conjunction:      "plus",
}

text, err := amountwords.Convert(10, "US", "USD", options)
if err != nil {
    panic(err)
}
fmt.Println(text)
// Ten US Dollars plus Zero Cents
```

</details>

<details>
<summary><strong>Java example</strong></summary>

```bash
cd java
mvn install
```

#### 1. Default conversion

```java
import io.github.mdikram.worldmoneywords.WorldMoneyWords;

WorldMoneyWords converter = new WorldMoneyWords();
String text = converter.convert("42.50", "GB", "GBP");
// Forty Two Pounds and Fifty Pence Only
```

#### 2. Conversion with options

```java
import io.github.mdikram.worldmoneywords.ConversionOptions;
import io.github.mdikram.worldmoneywords.WorldMoneyWords;

WorldMoneyWords converter = new WorldMoneyWords();
ConversionOptions options = new ConversionOptions(false, true, "plus");

String text = converter.convert("10.00", "US", "USD", options);
// Ten US Dollars plus Zero Cents
```

Requires Java 11 or newer. See the [Java guide](java/README.md).

</details>

<details>
<summary><strong>React / TypeScript example</strong></summary>

```bash
npm install @3mdikram/worldmoneywords
```

#### 1. Default conversion

```tsx
import { amountToWords } from '@3mdikram/worldmoneywords';

export function PaymentTotal() {
  const words = amountToWords('1500.25', 'USA', 'USD');
  return <p>{words}</p>;
}
```

#### 2. Conversion with options

```tsx
import { amountToWords } from '@3mdikram/worldmoneywords';

export function CustomPaymentTotal() {
  const words = amountToWords('10.00', 'US', 'USD', {
    includeOnly: false,
    includeZeroMinor: true,
    conjunction: 'plus',
  });

  return <p>{words}</p>;
  // Ten US Dollars plus Zero Cents
}
```

The TypeScript package has no React runtime dependency, so it also works in browsers and Node.js.

</details>

## Core API

```php
convert(int|float|string $amount, string $countryCode, string $currencyCode, ?ConversionOptions $options = null): string
```

| Argument | Example | Rules |
|---|---|---|
| `amount` | `'1,234.50'` | Plain or correctly grouped numeric value; strings are recommended |
| `countryCode` | `'IN'` or `'IND'` | ISO 3166-1 Alpha-2 or Alpha-3 code, case-insensitive |
| `currencyCode` | `'INR'` | ISO 4217 code, case-insensitive |
| `options` | `new ConversionOptions(...)` | Optional fourth argument used to customize the generated sentence |

### Conversion options

| Option | Type | Default | What it does | Example result |
|---|:---:|:---:|---|---|
| `includeOnly` | `boolean` | `true` | Adds `Only` to the end of the amount | `Ten US Dollars Only` |
| `includeZeroMinor` | `boolean` | `false` | Includes the minor unit when its value is zero | `Ten US Dollars and Zero Cents Only` |
| `conjunction` | `string` | `and` | Changes the word joining major and minor units | `Ten US Dollars plus Fifty Cents Only` |
| `language` | `string` | `en` | Selects English (`en`), Hindi (`hi`), or Arabic (`ar`) output | `एक हज़ार भारतीय रुपया मात्र` |

Use the options when you need wording tailored to invoices, cheques, banking forms, or local document rules:

- Keep the defaults for standard financial wording.
- Set `includeOnly` to `false` when a document does not require an `Only` suffix.
- Set `includeZeroMinor` to `true` when zero cents, paise, or fils must be stated explicitly.
- Change `conjunction` to wording such as `plus` when required by your output format.

Option names follow each language's normal style:

| Option | PHP / TypeScript | Python | Go | Java |
|---|---|---|---|---|
| Only suffix | `includeOnly` | `include_only` | `IncludeOnly` | `includeOnly` |
| Zero minor unit | `includeZeroMinor` | `include_zero_minor` | `IncludeZeroMinor` | `includeZeroMinor` |
| Conjunction | `conjunction` | `conjunction` | `Conjunction` | `conjunction` |
| Output language | `language` | `language` | `Language` | `language` |

### Hindi and Arabic output

```php
$hindi = $converter->convert('1234.50', 'IN', 'INR', new ConversionOptions(language: 'hi'));
// एक हज़ार दो सौ चौंतीस भारतीय रुपया और पचास उप-इकाई मात्र

$arabic = $converter->convert('1234.50', 'US', 'USD', new ConversionOptions(language: 'ar'));
// ألف ومائتان وأربعة وثلاثون دولار أمريكي و خمسون وحدة فرعية فقط
```

Language selection changes only the generated wording. ISO Alpha-2/Alpha-3 country validation remains available for all 200 supported countries.

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

echo $converter->convert('10.00', 'USA', 'USD', $options);
// Ten US Dollars plus Zero Cents
```

The same optional fourth argument is available in every native implementation:

```python
from amount_to_words import AmountToWords, ConversionOptions

options = ConversionOptions(include_only=False, include_zero_minor=True, conjunction="plus")
text = AmountToWords().convert("10.00", "US", "USD", options)
```

```go
options := amountwords.Options{
    IncludeOnly: false,
    IncludeZeroMinor: true,
    Conjunction: "plus",
}
text, err := amountwords.Convert(10, "US", "USD", options)
```

```ts
const text = amountToWords('10.00', 'US', 'USD', {
  includeOnly: false,
  includeZeroMinor: true,
  conjunction: 'plus',
});
```

```java
ConversionOptions options = new ConversionOptions(false, true, "plus");
String text = new WorldMoneyWords().convert("10.00", "US", "USD", options);
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
>
> **Country-language codes:** `EN` = English, `HI` = Hindi, `AR` = Arabic. Each row lists the currently implemented output languages that are official or de facto official for that country. `—` means the country's local official language is not implemented yet. The converter can still generate `EN`, `HI`, or `AR` for any supported country when explicitly requested.
>
> Country-language matching is based on [Unicode CLDR territory-language data](https://unicode.org/cldr/charts/latest/supplemental/territory_language_information.html).

|  | Country | **Alpha-2** | **Alpha-3** | Currency | ISO currency | Country languages |
|:---:|---|:---:|:---:|---|:---:|---|
| 🇮🇳 | **India** | `IN` | `IND` | Indian Rupee | `INR` | `HI` · `EN` |
| 🇺🇸 | **United States** | `US` | `USA` | US Dollar | `USD` | `EN` |
| 🇬🇧 | **United Kingdom** | `GB` | `GBR` | Pound Sterling | `GBP` | `EN` |
| 🇦🇪 | **United Arab Emirates** | `AE` | `ARE` | UAE Dirham | `AED` | `AR` |
| 🇨🇦 | **Canada** | `CA` | `CAN` | Canadian Dollar | `CAD` | `EN` |
| 🇦🇺 | **Australia** | `AU` | `AUS` | Australian Dollar | `AUD` | `EN` |
| 🇩🇪 | **Germany** | `DE` | `DEU` | Euro | `EUR` | — |
| 🇫🇷 | **France** | `FR` | `FRA` | Euro | `EUR` | — |
| 🇯🇵 | **Japan** | `JP` | `JPN` | Japanese Yen | `JPY` | — |
| 🇨🇳 | **China** | `CN` | `CHN` | Yuan | `CNY` | — |
| 🇸🇬 | **Singapore** | `SG` | `SGP` | Singapore Dollar | `SGD` | `EN` |
| 🇸🇦 | **Saudi Arabia** | `SA` | `SAU` | Saudi Riyal | `SAR` | `AR` |
| 🇧🇷 | **Brazil** | `BR` | `BRA` | Brazilian Real | `BRL` | — |
| 🇿🇦 | **South Africa** | `ZA` | `ZAF` | Rand | `ZAR` | `EN` |
| 🇳🇿 | **New Zealand** | `NZ` | `NZL` | New Zealand Dollar | `NZD` | `EN` |

<details>
<summary><strong>🌐 Browse all other 185 supported countries</strong></summary>

<br>

|  | Country | **Alpha-2** | **Alpha-3** | Currency | ISO currency | Country languages |
|:---:|---|:---:|:---:|---|:---:|---|
| 🇦🇫 | **Afghanistan** | `AF` | `AFG` | Afghan Afghani | `AFN` | — |
| 🇦🇱 | **Albania** | `AL` | `ALB` | Albanian Lek | `ALL` | — |
| 🇩🇿 | **Algeria** | `DZ` | `DZA` | Algerian Dinar | `DZD` | `AR` |
| 🇦🇩 | **Andorra** | `AD` | `AND` | Euro | `EUR` | — |
| 🇦🇴 | **Angola** | `AO` | `AGO` | Angolan Kwanza | `AOA` | — |
| 🇦🇬 | **Antigua and Barbuda** | `AG` | `ATG` | East Caribbean Dollar | `XCD` | `EN` |
| 🇦🇷 | **Argentina** | `AR` | `ARG` | Argentine Peso | `ARS` | — |
| 🇦🇲 | **Armenia** | `AM` | `ARM` | Armenian Dram | `AMD` | — |
| 🇦🇹 | **Austria** | `AT` | `AUT` | Euro | `EUR` | — |
| 🇦🇿 | **Azerbaijan** | `AZ` | `AZE` | Azerbaijani Manat | `AZN` | — |
| 🇧🇸 | **Bahamas** | `BS` | `BHS` | Bahamian Dollar | `BSD` | `EN` |
| 🇧🇭 | **Bahrain** | `BH` | `BHR` | Bahraini Dinar | `BHD` | `AR` |
| 🇧🇩 | **Bangladesh** | `BD` | `BGD` | Bangladeshi Taka | `BDT` | — |
| 🇧🇧 | **Barbados** | `BB` | `BRB` | Barbadian Dollar | `BBD` | `EN` |
| 🇧🇾 | **Belarus** | `BY` | `BLR` | Belarusian Ruble | `BYN` | — |
| 🇧🇪 | **Belgium** | `BE` | `BEL` | Euro | `EUR` | — |
| 🇧🇿 | **Belize** | `BZ` | `BLZ` | Belize Dollar | `BZD` | `EN` |
| 🇧🇯 | **Benin** | `BJ` | `BEN` | West African CFA Franc | `XOF` | — |
| 🇧🇹 | **Bhutan** | `BT` | `BTN` | Bhutanese Ngultrum, Indian Rupee | `BTN`, `INR` | — |
| 🇧🇴 | **Bolivia (Plurinational State of)** | `BO` | `BOL` | Bolivian Boliviano | `BOB` | — |
| 🇧🇦 | **Bosnia and Herzegovina** | `BA` | `BIH` | Bosnia-Herzegovina Convertible Mark | `BAM` | — |
| 🇧🇼 | **Botswana** | `BW` | `BWA` | Botswanan Pula | `BWP` | `EN` |
| 🇧🇳 | **Brunei Darussalam** | `BN` | `BRN` | Brunei Dollar | `BND` | — |
| 🇧🇬 | **Bulgaria** | `BG` | `BGR` | Euro | `EUR` | — |
| 🇧🇫 | **Burkina Faso** | `BF` | `BFA` | West African CFA Franc | `XOF` | — |
| 🇧🇮 | **Burundi** | `BI` | `BDI` | Burundian Franc | `BIF` | `EN` |
| 🇨🇻 | **Cabo Verde** | `CV` | `CPV` | Cape Verdean Escudo | `CVE` | — |
| 🇰🇭 | **Cambodia** | `KH` | `KHM` | Cambodian Riel | `KHR` | — |
| 🇨🇲 | **Cameroon** | `CM` | `CMR` | Central African CFA Franc | `XAF` | `EN` |
| 🇨🇫 | **Central African Republic** | `CF` | `CAF` | Central African CFA Franc | `XAF` | — |
| 🇹🇩 | **Chad** | `TD` | `TCD` | Central African CFA Franc | `XAF` | `AR` |
| 🇨🇱 | **Chile** | `CL` | `CHL` | Chilean Peso | `CLP` | — |
| 🇨🇴 | **Colombia** | `CO` | `COL` | Colombian Peso | `COP` | — |
| 🇰🇲 | **Comoros** | `KM` | `COM` | Comorian Franc | `KMF` | `AR` |
| 🇨🇬 | **Congo** | `CG` | `COG` | Central African CFA Franc | `XAF` | — |
| 🇨🇩 | **Congo (Democratic Republic of the)** | `CD` | `COD` | Congolese Franc | `CDF` | — |
| 🇨🇷 | **Costa Rica** | `CR` | `CRI` | Costa Rican Colón | `CRC` | — |
| 🇨🇮 | **Côte d'Ivoire** | `CI` | `CIV` | West African CFA Franc | `XOF` | — |
| 🇭🇷 | **Croatia** | `HR` | `HRV` | Euro | `EUR` | — |
| 🇨🇺 | **Cuba** | `CU` | `CUB` | Cuban Peso | `CUP` | — |
| 🇨🇾 | **Cyprus** | `CY` | `CYP` | Euro | `EUR` | — |
| 🇨🇿 | **Czechia** | `CZ` | `CZE` | Czech Koruna | `CZK` | — |
| 🇩🇰 | **Denmark** | `DK` | `DNK` | Danish Krone | `DKK` | — |
| 🇩🇯 | **Djibouti** | `DJ` | `DJI` | Djiboutian Franc | `DJF` | `AR` |
| 🇩🇲 | **Dominica** | `DM` | `DMA` | East Caribbean Dollar | `XCD` | `EN` |
| 🇩🇴 | **Dominican Republic** | `DO` | `DOM` | Dominican Peso | `DOP` | — |
| 🇪🇨 | **Ecuador** | `EC` | `ECU` | US Dollar | `USD` | — |
| 🇪🇬 | **Egypt** | `EG` | `EGY` | Egyptian Pound | `EGP` | `AR` |
| 🇸🇻 | **El Salvador** | `SV` | `SLV` | US Dollar | `USD` | — |
| 🇬🇶 | **Equatorial Guinea** | `GQ` | `GNQ` | Central African CFA Franc | `XAF` | — |
| 🇪🇷 | **Eritrea** | `ER` | `ERI` | Eritrean Nakfa | `ERN` | `EN` · `AR` |
| 🇪🇪 | **Estonia** | `EE` | `EST` | Euro | `EUR` | — |
| 🇪🇹 | **Ethiopia** | `ET` | `ETH` | Ethiopian Birr | `ETB` | — |
| 🇸🇿 | **Eswatini** | `SZ` | `SWZ` | Swazi Lilangeni | `SZL` | `EN` |
| 🇫🇯 | **Fiji** | `FJ` | `FJI` | Fijian Dollar | `FJD` | `EN` |
| 🇫🇮 | **Finland** | `FI` | `FIN` | Euro | `EUR` | — |
| 🇬🇦 | **Gabon** | `GA` | `GAB` | Central African CFA Franc | `XAF` | — |
| 🇬🇲 | **Gambia** | `GM` | `GMB` | Gambian Dalasi | `GMD` | `EN` |
| 🇬🇪 | **Georgia** | `GE` | `GEO` | Georgian Lari | `GEL` | — |
| 🇬🇭 | **Ghana** | `GH` | `GHA` | Ghanaian Cedi | `GHS` | `EN` |
| 🇬🇷 | **Greece** | `GR` | `GRC` | Euro | `EUR` | — |
| 🇬🇩 | **Grenada** | `GD` | `GRD` | East Caribbean Dollar | `XCD` | `EN` |
| 🇬🇹 | **Guatemala** | `GT` | `GTM` | Guatemalan Quetzal | `GTQ` | — |
| 🇬🇳 | **Guinea** | `GN` | `GIN` | Guinean Franc | `GNF` | — |
| 🇬🇼 | **Guinea-Bissau** | `GW` | `GNB` | West African CFA Franc | `XOF` | — |
| 🇬🇾 | **Guyana** | `GY` | `GUY` | Guyanaese Dollar | `GYD` | `EN` |
| 🇭🇹 | **Haiti** | `HT` | `HTI` | Haitian Gourde, US Dollar | `HTG`, `USD` | — |
| 🇻🇦 | **Holy See** | `VA` | `VAT` | Euro | `EUR` | — |
| 🇭🇳 | **Honduras** | `HN` | `HND` | Honduran Lempira | `HNL` | — |
| 🇭🇰 | **Hong Kong** | `HK` | `HKG` | Hong Kong Dollar | `HKD` | `EN` |
| 🇭🇺 | **Hungary** | `HU` | `HUN` | Hungarian Forint | `HUF` | — |
| 🇮🇸 | **Iceland** | `IS` | `ISL` | Icelandic Króna | `ISK` | — |
| 🇮🇩 | **Indonesia** | `ID` | `IDN` | Indonesian Rupiah | `IDR` | — |
| 🇮🇷 | **Iran (Islamic Republic of)** | `IR` | `IRN` | Iranian Rial | `IRR` | — |
| 🇮🇶 | **Iraq** | `IQ` | `IRQ` | Iraqi Dinar | `IQD` | `AR` |
| 🇮🇪 | **Ireland** | `IE` | `IRL` | Euro | `EUR` | `EN` |
| 🇮🇱 | **Israel** | `IL` | `ISR` | Israeli New Shekel | `ILS` | `AR` |
| 🇮🇹 | **Italy** | `IT` | `ITA` | Euro | `EUR` | — |
| 🇯🇲 | **Jamaica** | `JM` | `JAM` | Jamaican Dollar | `JMD` | `EN` |
| 🇯🇴 | **Jordan** | `JO` | `JOR` | Jordanian Dinar | `JOD` | `AR` |
| 🇰🇿 | **Kazakhstan** | `KZ` | `KAZ` | Kazakhstani Tenge | `KZT` | — |
| 🇰🇪 | **Kenya** | `KE` | `KEN` | Kenyan Shilling | `KES` | `EN` |
| 🇰🇮 | **Kiribati** | `KI` | `KIR` | Australian Dollar | `AUD` | `EN` |
| 🇰🇵 | **Korea (Democratic People's Republic of)** | `KP` | `PRK` | North Korean Won | `KPW` | — |
| 🇰🇷 | **Korea (Republic of)** | `KR` | `KOR` | South Korean Won | `KRW` | — |
| 🇽🇰 | **Kosovo** | `XK` | `XKX` | Euro | `EUR` | — |
| 🇰🇼 | **Kuwait** | `KW` | `KWT` | Kuwaiti Dinar | `KWD` | `AR` |
| 🇰🇬 | **Kyrgyzstan** | `KG` | `KGZ` | Kyrgyz Som | `KGS` | — |
| 🇱🇦 | **Lao People's Democratic Republic** | `LA` | `LAO` | Laotian Kip | `LAK` | — |
| 🇱🇻 | **Latvia** | `LV` | `LVA` | Euro | `EUR` | — |
| 🇱🇧 | **Lebanon** | `LB` | `LBN` | Lebanese Pound | `LBP` | `AR` |
| 🇱🇸 | **Lesotho** | `LS` | `LSO` | South African Rand, Lesotho Loti | `ZAR`, `LSL` | `EN` |
| 🇱🇷 | **Liberia** | `LR` | `LBR` | Liberian Dollar | `LRD` | `EN` |
| 🇱🇾 | **Libya** | `LY` | `LBY` | Libyan Dinar | `LYD` | `AR` |
| 🇱🇮 | **Liechtenstein** | `LI` | `LIE` | Swiss Franc | `CHF` | — |
| 🇱🇹 | **Lithuania** | `LT` | `LTU` | Euro | `EUR` | — |
| 🇱🇺 | **Luxembourg** | `LU` | `LUX` | Euro | `EUR` | — |
| 🇲🇴 | **Macao** | `MO` | `MAC` | Macanese Pataca | `MOP` | — |
| 🇲🇰 | **North Macedonia** | `MK` | `MKD` | Macedonian Denar | `MKD` | — |
| 🇲🇬 | **Madagascar** | `MG` | `MDG` | Malagasy Ariary | `MGA` | `EN` |
| 🇲🇼 | **Malawi** | `MW` | `MWI` | Malawian Kwacha | `MWK` | `EN` |
| 🇲🇾 | **Malaysia** | `MY` | `MYS` | Malaysian Ringgit | `MYR` | — |
| 🇲🇻 | **Maldives** | `MV` | `MDV` | Maldivian Rufiyaa | `MVR` | — |
| 🇲🇱 | **Mali** | `ML` | `MLI` | West African CFA Franc | `XOF` | — |
| 🇲🇹 | **Malta** | `MT` | `MLT` | Euro | `EUR` | `EN` |
| 🇲🇭 | **Marshall Islands** | `MH` | `MHL` | US Dollar | `USD` | `EN` |
| 🇲🇷 | **Mauritania** | `MR` | `MRT` | Mauritanian Ouguiya | `MRU` | `AR` |
| 🇲🇺 | **Mauritius** | `MU` | `MUS` | Mauritian Rupee | `MUR` | `EN` |
| 🇲🇽 | **Mexico** | `MX` | `MEX` | Mexican Peso | `MXN` | — |
| 🇫🇲 | **Micronesia (Federated States of)** | `FM` | `FSM` | US Dollar | `USD` | `EN` |
| 🇲🇩 | **Moldova (Republic of)** | `MD` | `MDA` | Moldovan Leu | `MDL` | — |
| 🇲🇨 | **Monaco** | `MC` | `MCO` | Euro | `EUR` | — |
| 🇲🇳 | **Mongolia** | `MN` | `MNG` | Mongolian Tugrik | `MNT` | — |
| 🇲🇪 | **Montenegro** | `ME` | `MNE` | Euro | `EUR` | — |
| 🇲🇦 | **Morocco** | `MA` | `MAR` | Moroccan Dirham | `MAD` | `AR` |
| 🇲🇿 | **Mozambique** | `MZ` | `MOZ` | Mozambican Metical | `MZN` | — |
| 🇲🇲 | **Myanmar** | `MM` | `MMR` | Myanmar Kyat | `MMK` | — |
| 🇳🇦 | **Namibia** | `NA` | `NAM` | Namibian Dollar, South African Rand | `NAD`, `ZAR` | `EN` |
| 🇳🇷 | **Naoero** | `NR` | `NRU` | Australian Dollar | `AUD` | `EN` |
| 🇳🇵 | **Nepal** | `NP` | `NPL` | Nepalese Rupee | `NPR` | — |
| 🇳🇱 | **Netherlands** | `NL` | `NLD` | Euro | `EUR` | — |
| 🇳🇮 | **Nicaragua** | `NI` | `NIC` | Nicaraguan Córdoba | `NIO` | — |
| 🇳🇪 | **Niger** | `NE` | `NER` | West African CFA Franc | `XOF` | — |
| 🇳🇬 | **Nigeria** | `NG` | `NGA` | Nigerian Naira | `NGN` | `EN` |
| 🇳🇴 | **Norway** | `NO` | `NOR` | Norwegian Krone | `NOK` | — |
| 🇴🇲 | **Oman** | `OM` | `OMN` | Omani Rial | `OMR` | `AR` |
| 🇵🇰 | **Pakistan** | `PK` | `PAK` | Pakistani Rupee | `PKR` | `EN` |
| 🇵🇼 | **Palau** | `PW` | `PLW` | US Dollar | `USD` | `EN` |
| 🇵🇸 | **Palestine, State of** | `PS` | `PSE` | Israeli New Shekel, Jordanian Dinar | `ILS`, `JOD` | `AR` |
| 🇵🇦 | **Panama** | `PA` | `PAN` | Panamanian Balboa, US Dollar | `PAB`, `USD` | — |
| 🇵🇬 | **Papua New Guinea** | `PG` | `PNG` | Papua New Guinean Kina | `PGK` | `EN` |
| 🇵🇾 | **Paraguay** | `PY` | `PRY` | Paraguayan Guarani | `PYG` | — |
| 🇵🇪 | **Peru** | `PE` | `PER` | Peruvian Sol | `PEN` | — |
| 🇵🇭 | **Philippines** | `PH` | `PHL` | Philippine Peso | `PHP` | `EN` |
| 🇵🇱 | **Poland** | `PL` | `POL` | Polish Zloty | `PLN` | — |
| 🇵🇹 | **Portugal** | `PT` | `PRT` | Euro | `EUR` | — |
| 🇶🇦 | **Qatar** | `QA` | `QAT` | Qatari Riyal | `QAR` | `AR` |
| 🇷🇴 | **Romania** | `RO` | `ROU` | Romanian Leu | `RON` | — |
| 🇷🇺 | **Russian Federation** | `RU` | `RUS` | Russian Ruble | `RUB` | — |
| 🇷🇼 | **Rwanda** | `RW` | `RWA` | Rwandan Franc | `RWF` | `EN` |
| 🇰🇳 | **Saint Kitts and Nevis** | `KN` | `KNA` | East Caribbean Dollar | `XCD` | `EN` |
| 🇱🇨 | **Saint Lucia** | `LC` | `LCA` | East Caribbean Dollar | `XCD` | `EN` |
| 🇻🇨 | **Saint Vincent and the Grenadines** | `VC` | `VCT` | East Caribbean Dollar | `XCD` | `EN` |
| 🇼🇸 | **Samoa** | `WS` | `WSM` | Samoan Tala | `WST` | `EN` |
| 🇸🇲 | **San Marino** | `SM` | `SMR` | Euro | `EUR` | — |
| 🇸🇹 | **Sao Tome and Principe** | `ST` | `STP` | São Tomé & Príncipe Dobra | `STN` | — |
| 🇸🇳 | **Senegal** | `SN` | `SEN` | West African CFA Franc | `XOF` | — |
| 🇷🇸 | **Serbia** | `RS` | `SRB` | Serbian Dinar | `RSD` | — |
| 🇸🇨 | **Seychelles** | `SC` | `SYC` | Seychellois Rupee | `SCR` | `EN` |
| 🇸🇱 | **Sierra Leone** | `SL` | `SLE` | Sierra Leonean Leone | `SLE` | `EN` |
| 🇸🇰 | **Slovakia** | `SK` | `SVK` | Euro | `EUR` | — |
| 🇸🇮 | **Slovenia** | `SI` | `SVN` | Euro | `EUR` | — |
| 🇸🇧 | **Solomon Islands** | `SB` | `SLB` | Solomon Islands Dollar | `SBD` | `EN` |
| 🇸🇴 | **Somalia** | `SO` | `SOM` | Somali Shilling | `SOS` | `AR` |
| 🇸🇸 | **South Sudan** | `SS` | `SSD` | South Sudanese Pound | `SSP` | `EN` |
| 🇪🇸 | **Spain** | `ES` | `ESP` | Euro | `EUR` | — |
| 🇱🇰 | **Sri Lanka** | `LK` | `LKA` | Sri Lankan Rupee | `LKR` | — |
| 🇸🇩 | **Sudan** | `SD` | `SDN` | Sudanese Pound | `SDG` | `AR` · `EN` |
| 🇸🇷 | **Suriname** | `SR` | `SUR` | Surinamese Dollar | `SRD` | — |
| 🇸🇪 | **Sweden** | `SE` | `SWE` | Swedish Krona | `SEK` | — |
| 🇨🇭 | **Switzerland** | `CH` | `CHE` | Swiss Franc | `CHF` | — |
| 🇸🇾 | **Syrian Arab Republic** | `SY` | `SYR` | Syrian Pound | `SYP` | `AR` |
| 🇹🇼 | **Taiwan (Province of China)** | `TW` | `TWN` | New Taiwan Dollar | `TWD` | — |
| 🇹🇯 | **Tajikistan** | `TJ` | `TJK` | Tajikistani Somoni | `TJS` | — |
| 🇹🇿 | **Tanzania, United Republic of** | `TZ` | `TZA` | Tanzanian Shilling | `TZS` | `EN` |
| 🇹🇭 | **Thailand** | `TH` | `THA` | Thai Baht | `THB` | — |
| 🇹🇱 | **Timor-Leste** | `TL` | `TLS` | US Dollar | `USD` | — |
| 🇹🇬 | **Togo** | `TG` | `TGO` | West African CFA Franc | `XOF` | — |
| 🇹🇴 | **Tonga** | `TO` | `TON` | Tongan Paʻanga | `TOP` | `EN` |
| 🇹🇹 | **Trinidad and Tobago** | `TT` | `TTO` | Trinidad & Tobago Dollar | `TTD` | `EN` |
| 🇹🇳 | **Tunisia** | `TN` | `TUN` | Tunisian Dinar | `TND` | `AR` |
| 🇹🇷 | **Türkiye** | `TR` | `TUR` | Turkish Lira | `TRY` | — |
| 🇹🇲 | **Turkmenistan** | `TM` | `TKM` | Turkmenistani Manat | `TMT` | — |
| 🇹🇻 | **Tuvalu** | `TV` | `TUV` | Australian Dollar | `AUD` | `EN` |
| 🇺🇬 | **Uganda** | `UG` | `UGA` | Ugandan Shilling | `UGX` | `EN` |
| 🇺🇦 | **Ukraine** | `UA` | `UKR` | Ukrainian Hryvnia | `UAH` | — |
| 🇺🇾 | **Uruguay** | `UY` | `URY` | Uruguayan Peso | `UYU` | — |
| 🇺🇿 | **Uzbekistan** | `UZ` | `UZB` | Uzbekistani Som | `UZS` | — |
| 🇻🇺 | **Vanuatu** | `VU` | `VUT` | Vanuatu Vatu | `VUV` | `EN` |
| 🇻🇪 | **Venezuela (Bolivarian Republic of)** | `VE` | `VEN` | Venezuelan Bolívar | `VES` | — |
| 🇻🇳 | **Viet Nam** | `VN` | `VNM` | Vietnamese Dong | `VND` | — |
| 🇪🇭 | **Western Sahara** | `EH` | `ESH` | Moroccan Dirham | `MAD` | `AR` |
| 🇾🇪 | **Yemen** | `YE` | `YEM` | Yemeni Rial | `YER` | `AR` |
| 🇿🇲 | **Zambia** | `ZM` | `ZMB` | Zambian Kwacha | `ZMW` | `EN` |
| 🇿🇼 | **Zimbabwe** | `ZW` | `ZWE` | Zimbabwean Gold, US Dollar | `ZWG`, `USD` | `EN` |

> Currency assignments follow current Unicode CLDR tender data. `—` indicates that no single current official currency is assigned in that dataset.

</details>

Build dropdowns and validate input using the discovery API:

```php
$converter->isSupportedCountry('IND');  // true
$converter->isSupportedCountry('IN');   // true
$converter->normalizeCountryCode('IN'); // IND
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

# Java
cd java && mvn verify

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
