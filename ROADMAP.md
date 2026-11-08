# WorldMoneyWords roadmap

Package versions remain unchanged while the work below is in the **Unreleased** section of the changelog.

## Reliability baseline

- [x] Test every maintained runtime in continuous integration.
- [x] Add shared conversion and validation fixtures.
- [x] Preserve exact decimal strings in PHP, Python, Java, and TypeScript.
- [x] Reject unknown ISO currency codes consistently in portable runtimes.
- [x] Validate Java thousands grouping.
- [x] Protect the Go language-pack registry from data races.
- [ ] Add an exact decimal-string Go API without breaking `Convert(float64, ...)`.
- [ ] Make every runtime consume the shared fixtures directly.

## Cross-runtime API parity

- [x] Add a root Go compatibility module while preserving the versioned `/go/v2` module.
- [ ] Publish the root Go module with a deliberate `v1.x` compatibility tag.
- [x] Add an unreleased Android AAR backed by the Java conversion engine.
- [ ] Publish the Android AAR to Maven Central after release readiness review.
- [ ] Structured conversion results in Python, Go, Java, and TypeScript.
- [ ] Batch conversion in Python, Go, Java, and TypeScript.
- [ ] Country, currency, and language discovery APIs in every runtime.
- [ ] Immutable custom-currency registration in every runtime.
- [ ] Stable, documented error categories in every runtime.

## Language quality

- [ ] Add Indian lakh/crore numbering as an explicit numbering-system option.
- [ ] Review Hindi grammar and terminology with native speakers.
- [ ] Add Arabic gender, dual, plural, and scale agreement rules.
- [ ] Add reviewed Urdu and Bengali language packs.
- [ ] Require golden tests and linguistic review for new official packs.

## Product features

- [ ] Configurable rounding modes and strict excess-decimal rejection.
- [ ] Cheque, invoice, legal-uppercase, and casing output styles.
- [ ] Country-to-default-currency convenience lookup.
- [ ] Command-line interface.
- [ ] OpenAPI example service and Postman collection.
- [ ] Interactive browser playground.

Items remain unreleased until their package manifests, changelog entries, documentation, and release tags are deliberately updated together.
