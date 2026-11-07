package amountwords

import (
	"fmt"
	"strings"
	"testing"
)

func TestConvert(t *testing.T) {
	got, err := Convert(1234.50, "IND", "INR")
	want := "One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only"
	if err != nil || got != want {
		t.Fatalf("got %q, %v", got, err)
	}
}

func TestAlpha2Country(t *testing.T) {
	got, err := Convert(10, "IN", "INR")
	if err != nil || got != "Ten Rupees Only" {
		t.Fatalf("got %q, %v", got, err)
	}
}

func TestMinorTerritoryIsNotSupported(t *testing.T) {
	if _, err := Convert(10, "AI", "USD"); err == nil {
		t.Fatal("expected unsupported country error")
	}
}

func TestOptions(t *testing.T) {
	got, err := Convert(10, "US", "USD", Options{IncludeZeroMinor: true, Conjunction: "plus"})
	if err != nil || got != "Ten US Dollars plus Zero Cents" {
		t.Fatalf("got %q, %v", got, err)
	}
}

func TestShortCurrencyNameOption(t *testing.T) {
	got, err := Convert(10, "US", "USD", Options{IncludeZeroMinor: true, Conjunction: "plus", ShortCurrencyName: true})
	if err != nil || got != "Ten Dollars plus Zero Cents" {
		t.Fatalf("got %q, %v", got, err)
	}
}

func TestHindiAndArabic(t *testing.T) {
	hi, err := Convert(1234.50, "IN", "INR", Options{IncludeOnly: true, Language: "hi", Conjunction: "and"})
	if err != nil || hi != "एक हज़ार दो सौ चौंतीस भारतीय रुपया और पचास उप-इकाइयाँ मात्र" {
		t.Fatalf("got %q, %v", hi, err)
	}
	ar, err := Convert(1234.50, "US", "USD", Options{IncludeOnly: true, Language: "ar", Conjunction: "and"})
	if err != nil || ar != "ألف ومائتان وأربعة وثلاثون دولار أمريكي و خمسون وحدات فرعية فقط" {
		t.Fatalf("got %q, %v", ar, err)
	}
}

func TestAllLanguagesWorkForAll200Countries(t *testing.T) {
	if len(isoAlpha2To3) != 200 {
		t.Fatalf("got %d countries", len(isoAlpha2To3))
	}
	for country := range isoAlpha2To3 {
		for _, language := range []string{"en", "hi", "ar"} {
			text, err := Convert(42.50, country, "USD", Options{IncludeOnly: true, Conjunction: "and", Language: language})
			if err != nil || text == "" {
				t.Fatalf("%s/%s: %q, %v", country, language, text, err)
			}
		}
	}
}

func TestUnavailableLanguageFallsBackToEnglish(t *testing.T) {
	cases := []struct{ country, language, prefix string }{{"PK", "ur", "Ten"}}
	for _, c := range cases {
		text, err := Convert(10, c.country, "USD", Options{IncludeOnly: true, Conjunction: "and", Language: c.language})
		if err != nil || !strings.HasPrefix(text, c.prefix) {
			t.Fatalf("%+v: %q, %v", c, text, err)
		}
	}
}

func TestCustomOfficialLanguagePack(t *testing.T) {
	err := RegisterLanguagePack(LanguagePack{Code: "xx", Minus: "negative", Conjunction: "with", Only: "complete", MinorSingular: "part", MinorPlural: "parts", Spell: func(n int64) (string, error) { return fmt.Sprintf("number-%d", n), nil }, CurrencyName: func(code string, singular bool) string {
		suffix := "-units"
		if singular {
			suffix = "-unit"
		}
		return strings.ToLower(code) + suffix
	}})
	if err != nil {
		t.Fatal(err)
	}
	text, err := Convert(2.50, "US", "USD", Options{IncludeOnly: true, Conjunction: "and", Language: "xx"})
	if err != nil || text != "number-2 usd-units with number-50 parts complete" {
		t.Fatalf("%q %v", text, err)
	}
}
