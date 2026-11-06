<?php

declare(strict_types=1);

namespace AmountToWords;

final class ConversionResult implements \JsonSerializable
{
    public function __construct(
        public readonly string $text,
        public readonly string $countryCode,
        public readonly string $currencyCode,
        public readonly string $majorAmount,
        public readonly string $minorAmount,
    ) {
    }

    /** @return array{text: string, countryCode: string, currencyCode: string, majorAmount: string, minorAmount: string} */
    public function jsonSerialize(): array
    {
        return [
            'text' => $this->text,
            'countryCode' => $this->countryCode,
            'currencyCode' => $this->currencyCode,
            'majorAmount' => $this->majorAmount,
            'minorAmount' => $this->minorAmount,
        ];
    }
}
