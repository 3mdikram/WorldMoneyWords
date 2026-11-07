<?php

declare(strict_types=1);

namespace AmountToWords;

/** Implement this contract to add an official language without changing the converter. */
interface LanguagePack
{
    public function code(): string;

    public function spell(string $number): string;

    /** @return array{minus: string, conjunction: string, only: string, minorSingular: string, minorPlural: string} */
    public function labels(): array;

    public function currencyName(string $currencyCode, bool $singular): string;
}
