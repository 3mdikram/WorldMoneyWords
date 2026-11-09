# Changelog

All notable changes are documented here. This project follows semantic versioning.

## Unreleased

This section is intentionally unreleased. Package versions and release tags have not been changed.

### Added

- Added the PHP/Laravel `ignoreCountryName` option as an explicit way to remove country qualifiers from bundled currency names.
- Added the Laravel facade import `mdikram\Facades\AmountToWords`, while retaining the previous namespace for backward compatibility.
- Unreleased root Go compatibility module for `github.com/3mdikram/WorldMoneyWords`, forwarding to the maintained `/go/v2` engine.
- Unreleased Android AAR with a Kotlin-friendly, exact-decimal-string API and API 21+ support.
- Cross-runtime CI for PHP, Laravel, Python, Node.js, Go, and Java.
- Shared conformance fixtures for common conversion and validation behavior.
- Exact decimal-string arithmetic for the TypeScript runtime.
- Regression coverage for malformed grouping, unknown currencies, and large exact values.

### Changed

- Standardized `shortCurrencyName` and `ignoreCountryName` across PHP/Laravel, Python, Go v2, Java, TypeScript/React/Node, and Android/Kotlin.
- Changed PHP/Laravel `shortCurrencyName: true` to prefix the short Alpha-2 country code (`PK Rupees`) for bundled and ICU-backed currencies; `ignoreCountryName: true` continues to return `Rupees` without a country prefix.
- Bundled the Laravel service provider and facade with the main Composer package so `3mdikram/worldmoneywords` supports Laravel auto-discovery directly.
- Removed the obsolete separate Laravel Composer manifest; one package now supports both plain PHP and Laravel.
- Strengthened currency and amount validation across runtimes.

### Fixed

- Expanded short currency names across every bundled PHP/Laravel currency, including `Pakistani Rupees` → `Rupees` and `Australian Dollars` → `Dollars`.
- Fixed `Class "AmountToWords\\Laravel\\Facades\\AmountToWords" not found` caused by documentation pointing to an unpublished integration package and missing root-package autoload metadata.
- Improved repository hygiene by ignoring Java build output.

### Security

- Added race testing for the Go package and stricter numeric parsing for financial input.
