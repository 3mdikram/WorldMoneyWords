package io.github.mdikram.worldmoneywords.android

import io.github.mdikram.worldmoneywords.ConversionOptions
import io.github.mdikram.worldmoneywords.WorldMoneyWords
import java.util.Currency
import java.util.Locale

/**
 * Android and Kotlin facade for WorldMoneyWords.
 *
 * Keep monetary values as decimal strings so their precision is preserved.
 * This class is stateless apart from registered language packs in its delegate
 * and can be retained in a dependency-injection singleton.
 */
class AndroidAmountToWords(
    private val delegate: WorldMoneyWords = WorldMoneyWords(),
) {
    private val amountPattern = Regex("-?(?:\\d+|\\d{1,3}(?:,\\d{3})+)(?:\\.\\d+)?")

    @JvmOverloads
    fun convert(
        amount: String,
        countryCode: String,
        currencyCode: String,
        options: AndroidConversionOptions = AndroidConversionOptions(),
    ): String {
        require(amountPattern.matches(amount.trim())) {
            "Amount must be a plain numeric value with valid thousands grouping"
        }

        val text = delegate.convert(
            amount.trim(),
            countryCode,
            currencyCode,
            options.toJavaOptions(forceFullCurrencyName = options.shortCurrencyName || options.ignoreCountryName),
        )
        if (!options.shortCurrencyName && !options.ignoreCountryName) return text

        val singular = amount.replace(",", "").toBigDecimal().abs().setScale(0, java.math.RoundingMode.DOWN).compareTo(java.math.BigDecimal.ONE) == 0
        val code = currencyCode.trim().uppercase(Locale.ROOT)
        val fullNames = mapOf(
            "INR" to ("Rupee" to "Rupees"), "USD" to ("US Dollar" to "US Dollars"),
            "GBP" to ("Pound" to "Pounds"), "EUR" to ("Euro" to "Euros"),
            "AED" to ("Dirham" to "Dirhams"), "JPY" to ("Yen" to "Yen"),
            "PKR" to ("Pakistani Rupee" to "Pakistani Rupee"),
        )
        val fullName = fullNames[code]?.let { if (singular) it.first else it.second }
            ?: Currency.getInstance(code).getDisplayName(Locale.ENGLISH)
        val shortNames = mapOf(
            "INR" to ("Rupee" to "Rupees"), "USD" to ("Dollar" to "Dollars"),
            "GBP" to ("Pound" to "Pounds"), "EUR" to ("Euro" to "Euros"),
            "AED" to ("Dirham" to "Dirhams"), "JPY" to ("Yen" to "Yen"),
            "PKR" to ("Rupee" to "Rupees"),
        )
        val shortName = shortNames[code]?.let { if (singular) it.first else it.second }
            ?: fullName.substringAfterLast(' ')
        val alpha2 = countryCode.trim().uppercase(Locale.ROOT).let { input ->
            if (input.length == 2) input else Locale.getISOCountries()
                .firstOrNull { Locale("", it).isO3Country == input } ?: input
        }
        val replacement = if (options.ignoreCountryName) shortName else "$alpha2 $shortName"
        return text.replaceFirst(fullName, replacement)
    }

    fun normalizeCountryCode(countryCode: String): String =
        delegate.normalizeCountryCode(countryCode)

    /** Access the Java engine for registering a custom LanguagePack. */
    fun javaDelegate(): WorldMoneyWords = delegate

    private fun AndroidConversionOptions.toJavaOptions(forceFullCurrencyName: Boolean) = ConversionOptions(
        includeOnly,
        includeZeroMinor,
        conjunction,
        language,
        if (forceFullCurrencyName) false else shortCurrencyName,
    )
}
