package io.github.mdikram.worldmoneywords.android

import org.junit.Assert.assertEquals
import org.junit.Assert.assertThrows
import org.junit.Test

class AndroidAmountToWordsTest {
    private val converter = AndroidAmountToWords()

    @Test
    fun convertsExactDecimalString() {
        assertEquals(
            "One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only",
            converter.convert("1234.50", "IN", "INR"),
        )
    }

    @Test
    fun supportsKotlinOptions() {
        assertEquals(
            "Ten US Dollars plus Zero Cents",
            converter.convert(
                "10.00",
                "US",
                "USD",
                AndroidConversionOptions(
                    includeOnly = false,
                    includeZeroMinor = true,
                    conjunction = "plus",
                    shortCurrencyName = true,
                ),
            ),
        )
    }

    @Test
    fun supportsCountryCodeAndIgnoredCountryNameOptions() {
        assertEquals("Two PK Rupees Only", converter.convert("2", "PK", "PKR", AndroidConversionOptions(shortCurrencyName = true)))
        assertEquals("Two Rupees Only", converter.convert("2", "PK", "PKR", AndroidConversionOptions(ignoreCountryName = true)))
    }

    @Test
    fun supportsLocalizedOutput() {
        assertEquals(
            "एक हज़ार दो सौ चौंतीस भारतीय रुपया और पचास उप-इकाइयाँ मात्र",
            converter.convert(
                "1234.50",
                "IN",
                "INR",
                AndroidConversionOptions(language = "hi"),
            ),
        )
    }

    @Test
    fun normalizesCountryAndRejectsInvalidInput() {
        assertEquals("IND", converter.normalizeCountryCode("in"))
        assertThrows(IllegalArgumentException::class.java) {
            converter.convert("1,2", "US", "USD")
        }
    }
}
