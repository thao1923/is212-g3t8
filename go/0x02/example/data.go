package main

import (
    "strconv"
)

type MyFirstStruct struct {
    Name string `json:"name"`
    Age int `json:"age"`
    Note ChildStruct `json:"notes"`
}

type ChildStruct struct {
    Info string `json:"info"`
}

func GetString(name string,age string) *MyFirstStruct{
    intAge,_ := strconv.Atoi(age)
    var tag *MyFirstStruct
    tag = &MyFirstStruct{}
    tag.Name = name
    tag.Age = intAge
    tag.Note = ChildStruct{Info: "He was there but not there."}
    return tag
}

func ByeString(name string) *MyFirstStruct{
    var tag *MyFirstStruct
    tag = &MyFirstStruct{}
    tag.Name = name
    tag.Age = 999
    tag.Note = ChildStruct{Info: "He was there but not there."}
    return tag
}