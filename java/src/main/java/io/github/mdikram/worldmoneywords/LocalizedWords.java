package io.github.mdikram.worldmoneywords;

import java.math.BigInteger;
import java.util.Map;

final class LocalizedWords {
    private static final String[] HI = "शून्य|एक|दो|तीन|चार|पाँच|छह|सात|आठ|नौ|दस|ग्यारह|बारह|तेरह|चौदह|पन्द्रह|सोलह|सत्रह|अठारह|उन्नीस|बीस|इक्कीस|बाईस|तेईस|चौबीस|पच्चीस|छब्बीस|सत्ताईस|अट्ठाईस|उनतीस|तीस|इकतीस|बत्तीस|तैंतीस|चौंतीस|पैंतीस|छत्तीस|सैंतीस|अड़तीस|उनतालीस|चालीस|इकतालीस|बयालीस|तैंतालीस|चौवालीस|पैंतालीस|छियालीस|सैंतालीस|अड़तालीस|उनचास|पचास|इक्यावन|बावन|तिरेपन|चौवन|पचपन|छप्पन|सत्तावन|अट्ठावन|उनसठ|साठ|इकसठ|बासठ|तिरेसठ|चौंसठ|पैंसठ|छियासठ|सड़सठ|अड़सठ|उनहत्तर|सत्तर|इकहत्तर|बहत्तर|तिहत्तर|चौहत्तर|पचहत्तर|छिहत्तर|सतहत्तर|अठहत्तर|उनासी|अस्सी|इक्यासी|बयासी|तिरासी|चौरासी|पचासी|छियासी|सत्तासी|अट्ठासी|नवासी|नब्बे|इक्यानबे|बानबे|तिरानबे|चौरानबे|पंचानबे|छियानबे|सत्तानबे|अट्ठानबे|निन्यानबे".split("\\|");
    private static final String[] AR19 = "صفر|واحد|اثنان|ثلاثة|أربعة|خمسة|ستة|سبعة|ثمانية|تسعة|عشرة|أحد عشر|اثنا عشر|ثلاثة عشر|أربعة عشر|خمسة عشر|ستة عشر|سبعة عشر|ثمانية عشر|تسعة عشر".split("\\|");
    private static final String[] ART = {"","","عشرون","ثلاثون","أربعون","خمسون","ستون","سبعون","ثمانون","تسعون"};
    private static final Map<String,Map<String,String>> CURRENCIES = Map.of("hi", Map.of("INR","भारतीय रुपया","USD","अमेरिकी डॉलर","GBP","ब्रिटिश पाउंड","EUR","यूरो","AED","यूएई दिरहम","JPY","जापानी येन"), "ar", Map.of("INR","روبية هندية","USD","دولار أمريكي","GBP","جنيه إسترليني","EUR","يورو","AED","درهم إماراتي","JPY","ين ياباني"));
    static String currency(String code, String language) { return CURRENCIES.get(language).getOrDefault(code, code); }
    static String[] labels(String language) { return language.equals("hi") ? new String[]{"ऋण","और","मात्र","उप-इकाई","उप-इकाइयाँ"} : new String[]{"سالب","و","فقط","وحدة فرعية","وحدات فرعية"}; }
    private static String under100(int n,String l){if(l.equals("hi"))return HI[n];if(n<20)return AR19[n];return n%10==0?ART[n/10]:AR19[n%10]+" و"+ART[n/10];}
    private static String under1000(int n,String l){StringBuilder s=new StringBuilder();if(n>=100){int h=n/100;if(l.equals("hi"))s.append(h==1?"एक":HI[h]).append(" सौ");else s.append(h==1?"مائة":h==2?"مائتان":AR19[h]+" مائة");n%=100;}if(n>0){if(s.length()>0)s.append(l.equals("ar")?" و":" ");s.append(under100(n,l));}return s.toString();}
    static String spell(BigInteger number,String l){if(number.signum()==0)return l.equals("hi")?HI[0]:AR19[0];String[] scales=l.equals("hi")?new String[]{"","हज़ार","मिलियन","बिलियन","ट्रिलियन","क्वाड्रिलियन","क्विंटिलियन"}:new String[]{"","ألف","مليون","مليار","تريليون","كوادريليون","كوينتليون"};StringBuilder out=new StringBuilder();BigInteger thousand=BigInteger.valueOf(1000);int i=0;while(number.signum()>0){BigInteger[] p=number.divideAndRemainder(thousand);int c=p[1].intValue();if(c>0){String part=l.equals("ar")&&c==1&&i>0?"":under1000(c,l);if(!scales[i].isEmpty())part+=(part.isEmpty()?"":" ")+scales[i];out.insert(0,out.length()==0?part:part+(l.equals("ar")?" و":" "));}number=p[0];i++;}return out.toString();}
    private LocalizedWords() {}
}
