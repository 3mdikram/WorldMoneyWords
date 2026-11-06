<?php

declare(strict_types=1);

namespace AmountToWords;

final readonly class ConversionResult implements \JsonSerializable
{
    public function __construct(
        public string $text,
        public string $countryCode,
        public string $currencyCode,
        public string $majorAmount,
        public string $minorAmount,
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
