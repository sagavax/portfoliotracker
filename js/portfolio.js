const btnAddTransaction = document.getElementById('btnAddNewtransaction');
const create_transaction_wrapper = document.querySelector('.create_transaction_wrapper');
const search_in_ticker = document.getElementById('search_in_ticker');
const add_provider = document.getElementById('add_provider');
const tickerModal = document.getElementById('TickerModal');
const providerModal = document.getElementById('ProviderModal');
const assetCategoryModal = document.getElementById('AssetCategoryModal');
const longShortModal = document.getElementById('LongShortModal');
const modalPrice = document.getElementById('modalPrice');
const modalPriceInput = document.querySelector('#modalPrice input');
const modalQuantityInput = document.querySelector('#modalQuantity input');
const modalCurrency = document.getElementById('modalCurrency');
const modalCategory = document.getElementById('modalCategory');
const transactionList = document.querySelector('.transactions');
const new_transaction_actions_wrapper = document.querySelector('.new_transaction_actions_wrapper');
const modalTakeProfit = document.getElementById('modalTakeProfit');
const modalStopLoss = document.getElementById('modalStopLoss');
const btnCloseTransaction = document.querySelector('.button[name="close_transaction"]');
const btnAddNote = document.querySelector('.button[name="add_note"]');
const modalAddNote = document.getElementById('modalAddNote');



const MODAL_TICKER_MODES = {
  INSERT: "insertTicker",
  EDIT: "editTicker",
};

const MODAL_PROVIDER_MODES = {
  INSERT: "insertProvider",
  EDIT: "editProvider",
};

const MODAL_CATEGORY_MODES = {
  INSERT: "insertCategory",
  EDIT: "editCategory",
};

const LONG_SHORT_MODES = {
    INSERT: "insertLongShort",
    EDIT: "editLongShort"
}


let modalLongShortMode;
let modalTickerMode;
let modalProviderMode;
let modalCategoryMode;



btnAddNote.addEventListener('click', function() {
    modalAddNote.showModal();
})


new_transaction_actions_wrapper.addEventListener('click', function(e) {
    if(e.target && e.target.tagName === "BUTTON") {
        if(e.target.name === "new_transaction_close") {
            document.querySelector('.new_transaction').style.display = 'none';
            document.querySelector('.create_transaction_wrapper').style.display = 'none';
            const lastestTransaction = sessionStorage.getItem("currentTransactionId");
            removeTransaction(lastestTransaction);
            alert("New transaction cancelled!");
        } else if (e.target.name ==="new_transaction_reset") {
            //remove all added item - remove all buttons with data-type attribute
            const buttons = document.querySelectorAll(".new_transaction button[data-type]");
            //check if there is any button to remove
            if(buttons.length === 0) {
                alert("No item to reset!");
                return;
            }
            buttons.forEach(button => button.remove());
            
        } else if (e.target.name ==="new_transaction_create"){
            //remove new transaction div "new_transaction"
            //check if therre is any ticker
            const tickerBtn = document.querySelector(".new_transaction button[name='ticker']");
            if(!tickerBtn) {
                alert("Ticker is required!");
                return;
            } else {
                 document.querySelector('.create_transaction_wrapper').style.display = 'none';
            }

            //add created transaction to transaction list
        }
    }
})



transactionList.addEventListener('click', function(e) {
    if(e.target && e.target.tagName === "BUTTON") {
        //get current transaction id
        const transactionId = e.target.closest('.transaction').dataset.id;
        console.log("transaction id:", transactionId);
        sessionStorage.setItem("currentTransactionId", transactionId);
        if(e.target.name === "ticker") {
            if(e.target.innerHTML===""){
                console.log("no ticker");
                tickerModal.showModal();
                modalTickerMode = "editTicker";
                GetTickers();
            } 
            
        } else if (e.target.name ==="provider") {
            const transactionId = e.target.closest('.transaction').dataset.id;
            sessionStorage.setItem("currentTransactionId", transactionId);
            console.log("transaction id:", transactionId);
            //let modalProviderMode = MODAL_PROVIDER_MODES.EDIT
            modalProviderMode = "editProvider";
            document.getElementById("ProviderModal").showModal();
            GetProviders();
            
        } else if (e.target.name ==="category") {
            const transactionId = e.target.closest('.transaction').dataset.id;
            //console.log("transaction id:", transactionId);
            sessionStorage.setItem("currentTransactionId", transactionId);
            modalCategoryMode = "editCategory";
            console.log("modal category mode:", modalCategoryMode);
            document.getElementById("AssetCategoryModal").showModal();
            //GetAssetCategories();
            
        }else if (e.target.name ==="currency") {
            alert("currency");
        } else if(e.target.name ==="long_short") {
            modalLongShortMode = LONG_SHORT_MODES.EDIT;
            console.log("modal long short mode:", modalLongShortMode);
            document.getElementById("LongShortModal").showModal();            
        } else if (e.target.name ==="close_transaction") {
            const transactionId = e.target.closest('.transaction').dataset.id;
            closeTransaction(transactionId);
        } else if (e.target.name ==="add_note"){
            modalAddNote.showModal();
        } else if (e.target.name ==="add_quantity") {
            document.getElementById("modalQuantity").showModal();
        } else if (e.target.name ==="add_entry_price") {
            document.getElementById("modalPrice").showModal();
        } else if (e.target.name==="take_profit" ) {
            modalTakeProfit.showModal();
        } else if (e.target.name === "stop_loss") {
            modalStopLoss.showModal();
        }
    }
})



if(modalAddNote) {
    modalAddNote.addEventListener('click', function(e) {
        if(e.target && e.target.tagName === "BUTTON" && e.target.id === "saveNote") {
            const textarea = modalAddNote.querySelector('textarea');
            const noteText = textarea.value.trim();
            if(!noteText) {
                alert("Note cannot be empty!");
                return;
            }
            const transactionId = sessionStorage.getItem("currentTransactionId");
            updateTransactionNote(transactionId, noteText);
            modalAddNote.close();
        }
    })
}

modalTakeProfit.addEventListener('keydown', function(e) {
    currTransaction = sessionStorage.getItem("currentTransactionId");
    if(e.key === "Enter") {
        e.preventDefault();
        updateTakeProfit(currTransaction, e.target.value);
        modalTakeProfit.close();
    }
})

modalStopLoss.addEventListener('keydown', function(e) {
    currTransaction = sessionStorage.getItem("currentTransactionId");
    if(e.key === "Enter") {
        e.preventDefault();
        updateStopLoss(currTransaction, e.target.value);
        modalStopLoss.close();
    }
})


search_in_ticker.addEventListener('input', function(e) {
    FindTicker(e.target.value);    
})


//add new transaction
btnAddTransaction.addEventListener('click', function(e) {
    console.log('click');
    document.querySelector('.create_transaction_wrapper').style.display = 'flex';
    document.querySelector('.new_transaction').style.display = 'flex';
    //create a new transaction in db
    createTransaction()
})



if(assetCategoryModal){
    AssetCategoryModal.addEventListener('click', function(e) {
        document.getElementById("AssetCategoryModal").close();
        console.log(modalCategoryMode);
        if(modalCategoryMode === "insertCategory") {
            const currTransaction = sessionStorage.getItem("currentTransactionId");
            document.querySelector(".new_transaction").innerHTML += "<button type='button' class='button'>"+e.target.getAttribute("data-filter")+"</div>";
            updateTransactionCategory(currTransaction,e.target.getAttribute("data-filter"));
        } else if (modalCategoryMode === "editCategory") {
            const existing = document.querySelector(".new_transaction [data-type='category']");
            if(existing) {
                existing.textContent = e.target.getAttribute("data-filter");
            } else {
                const currTransaction = sessionStorage.getItem("currentTransactionId");
                console.log("current transaction:", currTransaction);
                document.querySelector("tr[data-id='"+currTransaction+"'] button[name='category']").innerHTML = e.target.getAttribute("data-filter");
                updateTransactionCategory(currTransaction,e.target.getAttribute("data-filter"));
            }
        }
    })}

modalCurrency.addEventListener("click", function(e) {
    e.preventDefault();
    const currency = e.target.name
    const existing = document.querySelector(".new_transaction [data-type='currency']");
    if(existing) {
        existing.textContent = currency;
    } else {
        document.querySelector(".new_transaction").innerHTML += "<button type='button' class='button' data-type='currency'>"+currency+"</button>";
    }
    modalCurrency.close();
})


modalPriceInput.addEventListener('input', function(e) {
    console.log(e.target.value);
})

modalPriceInput.addEventListener('keydown', function(e) {
    if(e.key === "Enter") {
        e.preventDefault();
        const price = e.target.value.trim();
        if(!price) return;
        const existing = document.querySelector(".new_transaction [data-type='price']");
        if(existing) {
            existing.textContent = price;
        } else {
            document.querySelector(".new_transaction").innerHTML += "<button type='button' class='button' data-type='price'>"+price+"</button>";
            updateTransactionEntryPrice(sessionStorage.getItem("currentTransactionId"), price);
        }
        modalPrice.close();
    }
})

modalQuantityInput.addEventListener('keydown', function(e) {
    if(e.key === "Enter") {
        e.preventDefault();
        const quantity = e.target.value.trim();
        if(!quantity) return;
        const existing = document.querySelector(".new_transaction [data-type='quantity']");
        if(existing) {
            existing.textContent = quantity;
        } else {
            document.querySelector(".new_transaction").innerHTML += "<button type='button' class='button' data-type='quantity'>"+quantity+"</button>";
            updateTransactionQuantity(sessionStorage.getItem("currentTransactionId"), quantity);
            modalQuantity.close();
        }
        modalQuantity.close();
    }
})



//create new transaction
create_transaction_wrapper.addEventListener('click', function(e) {
    if(e.target && e.target.tagName === "BUTTON") {
        if(e.target.name === "close") {
            document.querySelector('.create_transaction_wrapper').style.display = 'none';
        } else if (e.target.name ==="add_ticker") {
            modalTickerMode = MODAL_TICKER_MODES.INSERT;
            document.querySelector('#TickerModal').showModal();
            GetTickers();
        } else if (e.target.name==="add_provider") {
            //modalProviderMode = MODAL_PROVIDER_MODES.INSERT;
            modalProviderMode = "insertProvider";
            document.getElementById("ProviderModal").showModal();
           GetProviders();
        } else if (e.target.name==="long_short") {
            modalLongShortMode = "insertLongShort";
            document.getElementById("LongShortModal").showModal();
        } else if (e.target.name==="add_asset_category") {
            modalCategoryMode = "insertCategory";
            document.getElementById("AssetCategoryModal").showModal();
        } else if (e.target.name==="add_entry_price") {
            document.getElementById("modalPrice").showModal();
            modalPriceInput.value = "";
            modalPriceInput.focus();
        } else if (e.target.name==="add_quantity") {
            document.getElementById("modalQuantity").showModal();   
        } else if (e.target.name==="add_currency") {
            document.getElementById("modalCurrency").showModal();
        } else if (e.target.name === "add_tp") {
            modalTakeProfit.showModal();
        } else if (e.target.name === "add_sl") {
            modalStopLoss.showModal();
        }
    }
})

if(tickerModal) {
    tickerModal.addEventListener('click', function(e) {
        if(e.target.tagName === "BUTTON") {
             if (modalTickerMode === MODAL_TICKER_MODES.INSERT) {
                console.log(modalTickerMode);                 
                const ticker = e.target.getAttribute("data-ticker");
                document.querySelector(".new_transaction").innerHTML += "<button class='ticker' data-ticker="+ticker+">"+ticker+"</button>"; //document.querySelector(".new_transaction").appendChild(ticker);") 
                const currTransaction = sessionStorage.getItem("currentTransactionId");                
                updateTransactionTicker(currTransaction,ticker);
                tickerModal.close();
            } else if (modalTickerMode === MODAL_TICKER_MODES.EDIT) {
                const ticker = e.target.getAttribute("data-ticker");
                const currTransaction = sessionStorage.getItem("currentTransactionId");
                console.log("current transaction:", currTransaction);
                document.querySelector("tr[data-id='"+currTransaction+"'] button[name='ticker']").innerHTML = ticker;
                updateTransactionTicker(currTransaction,ticker);    
                tickerModal.close();                
            }
        }
    })
}

if(providerModal){
    providerModal.addEventListener('click', function(e) {
        console.log(e.target.getAttribute("data-name"));
        console.log(modalProviderMode);
        const currTransaction = sessionStorage.getItem("currentTransactionId");
        if(modalProviderMode === "insertProvider") {
            console.log("insert provider:", e.target.getAttribute("data-name"));
            console.log("current transaction:", currTransaction);
            document.querySelector(".new_transaction").innerHTML += "<button type='button' class='button' data-id-"+e.target.getAttribute("data-id")+">"+e.target.getAttribute("data-name")+"</button>";                    
            //document.querySelector(".new_transaction").appendChild("<button type='button' class='button' data-id-"+e.target.getAttribute("data-id")+">"+e.target.getAttribute("data-name")+"</button>");
            updateTransactionProvider(currTransaction,e.target.getAttribute("data-name"));
            document.getElementById("ProviderModal").close();
        } else if(modalProviderMode === "editProvider") {  
            console.log("current transaction:", currTransaction);
            console.log("edit provider:", e.target.getAttribute("data-name"));
            document.querySelector("tr[data-id='"+currTransaction+"'] button[name='provider']").innerHTML = e.target.getAttribute("data-name");
            updateTransactionProvider(currTransaction,e.target.getAttribute("data-name"));
            document.getElementById("ProviderModal").close();                    
        }
        }) 
    }



if(longShortModal){
    longShortModal.addEventListener('click', function(e) {
        if(e.target.tagName === "BUTTON" && (e.target.name === "add_long" || e.target.name === "add_short")) {
            document.getElementById("LongShortModal").close();
            const value = e.target.name === "add_long" ? "BUY" : "SELL";
            const cssClass = e.target.name === "add_long" ? "long" : "short";
            if(modalLongShortMode === "insertLongShort") {
                document.querySelector(".new_transaction").innerHTML += "<button type='button' class='button " + cssClass + "' name='long_short'>" + value + "</button>";
            const currTransaction = sessionStorage.getItem("currentTransactionId");
            updateTransactionLongShort(currTransaction, value);
            
            } else if(modalLongShortMode === "editLongShort") {
                const currTransaction = sessionStorage.getItem("currentTransactionId");
                const btn = document.querySelector("tr[data-id='"+currTransaction+"'] button[name='long_short']");
                btn.innerHTML = value;
                btn.className = "button " + cssClass;
                updateTransactionLongShort(currTransaction, value);
            }
        }
    })
}


function GetTickers(){
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("tickerDetailsContent").innerHTML = this.responseText;
            /* const tickers = JSON.parse(this.responseText);
            console.log(tickers); */
        }
    }
    xhttp.open("GET", "tickers_get.php", true);
    xhttp.send();
}

function FindTicker(ticker) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("tickerDetailsContent").innerHTML = this.responseText;
            /* const tickers = JSON.parse(this.responseText);
            console.log(tickers); */
        }
    }
    xhttp.open("GET", "tickers_get.php?ticker="+ticker, true);
    xhttp.send();
}

function AssignTicker(ticker) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("tickerDetailsContent").innerHTML = this.responseText;
            /* const tickers = JSON.parse(this.responseText);
            console.log(tickers); */
        }
    }
    xhttp.open("GET", "tickers_get.php?ticker="+ticker, true);
    xhttp.send();
}


function GetProviders(){
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("providerDetailsContent").innerHTML = this.responseText;
            /* const tickers = JSON.parse(this.responseText);
            console.log(tickers); */
        }
    }
    xhttp.open("GET", "providers_get.php", true);
    xhttp.send();
}


function createTransaction(){
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           getLastestTransaction();
           //document.getElementById("tickerDetailsContent").innerHTML = this.responseText;
            /* const tickers = JSON.parse(this.responseText);
            console.log(tickers); */
        }
    }
    xhttp.open("POST", "transaction_create.php", true);
    xhttp.send();
}


function getLastestTransaction(){
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            sessionStorage.setItem("currentTransactionId", this.responseText);
        }
    }
    xhttp.open("GET", "transaction_latest.php", true);
    xhttp.send();
}

function updateTransactionTicker(id, ticker){
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            alert("Transaction ticker updated successfully!");
            //document.getElementById("newTransactionContent").innerHTML = this.responseText;
            /* const tickers = JSON.parse(this.responseText);
            console.log(tickers); */
        }
    }
    xhttp.open("POST", "transaction_update_ticker.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}+&ticker=${ticker}`);
    
}

function updateTransactionProvider(id, provider){
    console.log("update provider:", id, provider);
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            alert("Transaction provider updated successfully!");
            //document.getElementById("newTransactionContent").innerHTML = this.responseText;
            /* const tickers = JSON.parse(this.responseText);
            console.log(tickers); */
        }
    }
    xhttp.open("POST", "transaction_update_provider.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}+&provider=${provider}`);
    
}

function updateTransactionCategory(id, category){
    console.log("update category:", id, category);
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            alert("Transaction category updated successfully!");
            //document.getElementById("newTransactionContent").innerHTML = this.responseText;
            /* const tickers = JSON.parse(this.responseText);
            console.log(tickers); */
        }
    }
    xhttp.open("POST", "transaction_update_category.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}+&category=${category}`);
}

function updateTransactionLongShort(id, longShort){
    console.log("update long_short:", id, longShort);
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "transaction_update_long_short.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}&long_short=${longShort}`);
}


function updateTakeProfit(id, takeProfit) {
    console.log("update take profit:", id, takeProfit);
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "transaction_update_take_profit.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}&take_profit=${takeProfit}`);
}

function updateStopLoss(id, stopLoss) {
    console.log("update stop loss:", id, stopLoss);
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "transaction_update_stop_loss.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}&stop_loss=${stopLoss}`);
}   


function closeTransaction(id) {
    console.log("close transaction:", id);
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //remove transaction from transaction list
            document.querySelector(`.transaction[data-id="${id}"]`).remove();
            alert("Transaction closed successfully!");
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "transaction_close.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}`);
}


function updateTransactionEntryPrice(id, entryPrice) {
    console.log("update entry price:", id, entryPrice);
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "transaction_update_entry_price.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}&entry_price=${entryPrice}`);
}

function updateTransactionQuantity(id, quantity) {
    console.log("update quantity:", id, quantity);
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "transaction_update_quantity.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}&quantity=${quantity}`);
}

function updateTransactionNote(id, note) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           document.querySelector("#modalAddNote textarea").value = "";
           alert("Transaction note updated successfully!");
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "transaction_update_note.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}&note=${encodeURIComponent(note)}`);
}


function removeTransaction(id) {
    console.log("remove transaction:", id);
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //remove transaction from transaction list
            document.querySelector(`.transaction[data-id="${id}"]`).remove();
            alert("Transaction removed successfully!");
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "transaction_delete.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}`);
}   