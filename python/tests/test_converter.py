import unittest
from amount_to_words import AmountToWords, ConversionOptions
from amount_to_words.countries import ALPHA2_TO_ALPHA3

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

    def test_hindi_and_arabic(self):
        self.assertEqual("एक हज़ार दो सौ चौंतीस भारतीय रुपया और पचास उप-इकाइयाँ मात्र", AmountToWords().convert("1234.50", "IN", "INR", ConversionOptions(language="hi")))
        self.assertEqual("ألف ومائتان وأربعة وثلاثون دولار أمريكي و خمسون وحدات فرعية فقط", AmountToWords().convert("1234.50", "US", "USD", ConversionOptions(language="ar")))

    def test_all_languages_work_for_all_200_countries(self):
        self.assertEqual(200, len(ALPHA2_TO_ALPHA3))
        converter = AmountToWords()
        for country in ALPHA2_TO_ALPHA3:
            for language in ("en", "hi", "ar"):
                self.assertTrue(converter.convert("42.50", country, "USD", ConversionOptions(language=language)))

if __name__ == "__main__":
    unittest.main()
