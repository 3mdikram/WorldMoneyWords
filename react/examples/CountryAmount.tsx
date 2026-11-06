import { useState } from 'react';
import { amountToWords } from '@3mdikram/worldmoneywords';

export function CountryAmount() {
  const [amount, setAmount] = useState('1234.50');
  const [country, setCountry] = useState('IN');
  const [currency, setCurrency] = useState('INR');

  return (
    <section>
      <input value={amount} onChange={event => setAmount(event.target.value)} />
      <input value={country} onChange={event => setCountry(event.target.value)} maxLength={3} />
      <input value={currency} onChange={event => setCurrency(event.target.value)} maxLength={3} />
      <p>{amountToWords(amount, country, currency)}</p>
    </section>
  );
}
