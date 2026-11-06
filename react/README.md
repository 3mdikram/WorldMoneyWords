# WorldMoneyWords for React and JavaScript

```tsx
import { amountToWords } from '@worldmoneywords/core';

const text = amountToWords('1234.50', 'IND', 'INR');
```

The package has TypeScript declarations and works in React, browsers, and Node.js. It has no React runtime dependency because the converter is a plain function that can be called from any component.
