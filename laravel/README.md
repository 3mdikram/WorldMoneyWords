# WorldMoneyWords for Laravel

```bash
composer require worldmoneywords/laravel
```

Laravel auto-discovers the provider and facade:

```php
use AmountToWords\Laravel\Facades\AmountToWords;

echo AmountToWords::convert('1234.50', 'IND', 'INR');
```
