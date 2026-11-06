package amountwords

import (
	"fmt"
	"math"
	"regexp"
	"strings"
)

type Currency struct {
	MajorSingular, MajorPlural, MinorSingular, MinorPlural string
	FractionDigits                                         int
}

var currencies = map[string]Currency{
	"INR": {"Rupee", "Rupees", "Paisa", "Paise", 2}, "USD": {"US Dollar", "US Dollars", "Cent", "Cents", 2},
	"GBP": {"Pound", "Pounds", "Penny", "Pence", 2}, "EUR": {"Euro", "Euros", "Cent", "Cents", 2},
	"AED": {"Dirham", "Dirhams", "Fil", "Fils", 2}, "JPY": {"Yen", "Yen", "", "", 0},
}
var ones = []string{"Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"}
var tens = []string{"", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"}
var scales = []string{"", "Thousand", "Million", "Billion", "Trillion", "Quadrillion"}
var isoCode = regexp.MustCompile(`^[A-Z]{3}$`)

func underThousand(n int64) string {
	p := []string{}
	if n >= 100 {
		p = append(p, ones[n/100], "Hundred")
		n %= 100
	}
	if n >= 20 {
		p = append(p, tens[n/10])
		if n%10 > 0 {
			p = append(p, ones[n%10])
		}
	} else if n > 0 {
		p = append(p, ones[n])
	}
	return strings.Join(p, " ")
}
func spell(n int64) (string, error) {
	if n == 0 {
		return "Zero", nil
	}
	p := []string{}
	i := 0
	for n > 0 {
		if i >= len(scales) {
			return "", fmt.Errorf("amount outside supported range")
		}
		c := n % 1000
		if c > 0 {
			s := underThousand(c)
			if scales[i] != "" {
				s += " " + scales[i]
			}
			p = append([]string{s}, p...)
		}
		n /= 1000
		i++
	}
	return strings.Join(p, " "), nil
}

func Convert(amount float64, countryCode, currencyCode string) (string, error) {
	country, code := strings.ToUpper(strings.TrimSpace(countryCode)), strings.ToUpper(strings.TrimSpace(currencyCode))
	if _, ok := isoCountries[country]; !ok {
		return "", fmt.Errorf("unknown ISO alpha-3 country code")
	}
	if !isoCode.MatchString(code) {
		return "", fmt.Errorf("currency code must be ISO 4217")
	}
	c, ok := currencies[code]
	if !ok {
		c = Currency{code, code, "Minor Unit", "Minor Units", 2}
	}
	factor := math.Pow10(c.FractionDigits)
	rounded := math.Round(amount*factor) / factor
	negative := rounded < 0
	rounded = math.Abs(rounded)
	major := int64(rounded)
	minor := int64(math.Round((rounded - float64(major)) * factor))
	majorWords, err := spell(major)
	if err != nil {
		return "", err
	}
	p := []string{}
	if negative {
		p = append(p, "Minus")
	}
	majorName := c.MajorPlural
	if major == 1 {
		majorName = c.MajorSingular
	}
	p = append(p, majorWords, majorName)
	if minor > 0 {
		minorWords, _ := spell(minor)
		minorName := c.MinorPlural
		if minor == 1 {
			minorName = c.MinorSingular
		}
		p = append(p, "and", minorWords, minorName)
	}
	return strings.Join(append(p, "Only"), " "), nil
}
