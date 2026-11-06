package main

import (
	"fmt"
	"log"

	amountwords "github.com/3mdikram/WorldMoneyWords/go/v2"
)

func main() {
	basic, err := amountwords.Convert(1234.50, "IN", "INR")
	if err != nil {
		log.Fatal(err)
	}
	fmt.Println(basic)

	advanced, err := amountwords.Convert(10, "US", "USD", amountwords.Options{
		IncludeOnly:      false,
		IncludeZeroMinor: true,
		Conjunction:      "plus",
	})
	if err != nil {
		log.Fatal(err)
	}
	fmt.Println(advanced)
}
