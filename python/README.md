# WorldMoneyWords for Python

```bash
pip install 3mdikram-worldmoneywords
```

```python
from amount_to_words import AmountToWords

converter = AmountToWords()
print(converter.convert("1234.50", "IN", "INR"))
```

The arguments are amount, ISO 3166-1 Alpha-2 or Alpha-3 country code, and ISO 4217 currency code.
