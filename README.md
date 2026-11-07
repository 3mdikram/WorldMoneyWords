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
> **Language codes:** `EN` = English, `HI` = Hindi, `AR` = Arabic. Every listed country supports all three through the `language` conversion option.

|  | Country | **Alpha-2** | **Alpha-3** | Currency | ISO currency | Supported languages |
|:---:|---|:---:|:---:|---|:---:|---|
| 🇮🇳 | **India** | `IN` | `IND` | Indian Rupee | `INR` | `EN` · `HI` · `AR` |
| 🇺🇸 | **United States** | `US` | `USA` | US Dollar | `USD` | `EN` · `HI` · `AR` |
| 🇬🇧 | **United Kingdom** | `GB` | `GBR` | Pound Sterling | `GBP` | `EN` · `HI` · `AR` |
| 🇦🇪 | **United Arab Emirates** | `AE` | `ARE` | UAE Dirham | `AED` | `EN` · `HI` · `AR` |
| 🇨🇦 | **Canada** | `CA` | `CAN` | Canadian Dollar | `CAD` | `EN` · `HI` · `AR` |
| 🇦🇺 | **Australia** | `AU` | `AUS` | Australian Dollar | `AUD` | `EN` · `HI` · `AR` |
| 🇩🇪 | **Germany** | `DE` | `DEU` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇫🇷 | **France** | `FR` | `FRA` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇯🇵 | **Japan** | `JP` | `JPN` | Japanese Yen | `JPY` | `EN` · `HI` · `AR` |
| 🇨🇳 | **China** | `CN` | `CHN` | Yuan | `CNY` | `EN` · `HI` · `AR` |
| 🇸🇬 | **Singapore** | `SG` | `SGP` | Singapore Dollar | `SGD` | `EN` · `HI` · `AR` |
| 🇸🇦 | **Saudi Arabia** | `SA` | `SAU` | Saudi Riyal | `SAR` | `EN` · `HI` · `AR` |
| 🇧🇷 | **Brazil** | `BR` | `BRA` | Brazilian Real | `BRL` | `EN` · `HI` · `AR` |
| 🇿🇦 | **South Africa** | `ZA` | `ZAF` | Rand | `ZAR` | `EN` · `HI` · `AR` |
| 🇳🇿 | **New Zealand** | `NZ` | `NZL` | New Zealand Dollar | `NZD` | `EN` · `HI` · `AR` |

<details>
<summary><strong>🌐 Browse all other 185 supported countries</strong></summary>

<br>

|  | Country | **Alpha-2** | **Alpha-3** | Currency | ISO currency | Supported languages |
|:---:|---|:---:|:---:|---|:---:|---|
| 🇦🇫 | **Afghanistan** | `AF` | `AFG` | Afghan Afghani | `AFN` | `EN` · `HI` · `AR` |
| 🇦🇱 | **Albania** | `AL` | `ALB` | Albanian Lek | `ALL` | `EN` · `HI` · `AR` |
| 🇩🇿 | **Algeria** | `DZ` | `DZA` | Algerian Dinar | `DZD` | `EN` · `HI` · `AR` |
| 🇦🇩 | **Andorra** | `AD` | `AND` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇦🇴 | **Angola** | `AO` | `AGO` | Angolan Kwanza | `AOA` | `EN` · `HI` · `AR` |
| 🇦🇬 | **Antigua and Barbuda** | `AG` | `ATG` | East Caribbean Dollar | `XCD` | `EN` · `HI` · `AR` |
| 🇦🇷 | **Argentina** | `AR` | `ARG` | Argentine Peso | `ARS` | `EN` · `HI` · `AR` |
| 🇦🇲 | **Armenia** | `AM` | `ARM` | Armenian Dram | `AMD` | `EN` · `HI` · `AR` |
| 🇦🇹 | **Austria** | `AT` | `AUT` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇦🇿 | **Azerbaijan** | `AZ` | `AZE` | Azerbaijani Manat | `AZN` | `EN` · `HI` · `AR` |
| 🇧🇸 | **Bahamas** | `BS` | `BHS` | Bahamian Dollar | `BSD` | `EN` · `HI` · `AR` |
| 🇧🇭 | **Bahrain** | `BH` | `BHR` | Bahraini Dinar | `BHD` | `EN` · `HI` · `AR` |
| 🇧🇩 | **Bangladesh** | `BD` | `BGD` | Bangladeshi Taka | `BDT` | `EN` · `HI` · `AR` |
| 🇧🇧 | **Barbados** | `BB` | `BRB` | Barbadian Dollar | `BBD` | `EN` · `HI` · `AR` |
| 🇧🇾 | **Belarus** | `BY` | `BLR` | Belarusian Ruble | `BYN` | `EN` · `HI` · `AR` |
| 🇧🇪 | **Belgium** | `BE` | `BEL` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇧🇿 | **Belize** | `BZ` | `BLZ` | Belize Dollar | `BZD` | `EN` · `HI` · `AR` |
| 🇧🇯 | **Benin** | `BJ` | `BEN` | West African CFA Franc | `XOF` | `EN` · `HI` · `AR` |
| 🇧🇹 | **Bhutan** | `BT` | `BTN` | Bhutanese Ngultrum, Indian Rupee | `BTN`, `INR` | `EN` · `HI` · `AR` |
| 🇧🇴 | **Bolivia (Plurinational State of)** | `BO` | `BOL` | Bolivian Boliviano | `BOB` | `EN` · `HI` · `AR` |
| 🇧🇦 | **Bosnia and Herzegovina** | `BA` | `BIH` | Bosnia-Herzegovina Convertible Mark | `BAM` | `EN` · `HI` · `AR` |
| 🇧🇼 | **Botswana** | `BW` | `BWA` | Botswanan Pula | `BWP` | `EN` · `HI` · `AR` |
| 🇧🇳 | **Brunei Darussalam** | `BN` | `BRN` | Brunei Dollar | `BND` | `EN` · `HI` · `AR` |
| 🇧🇬 | **Bulgaria** | `BG` | `BGR` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇧🇫 | **Burkina Faso** | `BF` | `BFA` | West African CFA Franc | `XOF` | `EN` · `HI` · `AR` |
| 🇧🇮 | **Burundi** | `BI` | `BDI` | Burundian Franc | `BIF` | `EN` · `HI` · `AR` |
| 🇨🇻 | **Cabo Verde** | `CV` | `CPV` | Cape Verdean Escudo | `CVE` | `EN` · `HI` · `AR` |
| 🇰🇭 | **Cambodia** | `KH` | `KHM` | Cambodian Riel | `KHR` | `EN` · `HI` · `AR` |
| 🇨🇲 | **Cameroon** | `CM` | `CMR` | Central African CFA Franc | `XAF` | `EN` · `HI` · `AR` |
| 🇨🇫 | **Central African Republic** | `CF` | `CAF` | Central African CFA Franc | `XAF` | `EN` · `HI` · `AR` |
| 🇹🇩 | **Chad** | `TD` | `TCD` | Central African CFA Franc | `XAF` | `EN` · `HI` · `AR` |
| 🇨🇱 | **Chile** | `CL` | `CHL` | Chilean Peso | `CLP` | `EN` · `HI` · `AR` |
| 🇨🇴 | **Colombia** | `CO` | `COL` | Colombian Peso | `COP` | `EN` · `HI` · `AR` |
| 🇰🇲 | **Comoros** | `KM` | `COM` | Comorian Franc | `KMF` | `EN` · `HI` · `AR` |
| 🇨🇬 | **Congo** | `CG` | `COG` | Central African CFA Franc | `XAF` | `EN` · `HI` · `AR` |
| 🇨🇩 | **Congo (Democratic Republic of the)** | `CD` | `COD` | Congolese Franc | `CDF` | `EN` · `HI` · `AR` |
| 🇨🇷 | **Costa Rica** | `CR` | `CRI` | Costa Rican Colón | `CRC` | `EN` · `HI` · `AR` |
| 🇨🇮 | **Côte d'Ivoire** | `CI` | `CIV` | West African CFA Franc | `XOF` | `EN` · `HI` · `AR` |
| 🇭🇷 | **Croatia** | `HR` | `HRV` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇨🇺 | **Cuba** | `CU` | `CUB` | Cuban Peso | `CUP` | `EN` · `HI` · `AR` |
| 🇨🇾 | **Cyprus** | `CY` | `CYP` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇨🇿 | **Czechia** | `CZ` | `CZE` | Czech Koruna | `CZK` | `EN` · `HI` · `AR` |
| 🇩🇰 | **Denmark** | `DK` | `DNK` | Danish Krone | `DKK` | `EN` · `HI` · `AR` |
| 🇩🇯 | **Djibouti** | `DJ` | `DJI` | Djiboutian Franc | `DJF` | `EN` · `HI` · `AR` |
| 🇩🇲 | **Dominica** | `DM` | `DMA` | East Caribbean Dollar | `XCD` | `EN` · `HI` · `AR` |
| 🇩🇴 | **Dominican Republic** | `DO` | `DOM` | Dominican Peso | `DOP` | `EN` · `HI` · `AR` |
| 🇪🇨 | **Ecuador** | `EC` | `ECU` | US Dollar | `USD` | `EN` · `HI` · `AR` |
| 🇪🇬 | **Egypt** | `EG` | `EGY` | Egyptian Pound | `EGP` | `EN` · `HI` · `AR` |
| 🇸🇻 | **El Salvador** | `SV` | `SLV` | US Dollar | `USD` | `EN` · `HI` · `AR` |
| 🇬🇶 | **Equatorial Guinea** | `GQ` | `GNQ` | Central African CFA Franc | `XAF` | `EN` · `HI` · `AR` |
| 🇪🇷 | **Eritrea** | `ER` | `ERI` | Eritrean Nakfa | `ERN` | `EN` · `HI` · `AR` |
| 🇪🇪 | **Estonia** | `EE` | `EST` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇪🇹 | **Ethiopia** | `ET` | `ETH` | Ethiopian Birr | `ETB` | `EN` · `HI` · `AR` |
| 🇸🇿 | **Eswatini** | `SZ` | `SWZ` | Swazi Lilangeni | `SZL` | `EN` · `HI` · `AR` |
| 🇫🇯 | **Fiji** | `FJ` | `FJI` | Fijian Dollar | `FJD` | `EN` · `HI` · `AR` |
| 🇫🇮 | **Finland** | `FI` | `FIN` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇬🇦 | **Gabon** | `GA` | `GAB` | Central African CFA Franc | `XAF` | `EN` · `HI` · `AR` |
| 🇬🇲 | **Gambia** | `GM` | `GMB` | Gambian Dalasi | `GMD` | `EN` · `HI` · `AR` |
| 🇬🇪 | **Georgia** | `GE` | `GEO` | Georgian Lari | `GEL` | `EN` · `HI` · `AR` |
| 🇬🇭 | **Ghana** | `GH` | `GHA` | Ghanaian Cedi | `GHS` | `EN` · `HI` · `AR` |
| 🇬🇷 | **Greece** | `GR` | `GRC` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇬🇩 | **Grenada** | `GD` | `GRD` | East Caribbean Dollar | `XCD` | `EN` · `HI` · `AR` |
| 🇬🇹 | **Guatemala** | `GT` | `GTM` | Guatemalan Quetzal | `GTQ` | `EN` · `HI` · `AR` |
| 🇬🇳 | **Guinea** | `GN` | `GIN` | Guinean Franc | `GNF` | `EN` · `HI` · `AR` |
| 🇬🇼 | **Guinea-Bissau** | `GW` | `GNB` | West African CFA Franc | `XOF` | `EN` · `HI` · `AR` |
| 🇬🇾 | **Guyana** | `GY` | `GUY` | Guyanaese Dollar | `GYD` | `EN` · `HI` · `AR` |
| 🇭🇹 | **Haiti** | `HT` | `HTI` | Haitian Gourde, US Dollar | `HTG`, `USD` | `EN` · `HI` · `AR` |
| 🇻🇦 | **Holy See** | `VA` | `VAT` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇭🇳 | **Honduras** | `HN` | `HND` | Honduran Lempira | `HNL` | `EN` · `HI` · `AR` |
| 🇭🇰 | **Hong Kong** | `HK` | `HKG` | Hong Kong Dollar | `HKD` | `EN` · `HI` · `AR` |
| 🇭🇺 | **Hungary** | `HU` | `HUN` | Hungarian Forint | `HUF` | `EN` · `HI` · `AR` |
| 🇮🇸 | **Iceland** | `IS` | `ISL` | Icelandic Króna | `ISK` | `EN` · `HI` · `AR` |
| 🇮🇩 | **Indonesia** | `ID` | `IDN` | Indonesian Rupiah | `IDR` | `EN` · `HI` · `AR` |
| 🇮🇷 | **Iran (Islamic Republic of)** | `IR` | `IRN` | Iranian Rial | `IRR` | `EN` · `HI` · `AR` |
| 🇮🇶 | **Iraq** | `IQ` | `IRQ` | Iraqi Dinar | `IQD` | `EN` · `HI` · `AR` |
| 🇮🇪 | **Ireland** | `IE` | `IRL` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇮🇱 | **Israel** | `IL` | `ISR` | Israeli New Shekel | `ILS` | `EN` · `HI` · `AR` |
| 🇮🇹 | **Italy** | `IT` | `ITA` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇯🇲 | **Jamaica** | `JM` | `JAM` | Jamaican Dollar | `JMD` | `EN` · `HI` · `AR` |
| 🇯🇴 | **Jordan** | `JO` | `JOR` | Jordanian Dinar | `JOD` | `EN` · `HI` · `AR` |
| 🇰🇿 | **Kazakhstan** | `KZ` | `KAZ` | Kazakhstani Tenge | `KZT` | `EN` · `HI` · `AR` |
| 🇰🇪 | **Kenya** | `KE` | `KEN` | Kenyan Shilling | `KES` | `EN` · `HI` · `AR` |
| 🇰🇮 | **Kiribati** | `KI` | `KIR` | Australian Dollar | `AUD` | `EN` · `HI` · `AR` |
| 🇰🇵 | **Korea (Democratic People's Republic of)** | `KP` | `PRK` | North Korean Won | `KPW` | `EN` · `HI` · `AR` |
| 🇰🇷 | **Korea (Republic of)** | `KR` | `KOR` | South Korean Won | `KRW` | `EN` · `HI` · `AR` |
| 🇽🇰 | **Kosovo** | `XK` | `XKX` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇰🇼 | **Kuwait** | `KW` | `KWT` | Kuwaiti Dinar | `KWD` | `EN` · `HI` · `AR` |
| 🇰🇬 | **Kyrgyzstan** | `KG` | `KGZ` | Kyrgyz Som | `KGS` | `EN` · `HI` · `AR` |
| 🇱🇦 | **Lao People's Democratic Republic** | `LA` | `LAO` | Laotian Kip | `LAK` | `EN` · `HI` · `AR` |
| 🇱🇻 | **Latvia** | `LV` | `LVA` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇱🇧 | **Lebanon** | `LB` | `LBN` | Lebanese Pound | `LBP` | `EN` · `HI` · `AR` |
| 🇱🇸 | **Lesotho** | `LS` | `LSO` | South African Rand, Lesotho Loti | `ZAR`, `LSL` | `EN` · `HI` · `AR` |
| 🇱🇷 | **Liberia** | `LR` | `LBR` | Liberian Dollar | `LRD` | `EN` · `HI` · `AR` |
| 🇱🇾 | **Libya** | `LY` | `LBY` | Libyan Dinar | `LYD` | `EN` · `HI` · `AR` |
| 🇱🇮 | **Liechtenstein** | `LI` | `LIE` | Swiss Franc | `CHF` | `EN` · `HI` · `AR` |
| 🇱🇹 | **Lithuania** | `LT` | `LTU` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇱🇺 | **Luxembourg** | `LU` | `LUX` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇲🇴 | **Macao** | `MO` | `MAC` | Macanese Pataca | `MOP` | `EN` · `HI` · `AR` |
| 🇲🇰 | **North Macedonia** | `MK` | `MKD` | Macedonian Denar | `MKD` | `EN` · `HI` · `AR` |
| 🇲🇬 | **Madagascar** | `MG` | `MDG` | Malagasy Ariary | `MGA` | `EN` · `HI` · `AR` |
| 🇲🇼 | **Malawi** | `MW` | `MWI` | Malawian Kwacha | `MWK` | `EN` · `HI` · `AR` |
| 🇲🇾 | **Malaysia** | `MY` | `MYS` | Malaysian Ringgit | `MYR` | `EN` · `HI` · `AR` |
| 🇲🇻 | **Maldives** | `MV` | `MDV` | Maldivian Rufiyaa | `MVR` | `EN` · `HI` · `AR` |
| 🇲🇱 | **Mali** | `ML` | `MLI` | West African CFA Franc | `XOF` | `EN` · `HI` · `AR` |
| 🇲🇹 | **Malta** | `MT` | `MLT` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇲🇭 | **Marshall Islands** | `MH` | `MHL` | US Dollar | `USD` | `EN` · `HI` · `AR` |
| 🇲🇷 | **Mauritania** | `MR` | `MRT` | Mauritanian Ouguiya | `MRU` | `EN` · `HI` · `AR` |
| 🇲🇺 | **Mauritius** | `MU` | `MUS` | Mauritian Rupee | `MUR` | `EN` · `HI` · `AR` |
| 🇲🇽 | **Mexico** | `MX` | `MEX` | Mexican Peso | `MXN` | `EN` · `HI` · `AR` |
| 🇫🇲 | **Micronesia (Federated States of)** | `FM` | `FSM` | US Dollar | `USD` | `EN` · `HI` · `AR` |
| 🇲🇩 | **Moldova (Republic of)** | `MD` | `MDA` | Moldovan Leu | `MDL` | `EN` · `HI` · `AR` |
| 🇲🇨 | **Monaco** | `MC` | `MCO` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇲🇳 | **Mongolia** | `MN` | `MNG` | Mongolian Tugrik | `MNT` | `EN` · `HI` · `AR` |
| 🇲🇪 | **Montenegro** | `ME` | `MNE` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇲🇦 | **Morocco** | `MA` | `MAR` | Moroccan Dirham | `MAD` | `EN` · `HI` · `AR` |
| 🇲🇿 | **Mozambique** | `MZ` | `MOZ` | Mozambican Metical | `MZN` | `EN` · `HI` · `AR` |
| 🇲🇲 | **Myanmar** | `MM` | `MMR` | Myanmar Kyat | `MMK` | `EN` · `HI` · `AR` |
| 🇳🇦 | **Namibia** | `NA` | `NAM` | Namibian Dollar, South African Rand | `NAD`, `ZAR` | `EN` · `HI` · `AR` |
| 🇳🇷 | **Naoero** | `NR` | `NRU` | Australian Dollar | `AUD` | `EN` · `HI` · `AR` |
| 🇳🇵 | **Nepal** | `NP` | `NPL` | Nepalese Rupee | `NPR` | `EN` · `HI` · `AR` |
| 🇳🇱 | **Netherlands** | `NL` | `NLD` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇳🇮 | **Nicaragua** | `NI` | `NIC` | Nicaraguan Córdoba | `NIO` | `EN` · `HI` · `AR` |
| 🇳🇪 | **Niger** | `NE` | `NER` | West African CFA Franc | `XOF` | `EN` · `HI` · `AR` |
| 🇳🇬 | **Nigeria** | `NG` | `NGA` | Nigerian Naira | `NGN` | `EN` · `HI` · `AR` |
| 🇳🇴 | **Norway** | `NO` | `NOR` | Norwegian Krone | `NOK` | `EN` · `HI` · `AR` |
| 🇴🇲 | **Oman** | `OM` | `OMN` | Omani Rial | `OMR` | `EN` · `HI` · `AR` |
| 🇵🇰 | **Pakistan** | `PK` | `PAK` | Pakistani Rupee | `PKR` | `EN` · `HI` · `AR` |
| 🇵🇼 | **Palau** | `PW` | `PLW` | US Dollar | `USD` | `EN` · `HI` · `AR` |
| 🇵🇸 | **Palestine, State of** | `PS` | `PSE` | Israeli New Shekel, Jordanian Dinar | `ILS`, `JOD` | `EN` · `HI` · `AR` |
| 🇵🇦 | **Panama** | `PA` | `PAN` | Panamanian Balboa, US Dollar | `PAB`, `USD` | `EN` · `HI` · `AR` |
| 🇵🇬 | **Papua New Guinea** | `PG` | `PNG` | Papua New Guinean Kina | `PGK` | `EN` · `HI` · `AR` |
| 🇵🇾 | **Paraguay** | `PY` | `PRY` | Paraguayan Guarani | `PYG` | `EN` · `HI` · `AR` |
| 🇵🇪 | **Peru** | `PE` | `PER` | Peruvian Sol | `PEN` | `EN` · `HI` · `AR` |
| 🇵🇭 | **Philippines** | `PH` | `PHL` | Philippine Peso | `PHP` | `EN` · `HI` · `AR` |
| 🇵🇱 | **Poland** | `PL` | `POL` | Polish Zloty | `PLN` | `EN` · `HI` · `AR` |
| 🇵🇹 | **Portugal** | `PT` | `PRT` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇶🇦 | **Qatar** | `QA` | `QAT` | Qatari Riyal | `QAR` | `EN` · `HI` · `AR` |
| 🇷🇴 | **Romania** | `RO` | `ROU` | Romanian Leu | `RON` | `EN` · `HI` · `AR` |
| 🇷🇺 | **Russian Federation** | `RU` | `RUS` | Russian Ruble | `RUB` | `EN` · `HI` · `AR` |
| 🇷🇼 | **Rwanda** | `RW` | `RWA` | Rwandan Franc | `RWF` | `EN` · `HI` · `AR` |
| 🇰🇳 | **Saint Kitts and Nevis** | `KN` | `KNA` | East Caribbean Dollar | `XCD` | `EN` · `HI` · `AR` |
| 🇱🇨 | **Saint Lucia** | `LC` | `LCA` | East Caribbean Dollar | `XCD` | `EN` · `HI` · `AR` |
| 🇻🇨 | **Saint Vincent and the Grenadines** | `VC` | `VCT` | East Caribbean Dollar | `XCD` | `EN` · `HI` · `AR` |
| 🇼🇸 | **Samoa** | `WS` | `WSM` | Samoan Tala | `WST` | `EN` · `HI` · `AR` |
| 🇸🇲 | **San Marino** | `SM` | `SMR` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇸🇹 | **Sao Tome and Principe** | `ST` | `STP` | São Tomé & Príncipe Dobra | `STN` | `EN` · `HI` · `AR` |
| 🇸🇳 | **Senegal** | `SN` | `SEN` | West African CFA Franc | `XOF` | `EN` · `HI` · `AR` |
| 🇷🇸 | **Serbia** | `RS` | `SRB` | Serbian Dinar | `RSD` | `EN` · `HI` · `AR` |
| 🇸🇨 | **Seychelles** | `SC` | `SYC` | Seychellois Rupee | `SCR` | `EN` · `HI` · `AR` |
| 🇸🇱 | **Sierra Leone** | `SL` | `SLE` | Sierra Leonean Leone | `SLE` | `EN` · `HI` · `AR` |
| 🇸🇰 | **Slovakia** | `SK` | `SVK` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇸🇮 | **Slovenia** | `SI` | `SVN` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇸🇧 | **Solomon Islands** | `SB` | `SLB` | Solomon Islands Dollar | `SBD` | `EN` · `HI` · `AR` |
| 🇸🇴 | **Somalia** | `SO` | `SOM` | Somali Shilling | `SOS` | `EN` · `HI` · `AR` |
| 🇸🇸 | **South Sudan** | `SS` | `SSD` | South Sudanese Pound | `SSP` | `EN` · `HI` · `AR` |
| 🇪🇸 | **Spain** | `ES` | `ESP` | Euro | `EUR` | `EN` · `HI` · `AR` |
| 🇱🇰 | **Sri Lanka** | `LK` | `LKA` | Sri Lankan Rupee | `LKR` | `EN` · `HI` · `AR` |
| 🇸🇩 | **Sudan** | `SD` | `SDN` | Sudanese Pound | `SDG` | `EN` · `HI` · `AR` |
| 🇸🇷 | **Suriname** | `SR` | `SUR` | Surinamese Dollar | `SRD` | `EN` · `HI` · `AR` |
| 🇸🇪 | **Sweden** | `SE` | `SWE` | Swedish Krona | `SEK` | `EN` · `HI` · `AR` |
| 🇨🇭 | **Switzerland** | `CH` | `CHE` | Swiss Franc | `CHF` | `EN` · `HI` · `AR` |
| 🇸🇾 | **Syrian Arab Republic** | `SY` | `SYR` | Syrian Pound | `SYP` | `EN` · `HI` · `AR` |
| 🇹🇼 | **Taiwan (Province of China)** | `TW` | `TWN` | New Taiwan Dollar | `TWD` | `EN` · `HI` · `AR` |
| 🇹🇯 | **Tajikistan** | `TJ` | `TJK` | Tajikistani Somoni | `TJS` | `EN` · `HI` · `AR` |
| 🇹🇿 | **Tanzania, United Republic of** | `TZ` | `TZA` | Tanzanian Shilling | `TZS` | `EN` · `HI` · `AR` |
| 🇹🇭 | **Thailand** | `TH` | `THA` | Thai Baht | `THB` | `EN` · `HI` · `AR` |
| 🇹🇱 | **Timor-Leste** | `TL` | `TLS` | US Dollar | `USD` | `EN` · `HI` · `AR` |
| 🇹🇬 | **Togo** | `TG` | `TGO` | West African CFA Franc | `XOF` | `EN` · `HI` · `AR` |
| 🇹🇴 | **Tonga** | `TO` | `TON` | Tongan Paʻanga | `TOP` | `EN` · `HI` · `AR` |
| 🇹🇹 | **Trinidad and Tobago** | `TT` | `TTO` | Trinidad & Tobago Dollar | `TTD` | `EN` · `HI` · `AR` |
| 🇹🇳 | **Tunisia** | `TN` | `TUN` | Tunisian Dinar | `TND` | `EN` · `HI` · `AR` |
| 🇹🇷 | **Türkiye** | `TR` | `TUR` | Turkish Lira | `TRY` | `EN` · `HI` · `AR` |
| 🇹🇲 | **Turkmenistan** | `TM` | `TKM` | Turkmenistani Manat | `TMT` | `EN` · `HI` · `AR` |
| 🇹🇻 | **Tuvalu** | `TV` | `TUV` | Australian Dollar | `AUD` | `EN` · `HI` · `AR` |
| 🇺🇬 | **Uganda** | `UG` | `UGA` | Ugandan Shilling | `UGX` | `EN` · `HI` · `AR` |
| 🇺🇦 | **Ukraine** | `UA` | `UKR` | Ukrainian Hryvnia | `UAH` | `EN` · `HI` · `AR` |
| 🇺🇾 | **Uruguay** | `UY` | `URY` | Uruguayan Peso | `UYU` | `EN` · `HI` · `AR` |
| 🇺🇿 | **Uzbekistan** | `UZ` | `UZB` | Uzbekistani Som | `UZS` | `EN` · `HI` · `AR` |
| 🇻🇺 | **Vanuatu** | `VU` | `VUT` | Vanuatu Vatu | `VUV` | `EN` · `HI` · `AR` |
| 🇻🇪 | **Venezuela (Bolivarian Republic of)** | `VE` | `VEN` | Venezuelan Bolívar | `VES` | `EN` · `HI` · `AR` |
| 🇻🇳 | **Viet Nam** | `VN` | `VNM` | Vietnamese Dong | `VND` | `EN` · `HI` · `AR` |
| 🇪🇭 | **Western Sahara** | `EH` | `ESH` | Moroccan Dirham | `MAD` | `EN` · `HI` · `AR` |
| 🇾🇪 | **Yemen** | `YE` | `YEM` | Yemeni Rial | `YER` | `EN` · `HI` · `AR` |
| 🇿🇲 | **Zambia** | `ZM` | `ZMB` | Zambian Kwacha | `ZMW` | `EN` · `HI` · `AR` |
| 🇿🇼 | **Zimbabwe** | `ZW` | `ZWE` | Zimbabwean Gold, US Dollar | `ZWG`, `USD` | `EN` · `HI` · `AR` |

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
