package io.github.mdikram.worldmoneywords;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertThrows;
import org.junit.jupiter.api.Test;
import java.math.BigInteger;

class WorldMoneyWordsTest {
    private final WorldMoneyWords converter = new WorldMoneyWords();

    @Test void registersCustomOfficialLanguagePack() {
        LanguagePack pack = new LanguagePack() {
            public String code(){return "xx";} public String spell(BigInteger n){return "number-"+n;} public String currencyName(String code,boolean singular){return code.toLowerCase()+(singular?"-unit":"-units");}
            public String minus(){return "negative";} public String conjunction(){return "with";} public String only(){return "complete";} public String minorSingular(){return "part";} public String minorPlural(){return "parts";}
        };
        assertEquals("number-2 usd-units with number-50 parts complete", new WorldMoneyWords().registerLanguagePack(pack).convert("2.50","US","USD",new ConversionOptions(true,false,"and","xx")));
    }

    @Test void convertsDefaultAmount() {
        assertEquals("One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only", converter.convert("1234.50", "IN", "INR"));
    }

    @Test void convertsWithOptions() {
        ConversionOptions options = new ConversionOptions(false, true, "plus");
        assertEquals("Ten US Dollars plus Zero Cents", converter.convert("10.00", "US", "USD", options));
    }

    @Test void usesShortCurrencyName() {
        ConversionOptions options = new ConversionOptions(false, true, "plus", "en", true);
        assertEquals("Ten US Dollars plus Zero Cents", converter.convert("10.00", "US", "USD", options));
    }

    @Test void supportsCountryCodeAndIgnoredCountryNameOptions() {
        assertEquals("Two PK Rupees Only", converter.convert("2", "PK", "PKR", new ConversionOptions(true, false, "and", "en", true, false)));
        assertEquals("Two Rupees Only", converter.convert("2", "PK", "PKR", new ConversionOptions(true, false, "and", "en", false, true)));
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

    @Test void unavailableLanguageFallsBackToEnglish() {
        assertEquals(true, converter.convert("10", "PK", "PKR", new ConversionOptions(true, false, "and", "ur")).startsWith("Ten"));
    }

    @Test void validatesThousandsGrouping() {
        assertEquals("One Thousand Two Hundred Thirty Four US Dollars and Fifty Six Cents Only", converter.convert("1,234.56", "US", "USD"));
        assertThrows(IllegalArgumentException.class, () -> converter.convert("1,2", "US", "USD"));
        assertThrows(IllegalArgumentException.class, () -> converter.convert("12,34", "US", "USD"));
    }
}
