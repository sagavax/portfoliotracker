const btnAddTransaction = document.getElementById('btnAddNewtransaction');
const create_transaction_wrapper = document.querySelector('.create_transaction_wrapper');
const search_in_ticker = document.getElementById('search_in_ticker');
const add_provider = document.getElementById('add_provider');
const tickerModal = document.getElementById('modalTicker');
const providerModal = document.getElementById('modalProvider');
const assetCategoryModal = document.getElementById('modalAssetCategory');
const longShortModal = document.getElementById('modalLongShort');
const modalPrice = document.getElementById('modalPrice');
const modalPriceInput = document.querySelector('#modalPrice input');
const modalQuantityInput = document.querySelector('#modalQuantity input');
const modalCurrency = document.getElementById('modalCurrency');
const modalCategory = document.getElementById('modalCategory');
const transactionList = document.querySelector('.transactions');
const new_transaction = document.querySelector('.new_transaction');
const modalTakeProfit = document.getElementById('modalTakeProfit');
const modalStopLoss = document.getElementById('modalStopLoss');
const modalAddNote = document.getElementById('modalAddNote');
const modalSpotPerpetual = document.getElementById('modalSpotPerpetualModal');
const transactions_filters = document.querySelector('.transactions_filters');
const selectCurrency = document.getElementById('currency');
const modalNote = document.getElementById('modalNote');
const modalNotes = document.getElementById('modalNotes');

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
let modalSpotPerpetualMode;
let modalCurrencyMode;

/* modalNotes.addEventListener("keyup", function(e) {
    if(!e.target.classList.contains('note')) return;
    const saveBtn = modalNotes.querySelector("button[name='save_note']");
    if(saveBtn) {
        saveBtn.style.display = e.target.textContent.trim().length > 0 ? "block" : "none";
    }
}) */



/* modalNotes.addEventListener('click', function(e) {
    if(e.target && e.target.tagName === "BUTTON" && e.target.name === "add_note") {
        const notes_wrapper = document.querySelector('.notes_wrapper');
        notes_wrapper.insertAdjacentHTML('afterbegin', "<div class='note'data-status='draft' contenteditable='true'></div>");
    } else if(e.target && e.target.tagName === "BUTTON" && e.target.name === "save_note") {
        const note = document.querySelector('div.note[data-status="draft"]');
        const noteText = note.innerHTML.trim();
        const transactionId = sessionStorage.getItem("currentTransactionId");
        createTransactionNote(transactionId, noteText);
        note.removeAttribute("data-status");

        note.setAttribute("contenteditable", false);
    }
}) */


modalNote.addEventListener('click', function(e) {
    if(e.target && e.target.tagName === "BUTTON" && e.target.id === "noteSave") {
        const note_text = document.getElementById("note_text");
        const noteText = note_text.value.trim();
        if(!noteText) {
            alert("Note cannot be empty!");
            return;
        }
        const transactionId = sessionStorage.getItem("currentTransactionId");
        updateTransactionNote(transactionId, noteText);
        modalAddNote.close();
    }
})


currency.addEventListener('change', function(e) {
    const selectedCurrency = e.target.value;
    filterTransactionsByCurrency(selectedCurrency);
}); 

transactions_filters.addEventListener('click', function(e) {
    if(e.target.tagName === "BUTTON") {
    if (e.target && e.target.getAttribute("data-filter")) {
        const filterName = e.target.getAttribute("data-filter");
        filterTransactions(filterName);
     }
    }
});


document.addEventListener('keydown', function(e) {
    if (e.key !== 'Enter') return;
    
    const isPrice = e.target.classList.contains('price');
    const isQuantity = e.target.classList.contains('quantity');
    if (!isPrice && !isQuantity) return;
    
    e.preventDefault();
    const transactionId = e.target.closest('.transaction').dataset.id;
    const value = e.target.textContent.trim();
    
    if (isPrice) {
        updateTransactionEntryPrice(transactionId, value);
        if (value === "") {
            e.target.outerHTML = "<button type='button' class='button' name='add_entry_price'><i class='fa fa-plus'></i> Add price</button>";
        }
    } else {
        updateTransactionQuantity(transactionId, value);
        if (value === "") {
            e.target.outerHTML = "<button type='button' class='button' name='add_quantity'><i class='fa fa-plus'></i> Add quantity</button>";
        }
    }
});

modalSpotPerpetual.addEventListener('click', function(e) {
    if(e.target.tagName === "BUTTON") {
        const value = e.target.name === "add_spot" ? "SPOT" : "PERPETUAL";
        const transactionId = sessionStorage.getItem("currentTransactionId");
        document.querySelector('tr[data-id="'+transactionId+'"] .button[name="spot_perpetual"]').textContent = value;
        updateSpotPerpetual(transactionId, value);
        modalSpotPerpetual.close();
    }
})

new_transaction.addEventListener('click', function(e) {
    if(e.target && e.target.tagName === "BUTTON") {
        if(e.target.name === "new_transaction_cancel") {
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
            const tickerBtn = document.querySelector(".new_transaction button[data-ticker]");
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
    const btn = e.target.closest('button');
    if (!btn) return;
    const transactionId = btn.closest('.transaction').dataset.id;
    console.log("transaction id:", transactionId + " name: " + btn.name);
    sessionStorage.setItem("currentTransactionId", transactionId);

    if (btn.name === "ticker") {
        tickerModal.showModal();
        modalTickerMode = "editTicker";
        GetTickers();
    } else if (btn.name === "provider") {
        modalProviderMode = "editProvider";
        document.getElementById("modalProvider").showModal();
        GetProviders();
    } else if (btn.name === "category") {
        modalCategoryMode = "editCategory";
        document.getElementById("modalAssetCategory").showModal();
    } else if (btn.name === "currency") {
        modalCurrencyMode = "editCurrency";
        modalCurrency.showModal();
    } else if (btn.name === "long_short") {
        modalLongShortMode = LONG_SHORT_MODES.EDIT;
        document.getElementById("modalLongShort").showModal();
    } else if (btn.name === "close_transaction") {
        closeTransaction(transactionId);
    } else if (btn.name === "add_note") {
        modalNote.showModal();
    } else if(btn.name === "notes") {
        modalNote.showModal(); 
        const transactionId = sessionStorage.getItem("currentTransactionId"); 
        //GetNotes(transactionId);
    } else if (btn.name === "add_quantity") {
        document.getElementById("modalQuantity").showModal();
    } else if (btn.name === "add_entry_price") {
        document.getElementById("modalPrice").showModal();
    } else if (btn.name === "take_profit") {
        modalTakeProfit.showModal();
    } else if (btn.name === "stop_loss") {
        modalStopLoss.showModal();
    } else if (btn.name === "see_transaction") {
        window.location.href = "transaction_details.php?transaction_id=" + transactionId;
    } else if (btn.name === "spot_perpetual") {
        modalSpotPerpetualMode = "editSpotPerpetual";
        document.getElementById("modalSpotPerpetualModal").showModal();
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
    document.querySelector('.create_transaction_wrapper').style.display = 'flex';
    document.querySelector('.new_transaction').style.display = 'flex';
    //create a new transaction in db
    createTransaction()
})



if(assetCategoryModal){
    assetCategoryModal.addEventListener('click', function(e) {
        document.getElementById("modalAssetCategory").close();
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
    const btn = e.target.closest('button[data-currency]');
    if (!btn) return;
    e.preventDefault();
    const currency = btn.getAttribute('data-currency');
    const currTransaction = sessionStorage.getItem("currentTransactionId");

    if (modalCurrencyMode === "insertCurrency") {
        const existing = document.querySelector(".new_transaction [data-type='currency']");
        if (existing) {
            existing.textContent = currency;
        } else {
            document.querySelector(".new_transaction").innerHTML += "<button type='button' class='button' data-type='currency'>" + currency + "</button>";
        }
    } else if (modalCurrencyMode === "editCurrency") {
        document.querySelector("tr[data-id='" + currTransaction + "'] button[name='currency']").textContent = currency;
        updateTransactionCurrency(currTransaction, currency);
    }

    modalCurrency.close();
})


modalPriceInput.addEventListener('input', function(e) {
    console.log(e.target.value);
})

modalPriceInput.addEventListener('keydown', function(e) {
    if (e.key !== "Enter") return;
    e.preventDefault();
    
    const price = e.target.value.trim();
    if (!price) return;
    
    const transactionId = sessionStorage.getItem("currentTransactionId");
    //const transaction = document.querySelector(`.tdtransaction[data-id="${transactionId}"]`);
    
    if (!transaction) return;
    
    const existing = transaction.querySelector("[data-type='price']");
    if (existing) {
        existing.textContent = price;
    } else {
        //transaction.insertAdjacentHTML('beforeend',
        //    `<button type='button' class='button' data-type='price'>${price}</button>`
        const transaction = document.querySelector("tr[data-id='"+transactionId+"'] button[name='add_entry_price']");
        transaction.outerHTML="<div class='quantity' contenteditable='true'>"+price+"</div>";
     }
    
    updateTransactionEntryPrice(transactionId, price);
    modalPrice.close();
});


modalQuantityInput.addEventListener('keydown', function(e) {
    if (e.key !== "Enter") return;
    e.preventDefault();
    
    const quantity = e.target.value.trim();
    if (!quantity) return;
    
    const transactionId = sessionStorage.getItem("currentTransactionId");
    //const transaction = document.querySelector(`.transaction[data-id="${transactionId}"]`);
    const transaction = document.querySelector("tr[data-id='"+transactionId+"'] button[name='add_quantity']");
    if (!transaction) return;
    
    const existing = transaction.querySelector("[data-type='quantity']");
    if (existing) {
        existing.textContent = quantity;
    } else {
        //transaction.insertAdjacentHTML('beforeend',
        //    `<button type='button' class='button' data-type='quantity'>${quantity}</button>`

        transaction.outerHTML="<div class='quantity' contenteditable='true'>"+quantity+"</div>";
            }
    
    updateTransactionQuantity(transactionId, quantity);
    modalQuantity.close();
});



//create new transaction
create_transaction_wrapper.addEventListener('click', function(e) {
    if(e.target && e.target.tagName === "BUTTON") {
        if(e.target.name === "close") {
            document.querySelector('.create_transaction_wrapper').style.display = 'none';
        } else if (e.target.name ==="add_ticker") {
            modalTickerMode = MODAL_TICKER_MODES.INSERT;
            document.querySelector('#modalTicker').showModal();
            GetTickers();
        } else if (e.target.name==="add_provider") {
            //modalProviderMode = MODAL_PROVIDER_MODES.INSERT;
            modalProviderMode = "insertProvider";
            document.getElementById("modalProvider").showModal();
           GetProviders();
        } else if (e.target.name==="long_short") {
            modalLongShortMode = "insertLongShort";
            document.getElementById("modalLongShort").showModal();
        } else if (e.target.name==="add_asset_category") {
            modalCategoryMode = "insertCategory";
            document.getElementById("modalAssetCategory").showModal();
        } else if (e.target.name==="add_entry_price") {
            document.getElementById("modalPrice").showModal();
            modalPriceInput.value = "";
            modalPriceInput.focus();
        } else if (e.target.name==="add_quantity") {
            document.getElementById("modalQuantity").showModal();   
        } else if (e.target.name==="add_currency") {
            modalCurrencyMode = "insertCurrency";
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
                if(e.target.getAttribute("data-letter")) {
                    GetTickers(e.target.getAttribute("data-letter"));
                    return;
                } else if (e.target.getAttribute("data-ticker")) {
                    console.log(e.target.getAttribute("data-ticker"));
                    //clicked on ticker button but not on letter button
                    const ticker = e.target.getAttribute("data-ticker");
                    const currTransaction = sessionStorage.getItem("currentTransactionId");
                    console.log("current transaction:", currTransaction);
                    document.querySelector("tr[data-id='"+currTransaction+"'] button[name='ticker']").innerHTML = ticker;
                    updateTransactionTicker(currTransaction,ticker);    
                    tickerModal.close();                
                }               
                
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
            document.getElementById("modalProvider").close();
        } else if(modalProviderMode === "editProvider") {  
            console.log("current transaction:", currTransaction);
            console.log("edit provider:", e.target.getAttribute("data-name"));
            document.querySelector("tr[data-id='"+currTransaction+"'] button[name='provider']").innerHTML = e.target.getAttribute("data-name");
            updateTransactionProvider(currTransaction,e.target.getAttribute("data-name"));
            document.getElementById("modalProvider").close();                    
        }
        }) 
    }



if(longShortModal){
    longShortModal.addEventListener('click', function(e) {
        if(e.target.tagName === "BUTTON" && (e.target.name === "add_long" || e.target.name === "add_short")) {
            document.getElementById("modalLongShort").close();
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


function GetTickers(letter = ''){
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("tickerDetailsContent").innerHTML = this.responseText;
            /* const tickers = JSON.parse(this.responseText);
            console.log(tickers); */
        }
    }
    xhttp.open("GET", "tickers_get.php?letter="+letter, true);
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


function updateTransactionCurrency(id, currency) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "transaction_update_currency.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}&currency=${currency}`);
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


function updateSpotPerpetual(id, value) {
    console.log("update spot/perpetual:", id, value);
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "transaction_update_spot_perpetual.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}&spot_perpetual=${value}`);
}   


function filterTickers(letter) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            document.getElementById("newTransactionContent").innerHTML = this.responseText;
        }
    }
    xhttp.open("POST", "transaction_filter_tickers.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`letter=${letter}`);
}

function filterTransactions(filterName) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            document.querySelector(".transactions").innerHTML = this.responseText;
        }
    }
    xhttp.open("POST", "transactions_filter.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`filter_name=${filterName}`);
}

function filterTransactionsByCurrency(currency) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            document.querySelector(".transactions").innerHTML = this.responseText;
        }
    }
    xhttp.open("POST", "transactions_filter_by_currency.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`currency=${currency}`);
}

function createTransactionNote(id, note) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector("#modalAddNote textarea").value = "";
            alert("Transaction note created successfully!");
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "transaction_create_note.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}&note=${encodeURIComponent(note)}`);
}

function updateTransactionNote(id, note) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           document.querySelector("#modalAddNote textarea").value = "";
           alert("Transaction note updated successfully!");
           const response = JSON.parse(this.responseText);
           console.log(response.note_count);
           document.querySelector(`.transaction[data-id="${id}"] button[name="notes"]`).innerText = String(response.note_count);
           document.querySelector("#modalNote").close();
        }
    }
    xhttp.open("POST", "transaction_update_note.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}&note=${encodeURIComponent(note)}`);
}

function GetNotes(id) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            document.querySelector("#notesDetailsContent").innerHTML = this.responseText;
        }
    }
    xhttp.open("POST", "transaction_get_notes.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`transaction_id=${id}`);
}