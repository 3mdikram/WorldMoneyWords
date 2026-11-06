import test from 'node:test'; import assert from 'node:assert/strict'; import {amountToWords,POPULAR_COUNTRY_OPTIONS} from '../dist/index.js';
test('converts INR',()=>assert.equal(amountToWords('1234.50','IND','INR'),'One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only'));
test('exports 15 popular country options',()=>assert.equal(POPULAR_COUNTRY_OPTIONS.length,15));
