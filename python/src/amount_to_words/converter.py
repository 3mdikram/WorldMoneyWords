from dataclasses import dataclass
from decimal import Decimal, InvalidOperation, ROUND_HALF_UP
import re
from typing import Callable
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
    language: str = "en"

    def __post_init__(self):
        if not self.conjunction.strip():
            raise ValueError("Conjunction cannot be empty")
        if not self.language.strip():
            raise ValueError("Language cannot be empty")

@dataclass(frozen=True)
class LanguagePack:
    code: str
    spell: Callable[[int], str]
    currency_name: Callable[[str, bool], str]
    minus: str
    conjunction: str
    only: str
    minor_singular: str
    minor_plural: str

    def __post_init__(self):
        normalized = self.code.strip().lower()
        if not re.fullmatch(r"[a-z]{2,8}(?:-[a-z0-9]{2,8})*", normalized):
            raise ValueError("Language pack code must be a valid language tag")
        for value in (self.minus, self.conjunction, self.only, self.minor_singular, self.minor_plural):
            if not value.strip(): raise ValueError("Language pack labels cannot be empty")
        object.__setattr__(self, "code", normalized)

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

HI_0_99 = "शून्य|एक|दो|तीन|चार|पाँच|छह|सात|आठ|नौ|दस|ग्यारह|बारह|तेरह|चौदह|पन्द्रह|सोलह|सत्रह|अठारह|उन्नीस|बीस|इक्कीस|बाईस|तेईस|चौबीस|पच्चीस|छब्बीस|सत्ताईस|अट्ठाईस|उनतीस|तीस|इकतीस|बत्तीस|तैंतीस|चौंतीस|पैंतीस|छत्तीस|सैंतीस|अड़तीस|उनतालीस|चालीस|इकतालीस|बयालीस|तैंतालीस|चौवालीस|पैंतालीस|छियालीस|सैंतालीस|अड़तालीस|उनचास|पचास|इक्यावन|बावन|तिरेपन|चौवन|पचपन|छप्पन|सत्तावन|अट्ठावन|उनसठ|साठ|इकसठ|बासठ|तिरेसठ|चौंसठ|पैंसठ|छियासठ|सड़सठ|अड़सठ|उनहत्तर|सत्तर|इकहत्तर|बहत्तर|तिहत्तर|चौहत्तर|पचहत्तर|छिहत्तर|सतहत्तर|अठहत्तर|उनासी|अस्सी|इक्यासी|बयासी|तिरासी|चौरासी|पचासी|छियासी|सत्तासी|अट्ठासी|नवासी|नब्बे|इक्यानबे|बानबे|तिरानबे|चौरानबे|पंचानबे|छियानबे|सत्तानबे|अट्ठानबे|निन्यानबे".split("|")
AR_0_19 = "صفر|واحد|اثنان|ثلاثة|أربعة|خمسة|ستة|سبعة|ثمانية|تسعة|عشرة|أحد عشر|اثنا عشر|ثلاثة عشر|أربعة عشر|خمسة عشر|ستة عشر|سبعة عشر|ثمانية عشر|تسعة عشر".split("|")
AR_TENS = ["", "", "عشرون", "ثلاثون", "أربعون", "خمسون", "ستون", "سبعون", "ثمانون", "تسعون"]

def _under_100_localized(value: int, language: str) -> str:
    if language == "hi": return HI_0_99[value]
    if value < 20: return AR_0_19[value]
    return AR_TENS[value // 10] if value % 10 == 0 else f"{AR_0_19[value % 10]} و{AR_TENS[value // 10]}"

def _under_1000_localized(value: int, language: str) -> str:
    parts = []
    if value >= 100:
        hundreds = value // 100
        if language == "hi": parts.append(("एक" if hundreds == 1 else HI_0_99[hundreds]) + " सौ")
        else: parts.append({1:"مائة",2:"مائتان"}.get(hundreds, AR_0_19[hundreds] + " مائة"))
        value %= 100
    if value: parts.append(_under_100_localized(value, language))
    return (" و" if language == "ar" else " ").join(parts)

def _spell_localized(value: int, language: str) -> str:
    if language == "en": return _spell(value)
    if value == 0: return HI_0_99[0] if language == "hi" else AR_0_19[0]
    scales = ["", "हज़ार", "मिलियन", "बिलियन", "ट्रिलियन", "क्वाड्रिलियन"] if language == "hi" else ["", "ألف", "مليون", "مليار", "تريليون", "كوادريليون"]
    chunks, scale = [], 0
    while value:
        chunk = value % 1000
        if chunk:
            part = "" if language == "ar" and chunk == 1 and scale > 0 else _under_1000_localized(chunk, language)
            if scales[scale]: part = (part + " " if part else "") + scales[scale]
            chunks.append(part)
        value //= 1000; scale += 1
        if scale >= len(scales) and value: raise ValueError("Amount is outside the supported range")
    return (" و" if language == "ar" else " ").join(reversed(chunks))

LOCALIZED_CURRENCIES = {
    "hi": {"INR":"भारतीय रुपया", "USD":"अमेरिकी डॉलर", "GBP":"ब्रिटिश पाउंड", "EUR":"यूरो", "AED":"यूएई दिरहम", "JPY":"जापानी येन"},
    "ar": {"INR":"روبية هندية", "USD":"دولار أمريكي", "GBP":"جنيه إسترليني", "EUR":"يورو", "AED":"درهم إماراتي", "JPY":"ين ياباني"},
}

class AmountToWords:
    def __init__(self, currencies: dict[str, Currency] | None = None, language_packs: list[LanguagePack] | None = None):
        self.currencies = {**CURRENCIES, **(currencies or {})}
        self.language_packs = {pack.code: pack for pack in (language_packs or [])}

    def add_language_pack(self, pack: LanguagePack) -> "AmountToWords":
        return AmountToWords(self.currencies, [*self.language_packs.values(), pack])

    def convert(self, amount: int | float | str | Decimal, country_code: str, currency_code: str, options: ConversionOptions | None = None) -> str:
        options = options or ConversionOptions()
        country_input = country_code.strip().upper()
        country = ISO_COUNTRY_CODES.get(country_input)
        code = currency_code.strip().upper()
        if country is None:
            raise ValueError("Unknown ISO 3166-1 Alpha-2 or Alpha-3 country code")
        language = _resolve_language(options.language, self.language_packs)
        pack = self.language_packs.get(language)
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
        labels = (pack.minus, pack.conjunction, pack.only, pack.minor_singular, pack.minor_plural) if pack else {"en": ("Minus", "and", "Only", "Minor Unit", "Minor Units"), "hi": ("ऋण", "और", "मात्र", "उप-इकाई", "उप-इकाइयाँ"), "ar": ("سالب", "و", "فقط", "وحدة فرعية", "وحدات فرعية")}[language]
        parts = [labels[0]] if negative else []
        major_name = currency.major_singular if major == 1 else currency.major_plural
        if pack: major_name = pack.currency_name(code, major == 1)
        elif language != "en": major_name = LOCALIZED_CURRENCIES[language].get(code, code)
        parts += [pack.spell(major) if pack else _spell_localized(major, language), major_name]
        if minor or (currency.fraction_digits > 0 and options.include_zero_minor):
            conjunction = labels[1] if options.conjunction == "and" else options.conjunction
            minor_name = currency.minor_singular if minor == 1 else currency.minor_plural
            if language != "en": minor_name = labels[3] if minor == 1 else labels[4]
            parts += [conjunction, pack.spell(minor) if pack else _spell_localized(minor, language), minor_name]
        if options.include_only:
            parts.append(labels[2])
        return " ".join(filter(None, parts))

def _resolve_language(requested: str, language_packs: dict[str, LanguagePack] | None = None) -> str:
    language = requested.strip().lower()
    if language in {"en", "hi", "ar"} or language in (language_packs or {}): return language
    return "en"
