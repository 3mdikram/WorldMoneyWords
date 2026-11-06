package amountwords

import "testing"

func TestConvert(t *testing.T) {
	got, err := Convert(1234.50, "IND", "INR")
	want := "One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only"
	if err != nil || got != want {
		t.Fatalf("got %q, %v", got, err)
	}
}
