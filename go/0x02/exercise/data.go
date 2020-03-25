
package main

type MessageStruct struct {
    Message string `json:"message"`
}


func GetMessage() *MessageStruct{
    var tag *MessageStruct
    tag = &MessageStruct{}
    tag.Message = "People throw rock at things that shine"
    return tag
}

func GetString(name string) *MyFirstStruct{
    var tag *MyFirstStruct
    tag = &MyFirstStruct{}
    tag.Name = name
    return tag
}