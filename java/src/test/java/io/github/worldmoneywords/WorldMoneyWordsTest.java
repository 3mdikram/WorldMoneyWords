package io.github.worldmoneywords;

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
}
