# WorldMoneyWords for Go

```bash
go get github.com/3mdikram/WorldMoneyWords/go
```

```go
import amountwords "github.com/3mdikram/WorldMoneyWords/go"

text, err := amountwords.Convert(1234.50, "IND", "INR")
```

The arguments are amount, ISO 3166-1 alpha-3 country code, and ISO 4217 currency code.
