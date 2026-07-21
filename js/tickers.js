const filter_ticker_alphabet = document.querySelector(".filter_ticker_alphabet");
const search_container = document.querySelector(".search-container");
const saveTicker = document.querySelector("#saveTicker");
const cancelTicker = document.querySelector("#cancelTicker");


//save new ticker in modal
saveTicker.addEventListener("click", function() {
    const new_ticker = document.querySelector("#new_ticker").value.trim();
    const new_short_name = document.querySelector("#new_short_name").value.trim();
    const new_industry = document.querySelector("#new_industry").value.trim();
    const new_website = document.querySelector("#new_website").value.trim();
    CreateNewTicker(new_ticker, new_short_name, new_industry, new_website);
    modalTicker.close();
    document.querySelector("#new_ticker").value="";
    document.querySelector("#new_short_name").value="";
    document.querySelector("#new_industry").value="";
    document.querySelector("#new_website").value="";
});

//close modal on cancel
cancelTicker.addEventListener("click", function() {
      document.querySelector("#new_ticker").value="";
      document.querySelector("#new_short_name").value="";
      document.querySelector("#new_industry").value="";
      document.querySelector("#new_website").value="";
    modalTicker.close();
});

// when I click on the add ticker button, show the modal and set the input value to the search input value
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
                document.querySelector("#new_ticker").value = document.querySelector(".search-container input").value.trim();
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


function CreateNewTicker(ticker, short_name, industry, website) {
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
    xhttp.open("POST", "tickers_create.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`symbol=${ticker}&short_name=${short_name}&industry=${industry}&website=${website}`);
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