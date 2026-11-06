# WorldMoneyWords for React and JavaScript

```bash
npm install @3mdikram/worldmoneywords
```

```tsx
import { amountToWords } from '@3mdikram/worldmoneywords';

const text = amountToWords('1234.50', 'IN', 'INR');
```

Pass an optional fourth object with `includeOnly`, `includeZeroMinor`, and `conjunction` properties.

The country argument accepts ISO 3166-1 Alpha-2 or Alpha-3 codes. The package has TypeScript declarations and works in React, browsers, and Node.js. It has no React runtime dependency because the converter is a plain function that can be called from any component.
