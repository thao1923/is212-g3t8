package main

import (
    "net/http"
    "github.com/gorilla/mux"
    "encoding/json"
)

func Swifty(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type", "application/json")
    output := GetMessage()
    json.NewEncoder(w).Encode(output)
}

func Message(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type", "application/json")
    output := GetMessage()
    json.NewEncoder(w).Encode(output)
}


func ServeService() http.Handler {

	router := mux.NewRouter()
	router.HandleFunc("/swifty", Swifty).Methods("GET", "OPTIONS")
	router.HandleFunc("/message", Message).Methods("GET", "OPTIONS")
	return router
}