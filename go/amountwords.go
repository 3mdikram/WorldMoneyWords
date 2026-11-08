package amountwords

import (
	"fmt"
	"math"
	"regexp"
	"strings"
	"sync"
)

type Currency struct {
	MajorSingular, MajorPlural, MinorSingular, MinorPlural string
	FractionDigits                                         int
}

type Options struct {
	IncludeOnly       bool
	IncludeZeroMinor  bool
	Conjunction       string
	Language          string
	ShortCurrencyName bool
}

type LanguagePack struct {
	Code, Minus, Conjunction, Only, MinorSingular, MinorPlural string
	Spell                                                      func(int64) (string, error)
	CurrencyName                                               func(string, bool) string
}

var languagePacks = map[string]LanguagePack{}
var languagePacksMu sync.RWMutex

var isoCurrencies = func() map[string]struct{} {
	codes := strings.Fields("AED AFN ALL AMD ANG AOA ARS AUD AWG AZN BAM BBD BDT BGN BHD BIF BMD BND BOB BOV BRL BSD BTN BWP BYN BZD CAD CDF CHE CHF CHW CLF CLP CNY COP COU CRC CUC CUP CVE CZK DJF DKK DOP DZD EGP ERN ETB EUR FJD FKP GBP GEL GHS GIP GMD GNF GTQ GYD HKD HNL HTG HUF IDR ILS INR IQD IRR ISK JMD JOD JPY KES KGS KHR KMF KPW KRW KWD KYD KZT LAK LBP LKR LRD LSL LYD MAD MDL MGA MKD MMK MNT MOP MRU MUR MVR MWK MXN MXV MYR MZN NAD NGN NIO NOK NPR NZD OMR PAB PEN PGK PHP PKR PLN PYG QAR RON RSD RUB RWF SAR SBD SCR SDG SEK SGD SHP SLE SLL SOS SRD SSP STN SVC SYP SZL THB TJS TMT TND TOP TRY TTD TWD TZS UAH UGX USD USN UYI UYU UYW UZS VED VES VND VUV WST XAF XAG XAU XBA XBB XBC XBD XCD XDR XOF XPD XPF XPT XSU XTS XUA XXX YER ZAR ZMW ZWL")
	result := make(map[string]struct{}, len(codes))
	for _, code := range codes {
		result[code] = struct{}{}
	}
	return result
}()

func RegisterLanguagePack(pack LanguagePack) error {
	code := strings.ToLower(strings.TrimSpace(pack.Code))
	valid := regexp.MustCompile(`^[a-z]{2,8}(?:-[a-z0-9]{2,8})*$`)
	if !valid.MatchString(code) {
		return fmt.Errorf("language pack code must be a valid language tag")
	}
	if pack.Spell == nil || pack.CurrencyName == nil || strings.TrimSpace(pack.Minus) == "" || strings.TrimSpace(pack.Conjunction) == "" || strings.TrimSpace(pack.Only) == "" || strings.TrimSpace(pack.MinorSingular) == "" || strings.TrimSpace(pack.MinorPlural) == "" {
		return fmt.Errorf("language pack functions and labels are required")
	}
	pack.Code = code
	languagePacksMu.Lock()
	defer languagePacksMu.Unlock()
	languagePacks[code] = pack
	return nil
}

func DefaultOptions() Options { return Options{IncludeOnly: true, Conjunction: "and", Language: "en"} }

var currencies = map[string]Currency{
	"INR": {"Rupee", "Rupees", "Paisa", "Paise", 2}, "USD": {"US Dollar", "US Dollars", "Cent", "Cents", 2},
	"GBP": {"Pound", "Pounds", "Penny", "Pence", 2}, "EUR": {"Euro", "Euros", "Cent", "Cents", 2},
	"AED": {"Dirham", "Dirhams", "Fil", "Fils", 2}, "JPY": {"Yen", "Yen", "", "", 0},
}
var ones = []string{"Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"}
var tens = []string{"", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"}
var scales = []string{"", "Thousand", "Million", "Billion", "Trillion", "Quadrillion"}
var isoCode = regexp.MustCompile(`^[A-Z]{3}$`)
var hindi = strings.Split("शून्य|एक|दो|तीन|चार|पाँच|छह|सात|आठ|नौ|दस|ग्यारह|बारह|तेरह|चौदह|पन्द्रह|सोलह|सत्रह|अठारह|उन्नीस|बीस|इक्कीस|बाईस|तेईस|चौबीस|पच्चीस|छब्बीस|सत्ताईस|अट्ठाईस|उनतीस|तीस|इकतीस|बत्तीस|तैंतीस|चौंतीस|पैंतीस|छत्तीस|सैंतीस|अड़तीस|उनतालीस|चालीस|इकतालीस|बयालीस|तैंतालीस|चौवालीस|पैंतालीस|छियालीस|सैंतालीस|अड़तालीस|उनचास|पचास|इक्यावन|बावन|तिरेपन|चौवन|पचपन|छप्पन|सत्तावन|अट्ठावन|उनसठ|साठ|इकसठ|बासठ|तिरेसठ|चौंसठ|पैंसठ|छियासठ|सड़सठ|अड़सठ|उनहत्तर|सत्तर|इकहत्तर|बहत्तर|तिहत्तर|चौहत्तर|पचहत्तर|छिहत्तर|सतहत्तर|अठहत्तर|उनासी|अस्सी|इक्यासी|बयासी|तिरासी|चौरासी|पचासी|छियासी|सत्तासी|अट्ठासी|नवासी|नब्बे|इक्यानबे|बानबे|तिरानबे|चौरानबे|पंचानबे|छियानबे|सत्तानबे|अट्ठानबे|निन्यानबे", "|")
var arabic19 = strings.Split("صفر|واحد|اثنان|ثلاثة|أربعة|خمسة|ستة|سبعة|ثمانية|تسعة|عشرة|أحد عشر|اثنا عشر|ثلاثة عشر|أربعة عشر|خمسة عشر|ستة عشر|سبعة عشر|ثمانية عشر|تسعة عشر", "|")
var arabicTens = []string{"", "", "عشرون", "ثلاثون", "أربعون", "خمسون", "ستون", "سبعون", "ثمانون", "تسعون"}
var localizedCurrencies = map[string]map[string]string{"hi": {"INR": "भारतीय रुपया", "USD": "अमेरिकी डॉलर", "GBP": "ब्रिटिश पाउंड", "EUR": "यूरो", "AED": "यूएई दिरहम", "JPY": "जापानी येन"}, "ar": {"INR": "روبية هندية", "USD": "دولار أمريكي", "GBP": "جنيه إسترليني", "EUR": "يورو", "AED": "درهم إماراتي", "JPY": "ين ياباني"}}
var shortCurrencies = map[string]map[string][2]string{"en": {"INR": {"Rupee", "Rupees"}, "USD": {"Dollar", "Dollars"}, "GBP": {"Pound", "Pounds"}, "EUR": {"Euro", "Euros"}, "AED": {"Dirham", "Dirhams"}, "JPY": {"Yen", "Yen"}}, "hi": {"INR": {"रुपया", "रुपये"}, "USD": {"डॉलर", "डॉलर"}, "GBP": {"पाउंड", "पाउंड"}, "EUR": {"यूरो", "यूरो"}, "AED": {"दिरहम", "दिरहम"}, "JPY": {"येन", "येन"}}, "ar": {"INR": {"روبية", "روبية"}, "USD": {"دولار", "دولار"}, "GBP": {"جنيه", "جنيه"}, "EUR": {"يورو", "يورو"}, "AED": {"درهم", "درهم"}, "JPY": {"ين", "ين"}}}

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

func localizedUnder100(n int64, language string) string {
	if language == "hi" {
		return hindi[n]
	}
	if n < 20 {
		return arabic19[n]
	}
	if n%10 == 0 {
		return arabicTens[n/10]
	}
	return arabic19[n%10] + " و" + arabicTens[n/10]
}
func localizedUnder1000(n int64, language string) string {
	p := []string{}
	if n >= 100 {
		h := n / 100
		if language == "hi" {
			one := hindi[h]
			if h == 1 {
				one = "एक"
			}
			p = append(p, one+" सौ")
		} else if h == 1 {
			p = append(p, "مائة")
		} else if h == 2 {
			p = append(p, "مائتان")
		} else {
			p = append(p, arabic19[h]+" مائة")
		}
		n %= 100
	}
	if n > 0 {
		p = append(p, localizedUnder100(n, language))
	}
	separator := " "
	if language == "ar" {
		separator = " و"
	}
	return strings.Join(p, separator)
}
func localizedSpell(n int64, language string) (string, error) {
	if language == "en" {
		return spell(n)
	}
	if n == 0 {
		if language == "hi" {
			return hindi[0], nil
		}
		return arabic19[0], nil
	}
	var ss []string
	if language == "hi" {
		ss = []string{"", "हज़ार", "मिलियन", "बिलियन", "ट्रिलियन", "क्वाड्रिलियन"}
	} else {
		ss = []string{"", "ألف", "مليون", "مليار", "تريليون", "كوادريليون"}
	}
	p := []string{}
	i := 0
	for n > 0 {
		if i >= len(ss) {
			return "", fmt.Errorf("amount outside supported range")
		}
		c := n % 1000
		if c > 0 {
			s := ""
			if !(language == "ar" && c == 1 && i > 0) {
				s = localizedUnder1000(c, language)
			}
			if ss[i] != "" {
				if s != "" {
					s += " "
				}
				s += ss[i]
			}
			p = append([]string{s}, p...)
		}
		n /= 1000
		i++
	}
	separator := " "
	if language == "ar" {
		separator = " و"
	}
	return strings.Join(p, separator), nil
}

func Convert(amount float64, countryCode, currencyCode string, supplied ...Options) (string, error) {
	if math.IsNaN(amount) || math.IsInf(amount, 0) {
		return "", fmt.Errorf("amount must be finite")
	}
	options := DefaultOptions()
	if len(supplied) > 0 {
		options = supplied[0]
		if options.Conjunction == "" {
			options.Conjunction = "and"
		}
	}
	if options.Language == "" {
		options.Language = "en"
	}
	country, code := strings.ToUpper(strings.TrimSpace(countryCode)), strings.ToUpper(strings.TrimSpace(currencyCode))
	if alpha3, ok := isoAlpha2To3[country]; ok {
		country = alpha3
	}
	if _, ok := isoCountries[country]; !ok {
		return "", fmt.Errorf("unknown ISO Alpha-2 or Alpha-3 country code")
	}
	requested := strings.ToLower(strings.TrimSpace(options.Language))
	languagePacksMu.RLock()
	requestedPack, requestedCustom := languagePacks[requested]
	languagePacksMu.RUnlock()
	if requested == "en" || requested == "hi" || requested == "ar" || requestedCustom {
		options.Language = requested
	} else {
		options.Language = "en"
	}
	if !isoCode.MatchString(code) {
		return "", fmt.Errorf("currency code must be ISO 4217")
	}
	if _, known := isoCurrencies[code]; !known {
		return "", fmt.Errorf("unknown ISO 4217 currency code: %s", code)
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
	pack, custom := requestedPack, requestedCustom
	if options.Language != requested {
		pack, custom = LanguagePack{}, false
	}
	majorWords, err := localizedSpell(major, options.Language)
	if custom {
		majorWords, err = pack.Spell(major)
	}
	if err != nil {
		return "", err
	}
	p := []string{}
	labels := map[string][]string{"en": {"Minus", "and", "Only", "Minor Unit", "Minor Units"}, "hi": {"ऋण", "और", "मात्र", "उप-इकाई", "उप-इकाइयाँ"}, "ar": {"سالب", "و", "فقط", "وحدة فرعية", "وحدات فرعية"}}[options.Language]
	if custom {
		labels = []string{pack.Minus, pack.Conjunction, pack.Only, pack.MinorSingular, pack.MinorPlural}
	}
	if negative {
		p = append(p, labels[0])
	}
	majorName := c.MajorPlural
	if major == 1 {
		majorName = c.MajorSingular
	}
	if custom {
		majorName = pack.CurrencyName(code, major == 1)
	} else if options.Language != "en" {
		if name, ok := localizedCurrencies[options.Language][code]; ok {
			majorName = name
		} else {
			majorName = code
		}
	}
	if options.ShortCurrencyName {
		if names, ok := shortCurrencies[options.Language][code]; ok {
			if major == 1 {
				majorName = names[0]
			} else {
				majorName = names[1]
			}
		}
	}
	p = append(p, majorWords, majorName)
	if minor > 0 || (c.FractionDigits > 0 && options.IncludeZeroMinor) {
		minorWords, _ := localizedSpell(minor, options.Language)
		if custom {
			minorWords, _ = pack.Spell(minor)
		}
		minorName := c.MinorPlural
		if minor == 1 {
			minorName = c.MinorSingular
		}
		if custom {
			if minor == 1 {
				minorName = pack.MinorSingular
			} else {
				minorName = pack.MinorPlural
			}
		} else if options.Language != "en" {
			if minor == 1 {
				minorName = labels[3]
			} else {
				minorName = labels[4]
			}
		}
		conjunction := options.Conjunction
		if conjunction == "and" {
			conjunction = labels[1]
		}
		p = append(p, conjunction, minorWords, minorName)
	}
	if options.IncludeOnly {
		p = append(p, labels[2])
	}
	return strings.Join(p, " "), nil
}
