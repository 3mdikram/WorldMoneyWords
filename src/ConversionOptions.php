<?php

declare(strict_types=1);

namespace AmountToWords;

use InvalidArgumentException;

final class ConversionOptions
{
    public function __construct(
        public readonly bool $includeOnly = true,
        public readonly bool $includeZeroMinor = false,
        public readonly string $conjunction = 'and',
        public readonly string $language = 'en',
        public readonly bool $shortCurrencyName = false,
        public readonly bool $ignoreCountryName = false,
    ) {
        if (trim($this->conjunction) === '') {
            throw new InvalidArgumentException('Conjunction cannot be empty.');
        }
        if (trim($this->language) === '') {
            throw new InvalidArgumentException('Language cannot be empty.');
        }
    }
}
