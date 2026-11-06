import unittest
from amount_to_words import AmountToWords

class ConverterTest(unittest.TestCase):
    def test_inr(self):
        self.assertEqual("One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only", AmountToWords().convert("1234.50", "IND", "INR"))

    def test_negative_gbp(self):
        self.assertEqual("Minus Ten Pounds and Twenty Five Pence Only", AmountToWords().convert("-10.25", "GBR", "GBP"))

if __name__ == "__main__":
    unittest.main()
