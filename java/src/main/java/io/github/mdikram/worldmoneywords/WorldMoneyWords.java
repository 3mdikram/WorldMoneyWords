package io.github.mdikram.worldmoneywords;

import java.math.BigDecimal;
import java.math.BigInteger;
import java.math.RoundingMode;
import java.util.Currency;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;

public final class WorldMoneyWords {
    private static final String[] ONES = {"Zero","One","Two","Three","Four","Five","Six","Seven","Eight","Nine","Ten","Eleven","Twelve","Thirteen","Fourteen","Fifteen","Sixteen","Seventeen","Eighteen","Nineteen"};
    private static final String[] TENS = {"","","Twenty","Thirty","Forty","Fifty","Sixty","Seventy","Eighty","Ninety"};
    private static final String[] SCALES = {"","Thousand","Million","Billion","Trillion","Quadrillion","Quintillion"};
    private static final Map<String, CurrencyWords> CURRENCIES = currencies();
    private final Map<String, LanguagePack> languagePacks = new HashMap<>();

    public WorldMoneyWords registerLanguagePack(LanguagePack pack) {
        if (pack == null) throw new IllegalArgumentException("Language pack is required");
        String code = pack.code().trim().toLowerCase();
        if (!code.matches("[a-z]{2,8}(?:-[a-z0-9]{2,8})*")) throw new IllegalArgumentException("Language pack code must be a valid language tag");
        if (java.util.List.of(pack.minus(), pack.conjunction(), pack.only(), pack.minorSingular(), pack.minorPlural()).stream().anyMatch(value -> value == null || value.trim().isEmpty())) throw new IllegalArgumentException("Language pack labels cannot be empty");
        languagePacks.put(code, pack);
        return this;
    }

    public String convert(String amount, String countryCode, String currencyCode) {
        return convert(amount, countryCode, currencyCode, ConversionOptions.defaults());
    }

    public String convert(String amount, String countryCode, String currencyCode, ConversionOptions options) {
        String country = SupportedCountries.normalize(countryCode);
        if (options == null) options = ConversionOptions.defaults();
        String code = currencyCode == null ? "" : currencyCode.trim().toUpperCase();
        CurrencyWords currency = currency(code);
        BigDecimal value;
        try { value = new BigDecimal(amount.replace(",", "")); }
        catch (RuntimeException error) { throw new IllegalArgumentException("Amount must be numeric", error); }
        value = value.setScale(currency.fractionDigits, RoundingMode.HALF_UP);
        boolean negative = value.signum() < 0;
        value = value.abs();
        BigInteger major = value.toBigInteger();
        BigInteger minor = value.subtract(new BigDecimal(major)).movePointRight(currency.fractionDigits).toBigInteger();
        String language = resolveLanguage(options.language());
        LanguagePack pack = languagePacks.get(language);
        String[] labels = pack != null ? new String[]{pack.minus(),pack.conjunction(),pack.only(),pack.minorSingular(),pack.minorPlural()} : language.equals("en") ? new String[]{"Minus","and","Only","Minor Unit","Minor Units"} : LocalizedWords.labels(language);
        StringBuilder result = new StringBuilder();
        if (negative && value.signum() != 0) result.append(labels[0]).append(' ');
        String majorWords = pack != null ? pack.spell(major) : language.equals("en") ? spell(major) : LocalizedWords.spell(major, language);
        String majorName = pack != null ? pack.currencyName(code, major.equals(BigInteger.ONE)) : language.equals("en") ? (major.equals(BigInteger.ONE) ? currency.majorSingular : currency.majorPlural) : LocalizedWords.currency(code, language);
        if (options.shortCurrencyName()) majorName = shortCurrencyName(code, language, major.equals(BigInteger.ONE), majorName);
        result.append(majorWords).append(' ').append(majorName);
        if (minor.signum() > 0 || (currency.fractionDigits > 0 && options.includeZeroMinor())) {
            String conjunction = options.conjunction().equals("and") ? labels[1] : options.conjunction();
            String minorWords = pack != null ? pack.spell(minor) : language.equals("en") ? spell(minor) : LocalizedWords.spell(minor, language);
            String minorName = pack != null ? (minor.equals(BigInteger.ONE) ? pack.minorSingular() : pack.minorPlural()) : language.equals("en") ? (minor.equals(BigInteger.ONE) ? currency.minorSingular : currency.minorPlural) : (minor.equals(BigInteger.ONE) ? labels[3] : labels[4]);
            result.append(' ').append(conjunction).append(' ').append(minorWords).append(' ').append(minorName);
        }
        if (options.includeOnly()) result.append(' ').append(labels[2]);
        return result.toString();
    }

    public String normalizeCountryCode(String code) { return SupportedCountries.normalize(code); }

    private String resolveLanguage(String requested) {
        String language = requested.trim().toLowerCase();
        if (language.equals("en") || language.equals("hi") || language.equals("ar") || languagePacks.containsKey(language)) return language;
        return "en";
    }

    private static String spell(BigInteger number) {
        if (number.signum() == 0) return "Zero";
        StringBuilder words = new StringBuilder();
        BigInteger thousand = BigInteger.valueOf(1000);
        int scale = 0;
        while (number.signum() > 0) {
            if (scale >= SCALES.length) throw new IllegalArgumentException("Amount is outside the supported range");
            BigInteger[] parts = number.divideAndRemainder(thousand);
            int chunk = parts[1].intValue();
            if (chunk > 0) {
                String section = underThousand(chunk) + (SCALES[scale].isEmpty() ? "" : " " + SCALES[scale]);
                words.insert(0, words.length() == 0 ? section : section + " ");
            }
            number = parts[0];
            scale++;
        }
        return words.toString();
    }

    private static String underThousand(int number) {
        StringBuilder words = new StringBuilder();
        if (number >= 100) { words.append(ONES[number / 100]).append(" Hundred"); number %= 100; }
        if (number > 0 && words.length() > 0) words.append(' ');
        if (number >= 20) { words.append(TENS[number / 10]); if (number % 10 > 0) words.append(' ').append(ONES[number % 10]); }
        else if (number > 0) words.append(ONES[number]);
        return words.toString();
    }

    private static CurrencyWords currency(String code) {
        CurrencyWords configured = CURRENCIES.get(code);
        if (configured != null) return configured;
        try {
            Currency currency = Currency.getInstance(code);
            int digits = Math.max(currency.getDefaultFractionDigits(), 0);
            String name = currency.getDisplayName(Locale.ENGLISH);
            return new CurrencyWords(name, name, "Minor Unit", "Minor Units", digits);
        } catch (RuntimeException error) { throw new IllegalArgumentException("Unknown ISO currency code: " + code, error); }
    }

    private static String shortCurrencyName(String code, String language, boolean singular, String fallback) {
        Map<String,String[]> names = new HashMap<>();
        if (language.equals("en")) { names.put("INR",new String[]{"Rupee","Rupees"}); names.put("USD",new String[]{"Dollar","Dollars"}); names.put("GBP",new String[]{"Pound","Pounds"}); names.put("EUR",new String[]{"Euro","Euros"}); names.put("AED",new String[]{"Dirham","Dirhams"}); names.put("JPY",new String[]{"Yen","Yen"}); }
        else if (language.equals("hi")) { names.put("INR",new String[]{"रुपया","रुपये"}); names.put("USD",new String[]{"डॉलर","डॉलर"}); names.put("GBP",new String[]{"पाउंड","पाउंड"}); names.put("EUR",new String[]{"यूरो","यूरो"}); names.put("AED",new String[]{"दिरहम","दिरहम"}); names.put("JPY",new String[]{"येन","येन"}); }
        else if (language.equals("ar")) { names.put("INR",new String[]{"روبية","روبية"}); names.put("USD",new String[]{"دولار","دولار"}); names.put("GBP",new String[]{"جنيه","جنيه"}); names.put("EUR",new String[]{"يورو","يورو"}); names.put("AED",new String[]{"درهم","درهم"}); names.put("JPY",new String[]{"ين","ين"}); }
        String[] value = names.get(code);
        return value == null ? fallback : value[singular ? 0 : 1];
    }

    private static Map<String, CurrencyWords> currencies() {
        Map<String, CurrencyWords> map = new HashMap<>();
        map.put("INR", new CurrencyWords("Rupee","Rupees","Paisa","Paise",2));
        map.put("USD", new CurrencyWords("US Dollar","US Dollars","Cent","Cents",2));
        map.put("GBP", new CurrencyWords("Pound","Pounds","Penny","Pence",2));
        map.put("EUR", new CurrencyWords("Euro","Euros","Cent","Cents",2));
        map.put("AED", new CurrencyWords("Dirham","Dirhams","Fil","Fils",2));
        map.put("JPY", new CurrencyWords("Yen","Yen","","",0));
        return map;
    }

    private static final class CurrencyWords {
        final String majorSingular, majorPlural, minorSingular, minorPlural;
        final int fractionDigits;
        CurrencyWords(String a, String b, String c, String d, int digits) {
            majorSingular = a; majorPlural = b; minorSingular = c; minorPlural = d; fractionDigits = digits;
        }
    }
}
