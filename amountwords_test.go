package amountwords

import "testing"

func TestRootImportCompatibility(t *testing.T) {
	text, err := Convert(1234.50, "IN", "INR")
	if err != nil {
		t.Fatal(err)
	}
	if text != "One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only" {
		t.Fatalf("unexpected text: %q", text)
	}
}

func TestRootOptionsCompatibility(t *testing.T) {
	text, err := Convert(10, "US", "USD", Options{
		IncludeZeroMinor: true,
		Conjunction:      "plus",
	})
	if err != nil {
		t.Fatal(err)
	}
	if text != "Ten US Dollars plus Zero Cents" {
		t.Fatalf("unexpected text: %q", text)
	}
}

func TestRootCountryNameOptionsCompatibility(t *testing.T) {
	withCode, err := Convert(2, "PK", "PKR", Options{IncludeOnly: true, Conjunction: "and", ShortCurrencyName: true})
	if err != nil || withCode != "Two PK Rupees Only" {
		t.Fatalf("got %q, %v", withCode, err)
	}
	withoutCode, err := Convert(2, "PK", "PKR", Options{IncludeOnly: true, Conjunction: "and", IgnoreCountryName: true})
	if err != nil || withoutCode != "Two Rupees Only" {
		t.Fatalf("got %q, %v", withoutCode, err)
	}
}
