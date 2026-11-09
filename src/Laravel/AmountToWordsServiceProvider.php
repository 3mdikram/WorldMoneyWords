<?php

declare(strict_types=1);

namespace AmountToWords\Laravel;

use AmountToWords\AmountToWords;
use Illuminate\Support\ServiceProvider;

final class AmountToWordsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            AmountToWords::class,
            static fn (): AmountToWords => new AmountToWords(),
        );
        $this->app->alias(AmountToWords::class, 'amount-to-words');
    }
}
