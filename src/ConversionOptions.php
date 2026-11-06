<?php

declare(strict_types=1);

namespace AmountToWords;

use InvalidArgumentException;

final readonly class ConversionOptions
{
    public function __construct(
        public bool $includeOnly = true,
        public bool $includeZeroMinor = false,
        public string $conjunction = 'and',
    ) {
        if (trim($this->conjunction) === '') {
            throw new InvalidArgumentException('Conjunction cannot be empty.');
        }
    }
}
