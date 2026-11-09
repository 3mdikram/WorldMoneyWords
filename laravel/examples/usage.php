<?php

declare(strict_types=1);

use AmountToWords\ConversionOptions;
use mdikram\Facades\AmountToWords;

// Basic facade usage.
echo AmountToWords::convert('1234.50', 'IN', 'INR');

// Advanced options through the same facade method.
$options = new ConversionOptions(
    includeOnly: false,
    includeZeroMinor: true,
    conjunction: 'plus',
);

echo AmountToWords::convert('10.00', 'US', 'USD', $options);
