from amount_to_words import AmountToWords, ConversionOptions

converter = AmountToWords()

# Basic conversion with an Alpha-2 country code.
print(converter.convert("1234.50", "IN", "INR"))

# Advanced conversion options.
options = ConversionOptions(
    include_only=False,
    include_zero_minor=True,
    conjunction="plus",
)
print(converter.convert("10.00", "US", "USD", options))
