import unittest
from amount_to_words import AmountToWords

class ConverterTest(unittest.TestCase):
    def test_inr(self):
        self.assertEqual("One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only", AmountToWords().convert("1234.50", "IND", "INR"))

    def test_negative_gbp(self):
        self.assertEqual("Minus Ten Pounds and Twenty Five Pence Only", AmountToWords().convert("-10.25", "GBR", "GBP"))

    def test_alpha2_country(self):
        self.assertEqual("Ten Rupees Only", AmountToWords().convert("10", "IN", "INR"))

if __name__ == "__main__":
    unittest.main()
