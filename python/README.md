# WorldMoneyWords for Python

```bash
pip install worldmoneywords
```

```python
from amount_to_words import AmountToWords

converter = AmountToWords()
print(converter.convert("1234.50", "IN", "INR"))
```

An optional fourth `ConversionOptions` argument controls the suffix, zero minor units, conjunction, and output `language` (`en`, `hi`, or `ar`).

```python
converter.convert("1234.50", "IN", "INR", ConversionOptions(language="hi"))
converter.convert("1234.50", "US", "USD", ConversionOptions(language="ar"))
```

The arguments are amount, ISO 3166-1 Alpha-2 or Alpha-3 country code, and ISO 4217 currency code.
