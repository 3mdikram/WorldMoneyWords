import unittest
from amount_to_words import AmountToWords, ConversionOptions

class ConverterTest(unittest.TestCase):
    def test_inr(self):
        self.assertEqual("One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only", AmountToWords().convert("1234.50", "IND", "INR"))

    def test_negative_gbp(self):
        self.assertEqual("Minus Ten Pounds and Twenty Five Pence Only", AmountToWords().convert("-10.25", "GBR", "GBP"))

    def test_alpha2_country(self):
        self.assertEqual("Ten Rupees Only", AmountToWords().convert("10", "IN", "INR"))

    def test_minor_territory_is_not_supported(self):
        with self.assertRaises(ValueError):
            AmountToWords().convert("10", "AI", "USD")

    def test_options(self):
        options = ConversionOptions(include_only=False, include_zero_minor=True, conjunction="plus")
        self.assertEqual("Ten US Dollars plus Zero Cents", AmountToWords().convert("10", "US", "USD", options))

if __name__ == "__main__":
    unittest.main()
