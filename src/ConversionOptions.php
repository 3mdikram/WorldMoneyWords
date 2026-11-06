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
    ) {
        if (trim($this->conjunction) === '') {
            throw new InvalidArgumentException('Conjunction cannot be empty.');
        }
    }
}
