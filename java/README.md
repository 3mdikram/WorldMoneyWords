# WorldMoneyWords for Java

```xml
<dependency>
  <groupId>io.github.3mdikram</groupId>
  <artifactId>worldmoneywords</artifactId>
  <version>2.4.0</version>
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
Use `auto` for country-aware selection. Unsupported local language writers fall back to English.

Requires Java 11 or newer.
