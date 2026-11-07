# WorldMoneyWords for Java

```xml
<dependency>
  <groupId>io.github.3mdikram</groupId>
  <artifactId>worldmoneywords</artifactId>
  <version>2.2.0</version>
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

Requires Java 11 or newer.
