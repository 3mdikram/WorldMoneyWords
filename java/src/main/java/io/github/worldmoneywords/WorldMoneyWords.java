package io.github.worldmoneywords;

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

    public String convert(String amount, String countryCode, String currencyCode) {
        return convert(amount, countryCode, currencyCode, ConversionOptions.defaults());
    }

    public String convert(String amount, String countryCode, String currencyCode, ConversionOptions options) {
        SupportedCountries.normalize(countryCode);
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
        StringBuilder result = new StringBuilder();
        if (negative && value.signum() != 0) result.append("Minus ");
        result.append(spell(major)).append(' ').append(major.equals(BigInteger.ONE) ? currency.majorSingular : currency.majorPlural);
        if (minor.signum() > 0 || (currency.fractionDigits > 0 && options.includeZeroMinor())) {
            result.append(' ').append(options.conjunction()).append(' ').append(spell(minor)).append(' ')
                .append(minor.equals(BigInteger.ONE) ? currency.minorSingular : currency.minorPlural);
        }
        if (options.includeOnly()) result.append(" Only");
        return result.toString();
    }

    public String normalizeCountryCode(String code) { return SupportedCountries.normalize(code); }

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
