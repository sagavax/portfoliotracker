const filter_ticker_alphabet = document.querySelector(".filter_ticker_alphabet");
const search_container = document.querySelector(".search-container");



search_container.addEventListener("click", function(event) {
    if (event.target.tagName === "BUTTON") {
        if(event.target.getAttribute("data-ticker") === "add_ticker") {
            // Handle add ticker button click
            const tickerInput = document.querySelector(".search-container input");
            if (tickerInput.value == "") {
                alert("Please enter a ticker symbol.");
                return;
            } else {
                modalTicker.showModal();
            }            
        }
    }
});

search_container.addEventListener("input", function(event) {
    if (event.target.tagName === "INPUT") {
        const searchTerm = event.target.value.toLowerCase();
        filterTickersBySearch(searchTerm);
    }
});

filter_ticker_alphabet.addEventListener("click", function(event) {
    const clickedLetter = event.target.textContent;
    filterTickersByLetter(clickedLetter);
});




function filterTickersByLetter(letter) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".tickers_wrapper table tbody").innerHTML = this.responseText;
        }
    }
    xhttp.open("GET", "tickers_get.php?letter=" + letter, true);
    xhttp.send();
}


function CreateNewTicker(ticker) {
    const xhttp = new XMLHttpRequest();
    console.log("create new ticker:", ticker);
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            if(response.status === "success") {
                alert("Ticker created successfully!");
            } else {
                alert(response.message);
            }
        }
    }
    xhttp.open("POST", "ticker_create.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`symbol=${ticker}`);
}   


let searchXhttp = null;

function filterTickersBySearch(searchTerm) {
    if (searchXhttp) {
        searchXhttp.abort();
    }
    searchXhttp = new XMLHttpRequest();
    searchXhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".tickers_wrapper table tbody").innerHTML = this.responseText;
        }
    }
    searchXhttp.open("GET", "tickers_get.php?ticker=" + searchTerm, true);
    searchXhttp.send();
}