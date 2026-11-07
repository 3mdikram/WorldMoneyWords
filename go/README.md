# WorldMoneyWords for Go

Go amount-to-words, money-to-text, number-to-words, and currency-to-words library with multilingual output and ISO 3166/4217 support for 200 countries.

```bash
go get github.com/3mdikram/WorldMoneyWords/go/v2
```

```go
import amountwords "github.com/3mdikram/WorldMoneyWords/go/v2"

text, err := amountwords.Convert(1234.50, "IN", "INR")
```

Pass an optional `Options` value as the fourth argument to customize the suffix, zero minor units, conjunction, short currency names, and output `Language` (`en`, `hi`, `ar`, or a registered language code). Set `ShortCurrencyName: true` to output `Dollars` instead of `US Dollars`.

```go
text, err := amountwords.Convert(1234.50, "IN", "INR", amountwords.Options{IncludeOnly: true, Conjunction: "and", Language: "hi"})
```

The arguments are amount, ISO 3166-1 Alpha-2 or Alpha-3 country code, and ISO 4217 currency code.
