package io.github.mdikram.worldmoneywords.android

import io.github.mdikram.worldmoneywords.ConversionOptions
import io.github.mdikram.worldmoneywords.WorldMoneyWords

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

        return delegate.convert(
            amount.trim(),
            countryCode,
            currencyCode,
            options.toJavaOptions(),
        )
    }

    fun normalizeCountryCode(countryCode: String): String =
        delegate.normalizeCountryCode(countryCode)

    /** Access the Java engine for registering a custom LanguagePack. */
    fun javaDelegate(): WorldMoneyWords = delegate

    private fun AndroidConversionOptions.toJavaOptions() = ConversionOptions(
        includeOnly,
        includeZeroMinor,
        conjunction,
        language,
        shortCurrencyName,
    )
}
