// Package amountwords provides the root import-path compatibility API for
// WorldMoneyWords. New v2 applications may continue to import the versioned
// github.com/3mdikram/WorldMoneyWords/go/v2 module directly.
package amountwords

import v2 "github.com/3mdikram/WorldMoneyWords/go/v2"

// Currency describes the names and decimal precision of a monetary unit.
type Currency = v2.Currency

// Options controls the generated wording.
type Options = v2.Options

// LanguagePack provides reviewed number and currency wording for a language.
type LanguagePack = v2.LanguagePack

// DefaultOptions returns the standard English financial wording options.
func DefaultOptions() Options {
	return v2.DefaultOptions()
}

// RegisterLanguagePack adds a validated language pack to the shared engine.
func RegisterLanguagePack(pack LanguagePack) error {
	return v2.RegisterLanguagePack(pack)
}

// Convert spells a monetary amount using the supplied country and currency.
func Convert(amount float64, countryCode, currencyCode string, options ...Options) (string, error) {
	return v2.Convert(amount, countryCode, currencyCode, options...)
}
