# WorldMoneyWords for Go

```bash
go get github.com/3mdikram/WorldMoneyWords/go/v2
```

```go
import amountwords "github.com/3mdikram/WorldMoneyWords/go/v2"

text, err := amountwords.Convert(1234.50, "IN", "INR")
```

Pass an optional `Options` value as the fourth argument to customize the suffix, zero minor units, and conjunction.

The arguments are amount, ISO 3166-1 Alpha-2 or Alpha-3 country code, and ISO 4217 currency code.
