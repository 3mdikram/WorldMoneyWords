package io.github.mdikram.worldmoneywords;

public final class ConversionOptions {
    private final boolean includeOnly;
    private final boolean includeZeroMinor;
    private final String conjunction;
    private final String language;
    private final boolean shortCurrencyName;
    private final boolean ignoreCountryName;

    public ConversionOptions(boolean includeOnly, boolean includeZeroMinor, String conjunction) {
        this(includeOnly, includeZeroMinor, conjunction, "en", false, false);
    }

    public ConversionOptions(boolean includeOnly, boolean includeZeroMinor, String conjunction, String language) {
        this(includeOnly, includeZeroMinor, conjunction, language, false, false);
    }

    public ConversionOptions(boolean includeOnly, boolean includeZeroMinor, String conjunction, String language, boolean shortCurrencyName) {
        this(includeOnly, includeZeroMinor, conjunction, language, shortCurrencyName, false);
    }

    public ConversionOptions(boolean includeOnly, boolean includeZeroMinor, String conjunction, String language, boolean shortCurrencyName, boolean ignoreCountryName) {
        if (conjunction == null || conjunction.trim().isEmpty()) throw new IllegalArgumentException("Conjunction cannot be empty");
        if (language == null || language.trim().isEmpty()) throw new IllegalArgumentException("Language cannot be empty");
        this.includeOnly = includeOnly;
        this.includeZeroMinor = includeZeroMinor;
        this.conjunction = conjunction;
        this.language = language.toLowerCase();
        this.shortCurrencyName = shortCurrencyName;
        this.ignoreCountryName = ignoreCountryName;
    }

    public static ConversionOptions defaults() { return new ConversionOptions(true, false, "and"); }
    public boolean includeOnly() { return includeOnly; }
    public boolean includeZeroMinor() { return includeZeroMinor; }
    public String conjunction() { return conjunction; }
    public String language() { return language; }
    public boolean shortCurrencyName() { return shortCurrencyName; }
    public boolean ignoreCountryName() { return ignoreCountryName; }
}
