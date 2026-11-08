package io.github.mdikram.worldmoneywords.android

/** Kotlin-friendly conversion options for Android callers. */
data class AndroidConversionOptions(
    val includeOnly: Boolean = true,
    val includeZeroMinor: Boolean = false,
    val conjunction: String = "and",
    val language: String = "en",
    val shortCurrencyName: Boolean = false,
) {
    init {
        require(conjunction.isNotBlank()) { "Conjunction cannot be empty" }
        require(language.isNotBlank()) { "Language cannot be empty" }
    }
}
