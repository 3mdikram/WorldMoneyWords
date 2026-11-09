<?php

declare(strict_types=1);

namespace mdikram\Facades;

use Illuminate\Support\Facades\Facade;

/** Laravel facade for the WorldMoneyWords converter. */
final class AmountToWords extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'amount-to-words';
    }
}
