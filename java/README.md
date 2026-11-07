# WorldMoneyWords for Java

```xml
<dependency>
  <groupId>io.github.3mdikram</groupId>
  <artifactId>worldmoneywords</artifactId>
  <version>2.5.0</version>
</dependency>
```

```java
import io.github.mdikram.worldmoneywords.ConversionOptions;
import io.github.mdikram.worldmoneywords.WorldMoneyWords;

WorldMoneyWords converter = new WorldMoneyWords();
String text = converter.convert("1234.50", "IN", "INR");

ConversionOptions options = new ConversionOptions(false, true, "plus");
String custom = converter.convert("10.00", "US", "USD", options);
```

Use `new ConversionOptions(true, false, "and", "hi")` for Hindi or replace `hi` with `ar` for Arabic.
Pass `en`, `hi`, `ar`, or a registered language code explicitly. Unsupported language writers fall back to English.

Use `new ConversionOptions(false, true, "plus", "en", true)` to output a short currency name such as `Dollars` instead of `US Dollars`.

Requires Java 11 or newer.
