# Contributing

Install PHP 8.1 or newer, Composer, and the PHP `intl` extension. Then run:

```bash
composer install
composer check
```

When adding a currency definition, include tests for its singular, plural, minor-unit, and fraction-digit behavior. Country parameters must use ISO 3166-1 alpha-3 codes and currencies must use ISO 4217 codes.
