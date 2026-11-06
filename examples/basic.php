<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use AmountToWords\AmountToWords;

$converter = new AmountToWords();

foreach ([
    ['1234.50', 'IND', 'INR'],
    ['99.95', 'USA', 'USD'],
    ['42', 'GBR', 'GBP'],
    ['2.50', 'BRA', 'BRL'],
] as [$amount, $country, $currency]) {
    echo $converter->convert($amount, $country, $currency) . PHP_EOL;
}
