# WorldMoneyWords for Python

```bash
pip install 3mdikram-worldmoneywords
```

```python
from amount_to_words import AmountToWords

converter = AmountToWords()
print(converter.convert("1234.50", "IND", "INR"))
```

The arguments are amount, ISO 3166-1 alpha-3 country code, and ISO 4217 currency code.
