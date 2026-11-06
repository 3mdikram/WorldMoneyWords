from dataclasses import dataclass
from decimal import Decimal, InvalidOperation, ROUND_HALF_UP
import re
from .countries import ISO_COUNTRY_CODES

ONES = ["Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"]
TENS = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"]
SCALES = ["", "Thousand", "Million", "Billion", "Trillion", "Quadrillion"]

@dataclass(frozen=True)
class Currency:
    major_singular: str
    major_plural: str
    minor_singular: str
    minor_plural: str
    fraction_digits: int = 2

@dataclass(frozen=True)
class ConversionOptions:
    include_only: bool = True
    include_zero_minor: bool = False
    conjunction: str = "and"

    def __post_init__(self):
        if not self.conjunction.strip():
            raise ValueError("Conjunction cannot be empty")

CURRENCIES = {
    "INR": Currency("Rupee", "Rupees", "Paisa", "Paise"),
    "USD": Currency("US Dollar", "US Dollars", "Cent", "Cents"),
    "GBP": Currency("Pound", "Pounds", "Penny", "Pence"),
    "EUR": Currency("Euro", "Euros", "Cent", "Cents"),
    "AED": Currency("Dirham", "Dirhams", "Fil", "Fils"),
    "JPY": Currency("Yen", "Yen", "", "", 0),
}

def _under_thousand(value: int) -> str:
    words = []
    if value >= 100:
        words += [ONES[value // 100], "Hundred"]
        value %= 100
    if value >= 20:
        words.append(TENS[value // 10])
        if value % 10:
            words.append(ONES[value % 10])
    elif value:
        words.append(ONES[value])
    return " ".join(words)

def _spell(value: int) -> str:
    if value == 0:
        return "Zero"
    chunks = []
    scale = 0
    while value:
        chunk = value % 1000
        if chunk:
            part = _under_thousand(chunk)
            if SCALES[scale]:
                part += " " + SCALES[scale]
            chunks.append(part)
        value //= 1000
        scale += 1
        if scale >= len(SCALES) and value:
            raise ValueError("Amount is outside the supported range")
    return " ".join(reversed(chunks))

class AmountToWords:
    def __init__(self, currencies: dict[str, Currency] | None = None):
        self.currencies = {**CURRENCIES, **(currencies or {})}

    def convert(self, amount: int | float | str | Decimal, country_code: str, currency_code: str, options: ConversionOptions | None = None) -> str:
        options = options or ConversionOptions()
        country_input = country_code.strip().upper()
        country = ISO_COUNTRY_CODES.get(country_input)
        code = currency_code.strip().upper()
        if country is None:
            raise ValueError("Unknown ISO 3166-1 Alpha-2 or Alpha-3 country code")
        if not re.fullmatch(r"[A-Z]{3}", code):
            raise ValueError("Currency code must be a three-letter ISO 4217 code")
        currency = self.currencies.get(code, Currency(code, code, "Minor Unit", "Minor Units"))
        try:
            value = Decimal(str(amount))
        except InvalidOperation as error:
            raise ValueError("Amount must be numeric") from error
        quantum = Decimal(1).scaleb(-currency.fraction_digits)
        value = value.quantize(quantum, rounding=ROUND_HALF_UP)
        negative = value < 0
        value = abs(value)
        major = int(value)
        minor = int((value - major) * (10 ** currency.fraction_digits))
        parts = ["Minus"] if negative else []
        parts += [_spell(major), currency.major_singular if major == 1 else currency.major_plural]
        if minor or (currency.fraction_digits > 0 and options.include_zero_minor):
            parts += [options.conjunction, _spell(minor), currency.minor_singular if minor == 1 else currency.minor_plural]
        if options.include_only:
            parts.append("Only")
        return " ".join(filter(None, parts))
