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
    ) {
        if (trim($this->conjunction) === '') {
            throw new InvalidArgumentException('Conjunction cannot be empty.');
        }
        if (!in_array(strtolower($this->language), ['en', 'hi', 'ar'], true)) {
            throw new InvalidArgumentException('Language must be en, hi, or ar.');
        }
    }
}
