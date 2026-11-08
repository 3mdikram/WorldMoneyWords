# WorldMoneyWords for Android

Kotlin-friendly Android AAR for converting monetary amounts into words. It uses the tested WorldMoneyWords Java engine and supports English, Hindi, Arabic, 200 country codes, ISO currencies, exact decimal strings, custom language packs, and Android API 21+.

## Status

The Android artifact is **unreleased**. Its development version is `0.0.0-SNAPSHOT`; no Maven release coordinates should be used until a release is announced.

## Planned installation

After release to Maven Central:

```kotlin
dependencies {
    implementation("io.github.3mdikram:worldmoneywords-android:<released-version>")
}
```

## Kotlin usage

```kotlin
import io.github.mdikram.worldmoneywords.android.AndroidAmountToWords
import io.github.mdikram.worldmoneywords.android.AndroidConversionOptions

val converter = AndroidAmountToWords()

val english = converter.convert("1234.50", "IN", "INR")

val hindi = converter.convert(
    amount = "1234.50",
    countryCode = "IN",
    currencyCode = "INR",
    options = AndroidConversionOptions(language = "hi"),
)
```

Prefer decimal strings rather than `Double` for financial values. `AndroidAmountToWords` deliberately exposes a string amount API so Android applications do not accidentally introduce binary floating-point rounding.

Custom Java `LanguagePack` implementations can be registered through `converter.javaDelegate()`.

## Build and test

The module requires JDK 17 and Android SDK 35:

```bash
gradle -p android testDebugUnitTest assembleRelease
```
