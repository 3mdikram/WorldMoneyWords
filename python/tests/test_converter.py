import unittest
from amount_to_words import AmountToWords, ConversionOptions, LanguagePack
from amount_to_words.countries import ALPHA2_TO_ALPHA3

class ConverterTest(unittest.TestCase):
    def test_custom_official_language_pack(self):
        pack = LanguagePack("xx", lambda number: f"number-{number}", lambda code, singular: code.lower() + ("-unit" if singular else "-units"), "negative", "with", "complete", "part", "parts")
        converter = AmountToWords().add_language_pack(pack)
        self.assertEqual("number-2 usd-units with number-50 parts complete", converter.convert("2.50", "US", "USD", ConversionOptions(language="xx")))

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

    def test_short_currency_name_option(self):
        options = ConversionOptions(include_only=False, include_zero_minor=True, conjunction="plus", short_currency_name=True)
        self.assertEqual("Ten US Dollars plus Zero Cents", AmountToWords().convert("10", "US", "USD", options))

    def test_country_code_and_ignored_country_name_options(self):
        converter = AmountToWords()
        self.assertEqual("Two PK Rupees Only", converter.convert("2", "PK", "PKR", ConversionOptions(short_currency_name=True)))
        self.assertEqual("Two Rupees Only", converter.convert("2", "PK", "PKR", ConversionOptions(ignore_country_name=True)))

    def test_hindi_and_arabic(self):
        self.assertEqual("एक हज़ार दो सौ चौंतीस भारतीय रुपया और पचास उप-इकाइयाँ मात्र", AmountToWords().convert("1234.50", "IN", "INR", ConversionOptions(language="hi")))
        self.assertEqual("ألف ومائتان وأربعة وثلاثون دولار أمريكي و خمسون وحدات فرعية فقط", AmountToWords().convert("1234.50", "US", "USD", ConversionOptions(language="ar")))

    def test_all_languages_work_for_all_200_countries(self):
        self.assertEqual(200, len(ALPHA2_TO_ALPHA3))
        converter = AmountToWords()
        for country in ALPHA2_TO_ALPHA3:
            for language in ("en", "hi", "ar"):
                self.assertTrue(converter.convert("42.50", country, "USD", ConversionOptions(language=language)))

    def test_unavailable_language_falls_back_to_english(self):
        converter = AmountToWords()
        self.assertTrue(converter.convert("10", "PK", "PKR", ConversionOptions(language="ur")).startswith("Ten"))

    def test_rejects_unknown_currency_and_non_finite_amounts(self):
        converter = AmountToWords()
        with self.assertRaises(ValueError):
            converter.convert("10", "US", "ZZZ")
        for amount in ("NaN", "Infinity", "-Infinity"):
            with self.assertRaises(ValueError):
                converter.convert(amount, "US", "USD")

    def test_validates_thousands_grouping(self):
        converter = AmountToWords()
        self.assertEqual("One Thousand Two Hundred Thirty Four US Dollars and Fifty Six Cents Only", converter.convert("1,234.56", "US", "USD"))
        with self.assertRaises(ValueError):
            converter.convert("1,2", "US", "USD")

if __name__ == "__main__":
    unittest.main()
