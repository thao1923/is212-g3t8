package main

import (
	"fmt"
)

func main() {
	//Make a slice of strings with the following valies:
	//"Taylor Swift","Fearless","Speak Now","Red","1989","Reputation","Lover"
	
	//Code is wrong below, fix it.
	albums := []string{"Taylor Swift","Fearless","Speak Now","Red","1989","Reputation","Lover"}
	album := make([]string, 0)
	// album = append(album, "Taylor Swift")
	
	//Now use for loop of your choice to print out each album name.
	for _, s := range albums {
		fmt.Println(s)
	}
}