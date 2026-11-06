package io.github.mdikram.worldmoneywords;

public final class ConversionOptions {
    private final boolean includeOnly;
    private final boolean includeZeroMinor;
    private final String conjunction;

    public ConversionOptions(boolean includeOnly, boolean includeZeroMinor, String conjunction) {
        if (conjunction == null || conjunction.trim().isEmpty()) throw new IllegalArgumentException("Conjunction cannot be empty");
        this.includeOnly = includeOnly;
        this.includeZeroMinor = includeZeroMinor;
        this.conjunction = conjunction;
    }

    public static ConversionOptions defaults() { return new ConversionOptions(true, false, "and"); }
    public boolean includeOnly() { return includeOnly; }
    public boolean includeZeroMinor() { return includeZeroMinor; }
    public String conjunction() { return conjunction; }
}
