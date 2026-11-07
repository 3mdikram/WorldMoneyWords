package amountwords

import "testing"

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
