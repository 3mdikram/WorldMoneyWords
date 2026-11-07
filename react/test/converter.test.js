import test from 'node:test'; import assert from 'node:assert/strict'; import {amountToWords} from '../dist/index.js';
test('converts INR',()=>assert.equal(amountToWords('1234.50','IND','INR'),'One Thousand Two Hundred Thirty Four Rupees and Fifty Paise Only'));
test('accepts Alpha-2 country codes',()=>assert.equal(amountToWords('10','IN','INR'),'Ten Rupees Only'));
test('rejects minor territories outside the 200-country list',()=>assert.throws(()=>amountToWords('10','AI','USD')));
test('supports conversion options',()=>assert.equal(amountToWords('10','US','USD',{includeOnly:false,includeZeroMinor:true,conjunction:'plus'}),'Ten US Dollars plus Zero Cents'));
test('supports Hindi and Arabic',()=>{assert.equal(amountToWords('1234.50','IN','INR',{language:'hi'}),'एक हज़ार दो सौ चौंतीस भारतीय रुपया और पचास उप-इकाइयाँ मात्र');assert.equal(amountToWords('1234.50','US','USD',{language:'ar'}),'ألف ومائتان وأربعة وثلاثون دولار أمريكي و خمسون وحدات فرعية فقط')});
