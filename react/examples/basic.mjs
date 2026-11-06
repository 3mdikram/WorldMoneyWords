import { amountToWords } from '../dist/index.js';

// Basic conversion with an Alpha-2 country code.
console.log(amountToWords('1234.50', 'IN', 'INR'));

// Advanced conversion options.
console.log(amountToWords('10.00', 'US', 'USD', {
  includeOnly: false,
  includeZeroMinor: true,
  conjunction: 'plus',
}));
