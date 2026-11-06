package io.github.worldmoneywords.example;

import io.github.worldmoneywords.ConversionOptions;
import io.github.worldmoneywords.WorldMoneyWords;

public final class BasicExample {
    public static void main(String[] args) {
        WorldMoneyWords converter = new WorldMoneyWords();
        System.out.println(converter.convert("1234.50", "IN", "INR"));
        System.out.println(converter.convert("10.00", "US", "USD", new ConversionOptions(false, true, "plus")));
    }
}
