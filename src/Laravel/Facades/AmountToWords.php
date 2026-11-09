<?php

declare(strict_types=1);

namespace AmountToWords\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

final class AmountToWords extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'amount-to-words';
    }
}
