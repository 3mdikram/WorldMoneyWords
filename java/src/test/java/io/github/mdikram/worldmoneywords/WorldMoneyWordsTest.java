package io.github.mdikram.worldmoneywords;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertThrows;
import org.junit.jupiter.api.Test;

class WorldMoneyWordsTest {
    private final WorldMoneyWords converter = new WorldMoneyWords();

    @Test void convertsDefaultAmount() {
        assertEquals("One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only", converter.convert("1234.50", "IN", "INR"));
    }

    @Test void convertsWithOptions() {
        ConversionOptions options = new ConversionOptions(false, true, "plus");
        assertEquals("Ten US Dollars plus Zero Cents", converter.convert("10.00", "US", "USD", options));
    }

    @Test void normalizesAlpha2AndRejectsMinorTerritory() {
        assertEquals("IND", converter.normalizeCountryCode("in"));
        assertThrows(IllegalArgumentException.class, () -> converter.convert("10", "AI", "USD"));
    }

    @Test void supportsHindiAndArabic() {
        assertEquals("एक हज़ार दो सौ चौंतीस भारतीय रुपया और पचास उप-इकाइयाँ मात्र", converter.convert("1234.50", "IN", "INR", new ConversionOptions(true, false, "and", "hi")));
        assertEquals("ألف ومائتان وأربعة وثلاثون دولار أمريكي و خمسون وحدات فرعية فقط", converter.convert("1234.50", "US", "USD", new ConversionOptions(true, false, "and", "ar")));
    }

    @Test void allLanguagesWorkForAll200Countries() {
        int countries = 0;
        for (String country : SupportedCountries.alpha2Codes()) {
            countries++;
            for (String language : new String[]{"en", "hi", "ar"}) {
                String text = converter.convert("42.50", country, "USD", new ConversionOptions(true, false, "and", language));
                if (text.isEmpty()) throw new AssertionError(country + "/" + language);
            }
        }
        assertEquals(200, countries);
    }

    @Test void autoLanguageAndEnglishFallback() {
        assertEquals(true, converter.convert("10", "IN", "INR", new ConversionOptions(true, false, "and", "auto")).startsWith("दस"));
        assertEquals(true, converter.convert("10", "SA", "SAR", new ConversionOptions(true, false, "and", "auto")).startsWith("عشرة"));
        assertEquals(true, converter.convert("10", "PK", "PKR", new ConversionOptions(true, false, "and", "auto")).startsWith("Ten"));
        assertEquals(true, converter.convert("10", "PK", "PKR", new ConversionOptions(true, false, "and", "ur")).startsWith("Ten"));
    }
}
