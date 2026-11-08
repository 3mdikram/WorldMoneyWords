# Changelog

All notable changes are documented here. This project follows semantic versioning.

## Unreleased

This section is intentionally unreleased. Package versions and release tags have not been changed.

### Added

- Unreleased Android AAR with a Kotlin-friendly, exact-decimal-string API and API 21+ support.
- Cross-runtime CI for PHP, Laravel, Python, Node.js, Go, and Java.
- Shared conformance fixtures for common conversion and validation behavior.
- Exact decimal-string arithmetic for the TypeScript runtime.
- Regression coverage for malformed grouping, unknown currencies, and large exact values.

### Changed

- Strengthened currency and amount validation across runtimes.
- Improved repository hygiene by ignoring Java build output.

### Security

- Added race testing for the Go package and stricter numeric parsing for financial input.
