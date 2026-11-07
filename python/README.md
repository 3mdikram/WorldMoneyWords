# WorldMoneyWords for Python

```bash
pip install worldmoneywords
```

```python
from amount_to_words import AmountToWords

converter = AmountToWords()
print(converter.convert("1234.50", "IN", "INR"))
```

An optional fourth `ConversionOptions` argument controls the suffix, zero minor units, and conjunction.

The arguments are amount, ISO 3166-1 Alpha-2 or Alpha-3 country code, and ISO 4217 currency code.
