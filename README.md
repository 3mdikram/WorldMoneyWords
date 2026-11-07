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
> **Supported languages:** Every listed country can generate amount wording in English (`en`), Hindi (`hi`), or Arabic (`ar`) through the `language` conversion option.

|  | Country | **Alpha-2** | **Alpha-3** | Currency | ISO currency | Supported languages |
|:---:|---|:---:|:---:|---|:---:|---|
| 🇮🇳 | **India** | `IN` | `IND` | Indian Rupee | `INR` | English · Hindi · Arabic |
| 🇺🇸 | **United States** | `US` | `USA` | US Dollar | `USD` | English · Hindi · Arabic |
| 🇬🇧 | **United Kingdom** | `GB` | `GBR` | Pound Sterling | `GBP` | English · Hindi · Arabic |
| 🇦🇪 | **United Arab Emirates** | `AE` | `ARE` | UAE Dirham | `AED` | English · Hindi · Arabic |
| 🇨🇦 | **Canada** | `CA` | `CAN` | Canadian Dollar | `CAD` | English · Hindi · Arabic |
| 🇦🇺 | **Australia** | `AU` | `AUS` | Australian Dollar | `AUD` | English · Hindi · Arabic |
| 🇩🇪 | **Germany** | `DE` | `DEU` | Euro | `EUR` | English · Hindi · Arabic |
| 🇫🇷 | **France** | `FR` | `FRA` | Euro | `EUR` | English · Hindi · Arabic |
| 🇯🇵 | **Japan** | `JP` | `JPN` | Japanese Yen | `JPY` | English · Hindi · Arabic |
| 🇨🇳 | **China** | `CN` | `CHN` | Yuan | `CNY` | English · Hindi · Arabic |
| 🇸🇬 | **Singapore** | `SG` | `SGP` | Singapore Dollar | `SGD` | English · Hindi · Arabic |
| 🇸🇦 | **Saudi Arabia** | `SA` | `SAU` | Saudi Riyal | `SAR` | English · Hindi · Arabic |
| 🇧🇷 | **Brazil** | `BR` | `BRA` | Brazilian Real | `BRL` | English · Hindi · Arabic |
| 🇿🇦 | **South Africa** | `ZA` | `ZAF` | Rand | `ZAR` | English · Hindi · Arabic |
| 🇳🇿 | **New Zealand** | `NZ` | `NZL` | New Zealand Dollar | `NZD` | English · Hindi · Arabic |

<details>
<summary><strong>🌐 Browse all other 185 supported countries</strong></summary>

<br>

|  | Country | **Alpha-2** | **Alpha-3** | Currency | ISO currency | Supported languages |
|:---:|---|:---:|:---:|---|:---:|---|
| 🇦🇫 | **Afghanistan** | `AF` | `AFG` | Afghan Afghani | `AFN` | English · Hindi · Arabic |
| 🇦🇱 | **Albania** | `AL` | `ALB` | Albanian Lek | `ALL` | English · Hindi · Arabic |
| 🇩🇿 | **Algeria** | `DZ` | `DZA` | Algerian Dinar | `DZD` | English · Hindi · Arabic |
| 🇦🇩 | **Andorra** | `AD` | `AND` | Euro | `EUR` | English · Hindi · Arabic |
| 🇦🇴 | **Angola** | `AO` | `AGO` | Angolan Kwanza | `AOA` | English · Hindi · Arabic |
| 🇦🇬 | **Antigua and Barbuda** | `AG` | `ATG` | East Caribbean Dollar | `XCD` | English · Hindi · Arabic |
| 🇦🇷 | **Argentina** | `AR` | `ARG` | Argentine Peso | `ARS` | English · Hindi · Arabic |
| 🇦🇲 | **Armenia** | `AM` | `ARM` | Armenian Dram | `AMD` | English · Hindi · Arabic |
| 🇦🇹 | **Austria** | `AT` | `AUT` | Euro | `EUR` | English · Hindi · Arabic |
| 🇦🇿 | **Azerbaijan** | `AZ` | `AZE` | Azerbaijani Manat | `AZN` | English · Hindi · Arabic |
| 🇧🇸 | **Bahamas** | `BS` | `BHS` | Bahamian Dollar | `BSD` | English · Hindi · Arabic |
| 🇧🇭 | **Bahrain** | `BH` | `BHR` | Bahraini Dinar | `BHD` | English · Hindi · Arabic |
| 🇧🇩 | **Bangladesh** | `BD` | `BGD` | Bangladeshi Taka | `BDT` | English · Hindi · Arabic |
| 🇧🇧 | **Barbados** | `BB` | `BRB` | Barbadian Dollar | `BBD` | English · Hindi · Arabic |
| 🇧🇾 | **Belarus** | `BY` | `BLR` | Belarusian Ruble | `BYN` | English · Hindi · Arabic |
| 🇧🇪 | **Belgium** | `BE` | `BEL` | Euro | `EUR` | English · Hindi · Arabic |
| 🇧🇿 | **Belize** | `BZ` | `BLZ` | Belize Dollar | `BZD` | English · Hindi · Arabic |
| 🇧🇯 | **Benin** | `BJ` | `BEN` | West African CFA Franc | `XOF` | English · Hindi · Arabic |
| 🇧🇹 | **Bhutan** | `BT` | `BTN` | Bhutanese Ngultrum, Indian Rupee | `BTN`, `INR` | English · Hindi · Arabic |
| 🇧🇴 | **Bolivia (Plurinational State of)** | `BO` | `BOL` | Bolivian Boliviano | `BOB` | English · Hindi · Arabic |
| 🇧🇦 | **Bosnia and Herzegovina** | `BA` | `BIH` | Bosnia-Herzegovina Convertible Mark | `BAM` | English · Hindi · Arabic |
| 🇧🇼 | **Botswana** | `BW` | `BWA` | Botswanan Pula | `BWP` | English · Hindi · Arabic |
| 🇧🇳 | **Brunei Darussalam** | `BN` | `BRN` | Brunei Dollar | `BND` | English · Hindi · Arabic |
| 🇧🇬 | **Bulgaria** | `BG` | `BGR` | Euro | `EUR` | English · Hindi · Arabic |
| 🇧🇫 | **Burkina Faso** | `BF` | `BFA` | West African CFA Franc | `XOF` | English · Hindi · Arabic |
| 🇧🇮 | **Burundi** | `BI` | `BDI` | Burundian Franc | `BIF` | English · Hindi · Arabic |
| 🇨🇻 | **Cabo Verde** | `CV` | `CPV` | Cape Verdean Escudo | `CVE` | English · Hindi · Arabic |
| 🇰🇭 | **Cambodia** | `KH` | `KHM` | Cambodian Riel | `KHR` | English · Hindi · Arabic |
| 🇨🇲 | **Cameroon** | `CM` | `CMR` | Central African CFA Franc | `XAF` | English · Hindi · Arabic |
| 🇨🇫 | **Central African Republic** | `CF` | `CAF` | Central African CFA Franc | `XAF` | English · Hindi · Arabic |
| 🇹🇩 | **Chad** | `TD` | `TCD` | Central African CFA Franc | `XAF` | English · Hindi · Arabic |
| 🇨🇱 | **Chile** | `CL` | `CHL` | Chilean Peso | `CLP` | English · Hindi · Arabic |
| 🇨🇴 | **Colombia** | `CO` | `COL` | Colombian Peso | `COP` | English · Hindi · Arabic |
| 🇰🇲 | **Comoros** | `KM` | `COM` | Comorian Franc | `KMF` | English · Hindi · Arabic |
| 🇨🇬 | **Congo** | `CG` | `COG` | Central African CFA Franc | `XAF` | English · Hindi · Arabic |
| 🇨🇩 | **Congo (Democratic Republic of the)** | `CD` | `COD` | Congolese Franc | `CDF` | English · Hindi · Arabic |
| 🇨🇷 | **Costa Rica** | `CR` | `CRI` | Costa Rican Colón | `CRC` | English · Hindi · Arabic |
| 🇨🇮 | **Côte d'Ivoire** | `CI` | `CIV` | West African CFA Franc | `XOF` | English · Hindi · Arabic |
| 🇭🇷 | **Croatia** | `HR` | `HRV` | Euro | `EUR` | English · Hindi · Arabic |
| 🇨🇺 | **Cuba** | `CU` | `CUB` | Cuban Peso | `CUP` | English · Hindi · Arabic |
| 🇨🇾 | **Cyprus** | `CY` | `CYP` | Euro | `EUR` | English · Hindi · Arabic |
| 🇨🇿 | **Czechia** | `CZ` | `CZE` | Czech Koruna | `CZK` | English · Hindi · Arabic |
| 🇩🇰 | **Denmark** | `DK` | `DNK` | Danish Krone | `DKK` | English · Hindi · Arabic |
| 🇩🇯 | **Djibouti** | `DJ` | `DJI` | Djiboutian Franc | `DJF` | English · Hindi · Arabic |
| 🇩🇲 | **Dominica** | `DM` | `DMA` | East Caribbean Dollar | `XCD` | English · Hindi · Arabic |
| 🇩🇴 | **Dominican Republic** | `DO` | `DOM` | Dominican Peso | `DOP` | English · Hindi · Arabic |
| 🇪🇨 | **Ecuador** | `EC` | `ECU` | US Dollar | `USD` | English · Hindi · Arabic |
| 🇪🇬 | **Egypt** | `EG` | `EGY` | Egyptian Pound | `EGP` | English · Hindi · Arabic |
| 🇸🇻 | **El Salvador** | `SV` | `SLV` | US Dollar | `USD` | English · Hindi · Arabic |
| 🇬🇶 | **Equatorial Guinea** | `GQ` | `GNQ` | Central African CFA Franc | `XAF` | English · Hindi · Arabic |
| 🇪🇷 | **Eritrea** | `ER` | `ERI` | Eritrean Nakfa | `ERN` | English · Hindi · Arabic |
| 🇪🇪 | **Estonia** | `EE` | `EST` | Euro | `EUR` | English · Hindi · Arabic |
| 🇪🇹 | **Ethiopia** | `ET` | `ETH` | Ethiopian Birr | `ETB` | English · Hindi · Arabic |
| 🇸🇿 | **Eswatini** | `SZ` | `SWZ` | Swazi Lilangeni | `SZL` | English · Hindi · Arabic |
| 🇫🇯 | **Fiji** | `FJ` | `FJI` | Fijian Dollar | `FJD` | English · Hindi · Arabic |
| 🇫🇮 | **Finland** | `FI` | `FIN` | Euro | `EUR` | English · Hindi · Arabic |
| 🇬🇦 | **Gabon** | `GA` | `GAB` | Central African CFA Franc | `XAF` | English · Hindi · Arabic |
| 🇬🇲 | **Gambia** | `GM` | `GMB` | Gambian Dalasi | `GMD` | English · Hindi · Arabic |
| 🇬🇪 | **Georgia** | `GE` | `GEO` | Georgian Lari | `GEL` | English · Hindi · Arabic |
| 🇬🇭 | **Ghana** | `GH` | `GHA` | Ghanaian Cedi | `GHS` | English · Hindi · Arabic |
| 🇬🇷 | **Greece** | `GR` | `GRC` | Euro | `EUR` | English · Hindi · Arabic |
| 🇬🇩 | **Grenada** | `GD` | `GRD` | East Caribbean Dollar | `XCD` | English · Hindi · Arabic |
| 🇬🇹 | **Guatemala** | `GT` | `GTM` | Guatemalan Quetzal | `GTQ` | English · Hindi · Arabic |
| 🇬🇳 | **Guinea** | `GN` | `GIN` | Guinean Franc | `GNF` | English · Hindi · Arabic |
| 🇬🇼 | **Guinea-Bissau** | `GW` | `GNB` | West African CFA Franc | `XOF` | English · Hindi · Arabic |
| 🇬🇾 | **Guyana** | `GY` | `GUY` | Guyanaese Dollar | `GYD` | English · Hindi · Arabic |
| 🇭🇹 | **Haiti** | `HT` | `HTI` | Haitian Gourde, US Dollar | `HTG`, `USD` | English · Hindi · Arabic |
| 🇻🇦 | **Holy See** | `VA` | `VAT` | Euro | `EUR` | English · Hindi · Arabic |
| 🇭🇳 | **Honduras** | `HN` | `HND` | Honduran Lempira | `HNL` | English · Hindi · Arabic |
| 🇭🇰 | **Hong Kong** | `HK` | `HKG` | Hong Kong Dollar | `HKD` | English · Hindi · Arabic |
| 🇭🇺 | **Hungary** | `HU` | `HUN` | Hungarian Forint | `HUF` | English · Hindi · Arabic |
| 🇮🇸 | **Iceland** | `IS` | `ISL` | Icelandic Króna | `ISK` | English · Hindi · Arabic |
| 🇮🇩 | **Indonesia** | `ID` | `IDN` | Indonesian Rupiah | `IDR` | English · Hindi · Arabic |
| 🇮🇷 | **Iran (Islamic Republic of)** | `IR` | `IRN` | Iranian Rial | `IRR` | English · Hindi · Arabic |
| 🇮🇶 | **Iraq** | `IQ` | `IRQ` | Iraqi Dinar | `IQD` | English · Hindi · Arabic |
| 🇮🇪 | **Ireland** | `IE` | `IRL` | Euro | `EUR` | English · Hindi · Arabic |
| 🇮🇱 | **Israel** | `IL` | `ISR` | Israeli New Shekel | `ILS` | English · Hindi · Arabic |
| 🇮🇹 | **Italy** | `IT` | `ITA` | Euro | `EUR` | English · Hindi · Arabic |
| 🇯🇲 | **Jamaica** | `JM` | `JAM` | Jamaican Dollar | `JMD` | English · Hindi · Arabic |
| 🇯🇴 | **Jordan** | `JO` | `JOR` | Jordanian Dinar | `JOD` | English · Hindi · Arabic |
| 🇰🇿 | **Kazakhstan** | `KZ` | `KAZ` | Kazakhstani Tenge | `KZT` | English · Hindi · Arabic |
| 🇰🇪 | **Kenya** | `KE` | `KEN` | Kenyan Shilling | `KES` | English · Hindi · Arabic |
| 🇰🇮 | **Kiribati** | `KI` | `KIR` | Australian Dollar | `AUD` | English · Hindi · Arabic |
| 🇰🇵 | **Korea (Democratic People's Republic of)** | `KP` | `PRK` | North Korean Won | `KPW` | English · Hindi · Arabic |
| 🇰🇷 | **Korea (Republic of)** | `KR` | `KOR` | South Korean Won | `KRW` | English · Hindi · Arabic |
| 🇽🇰 | **Kosovo** | `XK` | `XKX` | Euro | `EUR` | English · Hindi · Arabic |
| 🇰🇼 | **Kuwait** | `KW` | `KWT` | Kuwaiti Dinar | `KWD` | English · Hindi · Arabic |
| 🇰🇬 | **Kyrgyzstan** | `KG` | `KGZ` | Kyrgyz Som | `KGS` | English · Hindi · Arabic |
| 🇱🇦 | **Lao People's Democratic Republic** | `LA` | `LAO` | Laotian Kip | `LAK` | English · Hindi · Arabic |
| 🇱🇻 | **Latvia** | `LV` | `LVA` | Euro | `EUR` | English · Hindi · Arabic |
| 🇱🇧 | **Lebanon** | `LB` | `LBN` | Lebanese Pound | `LBP` | English · Hindi · Arabic |
| 🇱🇸 | **Lesotho** | `LS` | `LSO` | South African Rand, Lesotho Loti | `ZAR`, `LSL` | English · Hindi · Arabic |
| 🇱🇷 | **Liberia** | `LR` | `LBR` | Liberian Dollar | `LRD` | English · Hindi · Arabic |
| 🇱🇾 | **Libya** | `LY` | `LBY` | Libyan Dinar | `LYD` | English · Hindi · Arabic |
| 🇱🇮 | **Liechtenstein** | `LI` | `LIE` | Swiss Franc | `CHF` | English · Hindi · Arabic |
| 🇱🇹 | **Lithuania** | `LT` | `LTU` | Euro | `EUR` | English · Hindi · Arabic |
| 🇱🇺 | **Luxembourg** | `LU` | `LUX` | Euro | `EUR` | English · Hindi · Arabic |
| 🇲🇴 | **Macao** | `MO` | `MAC` | Macanese Pataca | `MOP` | English · Hindi · Arabic |
| 🇲🇰 | **North Macedonia** | `MK` | `MKD` | Macedonian Denar | `MKD` | English · Hindi · Arabic |
| 🇲🇬 | **Madagascar** | `MG` | `MDG` | Malagasy Ariary | `MGA` | English · Hindi · Arabic |
| 🇲🇼 | **Malawi** | `MW` | `MWI` | Malawian Kwacha | `MWK` | English · Hindi · Arabic |
| 🇲🇾 | **Malaysia** | `MY` | `MYS` | Malaysian Ringgit | `MYR` | English · Hindi · Arabic |
| 🇲🇻 | **Maldives** | `MV` | `MDV` | Maldivian Rufiyaa | `MVR` | English · Hindi · Arabic |
| 🇲🇱 | **Mali** | `ML` | `MLI` | West African CFA Franc | `XOF` | English · Hindi · Arabic |
| 🇲🇹 | **Malta** | `MT` | `MLT` | Euro | `EUR` | English · Hindi · Arabic |
| 🇲🇭 | **Marshall Islands** | `MH` | `MHL` | US Dollar | `USD` | English · Hindi · Arabic |
| 🇲🇷 | **Mauritania** | `MR` | `MRT` | Mauritanian Ouguiya | `MRU` | English · Hindi · Arabic |
| 🇲🇺 | **Mauritius** | `MU` | `MUS` | Mauritian Rupee | `MUR` | English · Hindi · Arabic |
| 🇲🇽 | **Mexico** | `MX` | `MEX` | Mexican Peso | `MXN` | English · Hindi · Arabic |
| 🇫🇲 | **Micronesia (Federated States of)** | `FM` | `FSM` | US Dollar | `USD` | English · Hindi · Arabic |
| 🇲🇩 | **Moldova (Republic of)** | `MD` | `MDA` | Moldovan Leu | `MDL` | English · Hindi · Arabic |
| 🇲🇨 | **Monaco** | `MC` | `MCO` | Euro | `EUR` | English · Hindi · Arabic |
| 🇲🇳 | **Mongolia** | `MN` | `MNG` | Mongolian Tugrik | `MNT` | English · Hindi · Arabic |
| 🇲🇪 | **Montenegro** | `ME` | `MNE` | Euro | `EUR` | English · Hindi · Arabic |
| 🇲🇦 | **Morocco** | `MA` | `MAR` | Moroccan Dirham | `MAD` | English · Hindi · Arabic |
| 🇲🇿 | **Mozambique** | `MZ` | `MOZ` | Mozambican Metical | `MZN` | English · Hindi · Arabic |
| 🇲🇲 | **Myanmar** | `MM` | `MMR` | Myanmar Kyat | `MMK` | English · Hindi · Arabic |
| 🇳🇦 | **Namibia** | `NA` | `NAM` | Namibian Dollar, South African Rand | `NAD`, `ZAR` | English · Hindi · Arabic |
| 🇳🇷 | **Naoero** | `NR` | `NRU` | Australian Dollar | `AUD` | English · Hindi · Arabic |
| 🇳🇵 | **Nepal** | `NP` | `NPL` | Nepalese Rupee | `NPR` | English · Hindi · Arabic |
| 🇳🇱 | **Netherlands** | `NL` | `NLD` | Euro | `EUR` | English · Hindi · Arabic |
| 🇳🇮 | **Nicaragua** | `NI` | `NIC` | Nicaraguan Córdoba | `NIO` | English · Hindi · Arabic |
| 🇳🇪 | **Niger** | `NE` | `NER` | West African CFA Franc | `XOF` | English · Hindi · Arabic |
| 🇳🇬 | **Nigeria** | `NG` | `NGA` | Nigerian Naira | `NGN` | English · Hindi · Arabic |
| 🇳🇴 | **Norway** | `NO` | `NOR` | Norwegian Krone | `NOK` | English · Hindi · Arabic |
| 🇴🇲 | **Oman** | `OM` | `OMN` | Omani Rial | `OMR` | English · Hindi · Arabic |
| 🇵🇰 | **Pakistan** | `PK` | `PAK` | Pakistani Rupee | `PKR` | English · Hindi · Arabic |
| 🇵🇼 | **Palau** | `PW` | `PLW` | US Dollar | `USD` | English · Hindi · Arabic |
| 🇵🇸 | **Palestine, State of** | `PS` | `PSE` | Israeli New Shekel, Jordanian Dinar | `ILS`, `JOD` | English · Hindi · Arabic |
| 🇵🇦 | **Panama** | `PA` | `PAN` | Panamanian Balboa, US Dollar | `PAB`, `USD` | English · Hindi · Arabic |
| 🇵🇬 | **Papua New Guinea** | `PG` | `PNG` | Papua New Guinean Kina | `PGK` | English · Hindi · Arabic |
| 🇵🇾 | **Paraguay** | `PY` | `PRY` | Paraguayan Guarani | `PYG` | English · Hindi · Arabic |
| 🇵🇪 | **Peru** | `PE` | `PER` | Peruvian Sol | `PEN` | English · Hindi · Arabic |
| 🇵🇭 | **Philippines** | `PH` | `PHL` | Philippine Peso | `PHP` | English · Hindi · Arabic |
| 🇵🇱 | **Poland** | `PL` | `POL` | Polish Zloty | `PLN` | English · Hindi · Arabic |
| 🇵🇹 | **Portugal** | `PT` | `PRT` | Euro | `EUR` | English · Hindi · Arabic |
| 🇶🇦 | **Qatar** | `QA` | `QAT` | Qatari Riyal | `QAR` | English · Hindi · Arabic |
| 🇷🇴 | **Romania** | `RO` | `ROU` | Romanian Leu | `RON` | English · Hindi · Arabic |
| 🇷🇺 | **Russian Federation** | `RU` | `RUS` | Russian Ruble | `RUB` | English · Hindi · Arabic |
| 🇷🇼 | **Rwanda** | `RW` | `RWA` | Rwandan Franc | `RWF` | English · Hindi · Arabic |
| 🇰🇳 | **Saint Kitts and Nevis** | `KN` | `KNA` | East Caribbean Dollar | `XCD` | English · Hindi · Arabic |
| 🇱🇨 | **Saint Lucia** | `LC` | `LCA` | East Caribbean Dollar | `XCD` | English · Hindi · Arabic |
| 🇻🇨 | **Saint Vincent and the Grenadines** | `VC` | `VCT` | East Caribbean Dollar | `XCD` | English · Hindi · Arabic |
| 🇼🇸 | **Samoa** | `WS` | `WSM` | Samoan Tala | `WST` | English · Hindi · Arabic |
| 🇸🇲 | **San Marino** | `SM` | `SMR` | Euro | `EUR` | English · Hindi · Arabic |
| 🇸🇹 | **Sao Tome and Principe** | `ST` | `STP` | São Tomé & Príncipe Dobra | `STN` | English · Hindi · Arabic |
| 🇸🇳 | **Senegal** | `SN` | `SEN` | West African CFA Franc | `XOF` | English · Hindi · Arabic |
| 🇷🇸 | **Serbia** | `RS` | `SRB` | Serbian Dinar | `RSD` | English · Hindi · Arabic |
| 🇸🇨 | **Seychelles** | `SC` | `SYC` | Seychellois Rupee | `SCR` | English · Hindi · Arabic |
| 🇸🇱 | **Sierra Leone** | `SL` | `SLE` | Sierra Leonean Leone | `SLE` | English · Hindi · Arabic |
| 🇸🇰 | **Slovakia** | `SK` | `SVK` | Euro | `EUR` | English · Hindi · Arabic |
| 🇸🇮 | **Slovenia** | `SI` | `SVN` | Euro | `EUR` | English · Hindi · Arabic |
| 🇸🇧 | **Solomon Islands** | `SB` | `SLB` | Solomon Islands Dollar | `SBD` | English · Hindi · Arabic |
| 🇸🇴 | **Somalia** | `SO` | `SOM` | Somali Shilling | `SOS` | English · Hindi · Arabic |
| 🇸🇸 | **South Sudan** | `SS` | `SSD` | South Sudanese Pound | `SSP` | English · Hindi · Arabic |
| 🇪🇸 | **Spain** | `ES` | `ESP` | Euro | `EUR` | English · Hindi · Arabic |
| 🇱🇰 | **Sri Lanka** | `LK` | `LKA` | Sri Lankan Rupee | `LKR` | English · Hindi · Arabic |
| 🇸🇩 | **Sudan** | `SD` | `SDN` | Sudanese Pound | `SDG` | English · Hindi · Arabic |
| 🇸🇷 | **Suriname** | `SR` | `SUR` | Surinamese Dollar | `SRD` | English · Hindi · Arabic |
| 🇸🇪 | **Sweden** | `SE` | `SWE` | Swedish Krona | `SEK` | English · Hindi · Arabic |
| 🇨🇭 | **Switzerland** | `CH` | `CHE` | Swiss Franc | `CHF` | English · Hindi · Arabic |
| 🇸🇾 | **Syrian Arab Republic** | `SY` | `SYR` | Syrian Pound | `SYP` | English · Hindi · Arabic |
| 🇹🇼 | **Taiwan (Province of China)** | `TW` | `TWN` | New Taiwan Dollar | `TWD` | English · Hindi · Arabic |
| 🇹🇯 | **Tajikistan** | `TJ` | `TJK` | Tajikistani Somoni | `TJS` | English · Hindi · Arabic |
| 🇹🇿 | **Tanzania, United Republic of** | `TZ` | `TZA` | Tanzanian Shilling | `TZS` | English · Hindi · Arabic |
| 🇹🇭 | **Thailand** | `TH` | `THA` | Thai Baht | `THB` | English · Hindi · Arabic |
| 🇹🇱 | **Timor-Leste** | `TL` | `TLS` | US Dollar | `USD` | English · Hindi · Arabic |
| 🇹🇬 | **Togo** | `TG` | `TGO` | West African CFA Franc | `XOF` | English · Hindi · Arabic |
| 🇹🇴 | **Tonga** | `TO` | `TON` | Tongan Paʻanga | `TOP` | English · Hindi · Arabic |
| 🇹🇹 | **Trinidad and Tobago** | `TT` | `TTO` | Trinidad & Tobago Dollar | `TTD` | English · Hindi · Arabic |
| 🇹🇳 | **Tunisia** | `TN` | `TUN` | Tunisian Dinar | `TND` | English · Hindi · Arabic |
| 🇹🇷 | **Türkiye** | `TR` | `TUR` | Turkish Lira | `TRY` | English · Hindi · Arabic |
| 🇹🇲 | **Turkmenistan** | `TM` | `TKM` | Turkmenistani Manat | `TMT` | English · Hindi · Arabic |
| 🇹🇻 | **Tuvalu** | `TV` | `TUV` | Australian Dollar | `AUD` | English · Hindi · Arabic |
| 🇺🇬 | **Uganda** | `UG` | `UGA` | Ugandan Shilling | `UGX` | English · Hindi · Arabic |
| 🇺🇦 | **Ukraine** | `UA` | `UKR` | Ukrainian Hryvnia | `UAH` | English · Hindi · Arabic |
| 🇺🇾 | **Uruguay** | `UY` | `URY` | Uruguayan Peso | `UYU` | English · Hindi · Arabic |
| 🇺🇿 | **Uzbekistan** | `UZ` | `UZB` | Uzbekistani Som | `UZS` | English · Hindi · Arabic |
| 🇻🇺 | **Vanuatu** | `VU` | `VUT` | Vanuatu Vatu | `VUV` | English · Hindi · Arabic |
| 🇻🇪 | **Venezuela (Bolivarian Republic of)** | `VE` | `VEN` | Venezuelan Bolívar | `VES` | English · Hindi · Arabic |
| 🇻🇳 | **Viet Nam** | `VN` | `VNM` | Vietnamese Dong | `VND` | English · Hindi · Arabic |
| 🇪🇭 | **Western Sahara** | `EH` | `ESH` | Moroccan Dirham | `MAD` | English · Hindi · Arabic |
| 🇾🇪 | **Yemen** | `YE` | `YEM` | Yemeni Rial | `YER` | English · Hindi · Arabic |
| 🇿🇲 | **Zambia** | `ZM` | `ZMB` | Zambian Kwacha | `ZMW` | English · Hindi · Arabic |
| 🇿🇼 | **Zimbabwe** | `ZW` | `ZWE` | Zimbabwean Gold, US Dollar | `ZWG`, `USD` | English · Hindi · Arabic |

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
