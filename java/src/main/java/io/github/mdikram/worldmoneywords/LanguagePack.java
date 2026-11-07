package io.github.mdikram.worldmoneywords;

import java.math.BigInteger;

public interface LanguagePack {
    String code();
    String spell(BigInteger number);
    String currencyName(String currencyCode, boolean singular);
    String minus();
    String conjunction();
    String only();
    String minorSingular();
    String minorPlural();
}
