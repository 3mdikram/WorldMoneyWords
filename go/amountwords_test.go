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
