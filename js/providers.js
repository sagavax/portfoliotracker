const btnAddNewProvider = document.getElementById('btnAddNewProvider');
const modalAddNewProvider = document.getElementById('modalAddNewProvider');
const modalAddNewProviderSave = document.getElementById('btnSaveNewProvider');
const btnCancelNewProvider = document.getElementById('btnCancelNewProvider');
const providerName = document.getElementById('provider_name');
const providerUrl = document.getElementById('provider_url');
const providerDescription = document.getElementById('provider_description');
const providerList = document.querySelector('.providers');
const providerDetails = document.querySelector('.provider_details');
const modalProviderLogoEditor = document.getElementById('modalProviderLogoEditor');
const edit_provider_url = document.querySelector('button[name=edit_provider_url]');
const provider_transactions = document.querySelector('.provider_transactions');
const provider_transactions_body = document.querySelector('.provider_transactions tbody');

// Transaction field dialogs shared with the portfolio module's backend.
const PORTFOLIO_API = 'portfolio/';
const tickerModal = document.getElementById('modalTicker');
const search_in_ticker = document.getElementById('search_in_ticker');
const longShortModal = document.getElementById('modalLongShort');
const modalSpotPerpetual = document.getElementById('modalSpotPerpetual');
const modalNote = document.getElementById('modalNote');
const modalAssetCategory = document.getElementById('modalAssetCategory');
const modalTakeProfit = document.getElementById('modalTakeProfit');
const modalTakeProfitInput = document.querySelector('#modalTakeProfit input');
const modalStopLoss = document.getElementById('modalStopLoss');
const modalStopLossInput = document.querySelector('#modalStopLoss input');
const modalCurrency = document.getElementById('modalCurrency');
const modalPrice = document.getElementById('modalPrice');
const modalPriceInput = document.querySelector('#modalPrice input');
const modalQuantity = document.getElementById('modalQuantity');
const modalQuantityInput = document.querySelector('#modalQuantity input');
const modalManualBot = document.getElementById('modalManualBot');
const modalLeverage = document.getElementById('modalLeverage');
const leverageSlider = document.getElementById('leverageSlider');
const leverageInput = document.getElementById('leverageInput');
const leverageCancel = document.getElementById('leverageCancel');
const leverageSave = document.getElementById('saveLeverage');

let modalLongShortMode = null;
let modalSpotPerpetualMode = null;

if (modalAddNewProviderSave){
    modalAddNewProviderSave.addEventListener('click', () => {
        const providerName = document.getElementById('provider_name').value;
        const providerUrl = document.getElementById('provider_url').value;
        const providerLogo = document.getElementById('provider_logo').value;
        const providerDescription = document.getElementById('provider_description').innerText;
        if(!providerName) {
            alert('Please fill at least the provider name before saving.');
            return;
        }
        addNewProvider(providerName, providerUrl, providerLogo, providerDescription);
    });
}

if (providerDetails) {
    providerDetails.addEventListener('input', function (e) {
        const descriptionEl = e.target.closest('.provider_description');
        if (descriptionEl) {
            const providerId = descriptionEl.closest('.provider_details').dataset.id;
            const updatedDescription = descriptionEl.innerText;
            updateProviderDescription(providerId, updatedDescription);
        }
    });

    providerDetails.addEventListener('paste', function (e) {
        const input = e.target.closest('.provider_logo_url_input');
        if (input && modalProviderLogoEditor) {
            e.preventDefault();
            const pasted = (e.clipboardData || e.originalEvent.clipboardData).getData('text');
            if (pasted.length > 140) {
                const providerId = input.closest('.provider_details').dataset.id;
                const modalProviderLogo = document.getElementById('modalProviderLogo');
                modalProviderLogo.value = pasted;
                modalProviderLogoEditor.dataset.id = providerId;
                modalProviderLogoEditor.style.display = 'block';
                modalProviderLogoEditor.showModal();
            } else {
                input.value = pasted;
            }
        }

    });

   
    providerDetails.addEventListener('click', function (e) {
        const saveBtn = e.target.closest('.btn_save_logo');
        if (saveBtn) {
            const wrapper = saveBtn.closest('.provider_logo_save_wrapper');
            const input = wrapper.querySelector('.provider_logo_url_input');
            const providerId = saveBtn.closest('.provider_details').dataset.id;
            const logo = input.value.trim();
            if (logo) {
                updateProviderLogo(providerId, logo);
                wrapper.outerHTML = `<div class="provider_logo"><img src="${logo}" alt="Logo"></div>`;
            }
        }

        const editUrlBtn = e.target.closest('button[name=edit_provider_url]');
        if (editUrlBtn) {
            const providerUrl = editUrlBtn.closest('.provider_details').querySelector('.provider_url');
            providerUrl.style.backgroundColor = '#0d0d0d';
            providerUrl.contentEditable = true;
            providerUrl.focus();
            //remove <a> tag if present
            if (providerUrl.querySelector('a')) {
                providerUrl.querySelector('a').remove();
            }
            editUrlBtn.style.display = 'none';
            providerUrl.addEventListener("input", function () {
                const providerId = providerUrl.closest('.provider_details').dataset.id;
                const updatedUrl = providerUrl.innerText.trim();
                updateProviderUrl(providerId, updatedUrl);
            });
        }

        const filterBtn = e.target.closest('.transaction_actions_tabs button');
        if (filterBtn) {
            const providerName = filterBtn.closest('.provider_details').dataset.name;
            if (filterBtn.name === 'all_transactions') {
                loadTransactions(providerName, 'all');
            } else if (filterBtn.name === 'active_transactions') {
                loadTransactions(providerName, 'active');
            } else if (filterBtn.name === 'closed_transactions') {
                loadTransactions(providerName, 'closed');
            }
        }

        const addTransactionBtn = e.target.closest('button[name=add_transaction]');
        if (addTransactionBtn) {
            const providerName = sessionStorage.getItem('selectedProviderName');
            if (providerName) {
                createTransaction(providerName);
            }
        }

        const transactionRow = e.target.closest('.transaction');
        const transactionBtn = e.target.closest('button');
        if (transactionRow && transactionBtn) {
            const transactionId = transactionRow.dataset.id;
            sessionStorage.setItem('currentTransactionId', transactionId);

            if (transactionBtn.name === 'ticker') {
                tickerModal.showModal();
                GetTickers();
            } else if (transactionBtn.name === 'currency') {
                modalCurrency.showModal();
            } else if (transactionBtn.name === 'long_short') {
                modalLongShortMode = 'editLongShort';
                longShortModal.showModal();
            } else if (transactionBtn.name === 'add_leverage') {
                modalLeverage.showModal();
            } else if (transactionBtn.name === 'add_quantity') {
                modalQuantity.showModal();
            } else if (transactionBtn.name === 'add_entry_price') {
                modalPrice.showModal();
            } else if (transactionBtn.name === 'spot_perpetual') {
                modalSpotPerpetualMode = 'editSpotPerpetual';
                modalSpotPerpetual.showModal();
            } else if (transactionBtn.name === 'manual_bot') {
                modalManualBot.showModal();
            } else if (transactionBtn.name === 'add_note' || transactionBtn.name === 'notes') {
                modalNote.showModal();
            } else if (transactionBtn.name === 'see_transaction') {
                window.location.href = PORTFOLIO_API + 'transaction.php?transaction_id=' + transactionId;
            } else if (transactionBtn.name === 'category') {
                modalAssetCategory.showModal();
            } else if (transactionBtn.name === 'take_profit') {
                modalTakeProfitInput.value = '';
                modalTakeProfit.showModal();
                modalTakeProfitInput.focus();
            } else if (transactionBtn.name === 'stop_loss') {
                modalStopLossInput.value = '';
                modalStopLoss.showModal();
                modalStopLossInput.focus();
            }
        }
    });
}




providerList.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('provider_card')) {
        const providerId = e.target.dataset.id;
        const providerName = e.target.dataset.name;
        getProviderDetails(providerId, providerName);
        sessionStorage.setItem('selectedProviderName', providerName);
    }
});


if (btnAddNewProvider && modalAddNewProvider) {
    btnAddNewProvider.addEventListener('click', () => {
        modalAddNewProvider.showModal();
    });
}

if (modalAddNewProvider) {
    btnCancelNewProvider.addEventListener('click', () => {
        modalAddNewProvider.close();
    });
}

if (modalAddNewProvider) {
    modalAddNewProvider.addEventListener('click', function (e) {
        if (e.target && e.target.tagName === "BUTTON") {
            if (e.target.id === "modalAddNewProviderSave") {
                //save new provider
            } else if (e.target.id === "modalAddNewProviderClose") {
                modalAddNewProvider.style.display = 'none';
            }
        }
    });
}

if (modalProviderLogoEditor) {
    const closeSpan = modalProviderLogoEditor.querySelector('.close');
    if (closeSpan) {
        closeSpan.addEventListener('click', () => {
            modalProviderLogoEditor.close();
            modalProviderLogoEditor.style.display = 'none';
        });
    }

    const btnSaveProviderLogo = document.getElementById('btnSaveProviderLogo');
    const modalProviderLogo = document.getElementById('modalProviderLogo');

    if (btnSaveProviderLogo && modalProviderLogo) {
        btnSaveProviderLogo.addEventListener('click', () => {
            const updatedLogo = modalProviderLogo.value.trim();
            const providerId = modalProviderLogoEditor.dataset.id;

            updateProviderLogo(providerId, updatedLogo);

            const wrapper = document.querySelector('.provider_details .provider_logo_save_wrapper');
            const existingLogo = document.querySelector('.provider_details .provider_logo');
            if (updatedLogo) {
                const logoHtml = `<div class="provider_logo"><img src="${updatedLogo}" alt="Logo"></div>`;
                if (wrapper) {
                    wrapper.outerHTML = logoHtml;
                } else if (existingLogo) {
                    existingLogo.innerHTML = `<img src="${updatedLogo}" alt="Logo">`;
                }
            }

            modalProviderLogoEditor.close();
            modalProviderLogoEditor.style.display = 'none';
        });
    }
}

if (tickerModal) {
    tickerModal.addEventListener('click', function (e) {
        if (e.target.tagName !== 'BUTTON') return;
        if (e.target.id === 'tickerModalClose') {
            tickerModal.close();
            return;
        }
        if (e.target.getAttribute('data-letter')) {
            GetTickers(e.target.getAttribute('data-letter'));
            return;
        }
        if (e.target.getAttribute('data-ticker')) {
            const ticker = e.target.getAttribute('data-ticker');
            const transactionId = sessionStorage.getItem('currentTransactionId');
            document.querySelector("tr[data-id='" + transactionId + "'] button[name='ticker']").innerHTML = ticker;
            updateTransactionTicker(transactionId, ticker);
            tickerModal.close();
        }
    });
}

if (search_in_ticker) {
    search_in_ticker.addEventListener('input', function (e) {
        FindTicker(e.target.value);
    });
}

if (modalCurrency) {
    modalCurrency.addEventListener('click', function (e) {
        const btn = e.target.closest('button[data-currency]');
        if (!btn) return;
        const currency = btn.getAttribute('data-currency');
        const transactionId = sessionStorage.getItem('currentTransactionId');
        document.querySelector("tr[data-id='" + transactionId + "'] button[name='currency']").textContent = currency;
        updateTransactionCurrency(transactionId, currency);
        modalCurrency.close();
    });
}

if (modalPriceInput) {
    modalPriceInput.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();
        const price = e.target.value.trim();
        if (!price) return;
        const transactionId = sessionStorage.getItem('currentTransactionId');
        const row = document.querySelector("tr[data-id='" + transactionId + "']");
        if (row) {
            const existing = row.querySelector("[data-type='price']");
            if (existing) {
                existing.textContent = price;
            } else {
                const priceButton = row.querySelector("button[name='add_entry_price']");
                if (priceButton) {
                    priceButton.outerHTML = "<div class='price' contenteditable='true'>" + price + "</div>";
                }
            }
        }
        updateTransactionEntryPrice(transactionId, price);
        modalPrice.close();
        modalPriceInput.value = '';
    });
}

if (modalQuantityInput) {
    modalQuantityInput.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();
        const quantity = e.target.value.trim();
        if (!quantity) return;
        const transactionId = sessionStorage.getItem('currentTransactionId');
        const row = document.querySelector("tr[data-id='" + transactionId + "']");
        if (row) {
            const existing = row.querySelector("[data-type='quantity']");
            if (existing) {
                existing.textContent = quantity;
            } else {
                const quantityButton = row.querySelector("button[name='add_quantity']");
                if (quantityButton) {
                    quantityButton.outerHTML = "<div class='quantity' contenteditable='true'>" + quantity + "</div>";
                }
            }
        }
        updateTransactionQuantity(transactionId, quantity);
        modalQuantity.close();
        modalQuantityInput.value = '';
    });
}

if (longShortModal) {
    longShortModal.addEventListener('click', function (e) {
        if (e.target.tagName !== 'BUTTON') return;
        if (e.target.id === 'longShortModalClose') {
            longShortModal.close();
            return;
        }
        if (e.target.name !== 'add_long' && e.target.name !== 'add_short') return;
        const value = e.target.name === 'add_long' ? 'BUY' : 'SELL';
        const cssClass = e.target.name === 'add_long' ? 'long' : 'short';
        const transactionId = sessionStorage.getItem('currentTransactionId');
        const btn = document.querySelector("tr[data-id='" + transactionId + "'] button[name='long_short']");
        if (btn) {
            btn.innerHTML = value;
            btn.className = 'transaction_button ' + cssClass;
        }
        updateTransactionLongShort(transactionId, value);
        longShortModal.close();
    });
}

if (modalSpotPerpetual) {
    modalSpotPerpetual.addEventListener('click', function (e) {
        if (e.target.tagName !== 'BUTTON') return;
        if (e.target.id === 'spotPerpetualModalClose') {
            modalSpotPerpetual.close();
            return;
        }
        if (e.target.name !== 'add_spot' && e.target.name !== 'add_perpetual') return;
        const value = e.target.name === 'add_spot' ? 'Spot' : 'Perpetual';
        const transactionId = sessionStorage.getItem('currentTransactionId');
        const btn = document.querySelector("tr[data-id='" + transactionId + "'] button[name='spot_perpetual']");
        if (btn) {
            btn.textContent = value;
        }
        updateSpotPerpetual(transactionId, value);
        modalSpotPerpetual.close();
    });
}

if (modalManualBot) {
    modalManualBot.addEventListener('click', function (e) {
        if (e.target.tagName !== 'BUTTON') return;
        if (e.target.id === 'manualBotModalClose') {
            modalManualBot.close();
            return;
        }
        if (e.target.name !== 'manual_bot_on' && e.target.name !== 'manual_bot_off') return;
        const manualBot = e.target.innerText;
        const transactionId = sessionStorage.getItem('currentTransactionId');
        updateTransactionManualBot(transactionId, manualBot);
        modalManualBot.close();
    });
}

if (leverageCancel && leverageSlider && leverageInput && leverageSave) {
    leverageCancel.addEventListener('click', function () {
        modalLeverage.close();
    });

    leverageSlider.addEventListener('input', function () {
        leverageInput.value = leverageSlider.value;
    });

    leverageInput.addEventListener('input', function () {
        const value = Math.min(Math.max(parseInt(leverageInput.value) || 0, leverageSlider.min), leverageSlider.max);
        leverageSlider.value = value;
    });

    leverageSave.addEventListener('click', function () {
        if (leverageInput.value == 0) {
            alert('Leverage cannot be 0!');
            return;
        }
        const transactionId = sessionStorage.getItem('currentTransactionId');
        document.querySelector('tr[data-id="' + transactionId + '"] button[name="add_leverage"]').textContent = leverageInput.value + 'x';
        updateTransactionLeverage(transactionId, leverageInput.value + 'x');
        modalLeverage.close();
    });
}

if (modalAssetCategory) {
    modalAssetCategory.addEventListener('click', function (e) {
        if (e.target.id === 'assetModalClose') {
            modalAssetCategory.close();
            return;
        }
        const btn = e.target.closest('button[data-filter]');
        if (!btn) return;
        const category = btn.getAttribute('data-filter');
        const transactionId = sessionStorage.getItem('currentTransactionId');
        const categoryBtn = document.querySelector("tr[data-id='" + transactionId + "'] button[name='category']");
        if (categoryBtn) {
            categoryBtn.innerHTML = category;
        }
        updateTransactionCategory(transactionId, category);
        modalAssetCategory.close();
    });
}

if (modalTakeProfitInput) {
    modalTakeProfitInput.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();
        const takeProfit = e.target.value.trim();
        if (!takeProfit) return;
        const transactionId = sessionStorage.getItem('currentTransactionId');
        const btn = document.querySelector("tr[data-id='" + transactionId + "'] button[name='take_profit']");
        if (btn) {
            btn.textContent = takeProfit;
        }
        updateTakeProfit(transactionId, takeProfit);
        modalTakeProfit.close();
    });
}

if (modalStopLossInput) {
    modalStopLossInput.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();
        const stopLoss = e.target.value.trim();
        if (!stopLoss) return;
        const transactionId = sessionStorage.getItem('currentTransactionId');
        const btn = document.querySelector("tr[data-id='" + transactionId + "'] button[name='stop_loss']");
        if (btn) {
            btn.textContent = stopLoss;
        }
        updateStopLoss(transactionId, stopLoss);
        modalStopLoss.close();
    });
}

if (modalNote) {
    modalNote.addEventListener('click', function (e) {
        if (e.target.tagName !== 'BUTTON') return;
        if (e.target.id === 'noteClose') {
            modalNote.close();
            return;
        }
        if (e.target.id === 'noteSave') {
            const noteText = document.getElementById('note_text').value.trim();
            if (!noteText) {
                alert('Note cannot be empty!');
                return;
            }
            const transactionId = sessionStorage.getItem('currentTransactionId');
            updateTransactionNote(transactionId, noteText);
        }
    });
}

function httpRequest(method, url, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            callback(xhr.responseText);
        }
    };
    xhr.send();
};


function getProviderDetails(providerId, providerName) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const detailsDiv = document.querySelector(".provider_details");
            if (detailsDiv) {
                detailsDiv.innerHTML = this.responseText;
                detailsDiv.dataset.id = providerId;
                detailsDiv.dataset.name = providerName;
                loadTransactions(providerName, 'all');
            }
        }
    }
    xhttp.open("GET", "provider_details.php?providerId=" + providerId, true);
    //xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
};



function updateProviderDescription(providerId, description) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            alert("Provider description updated successfully!");
        }
    }
    xhttp.open("POST", "provider_description_update.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`provider_id=${providerId}&description=${description}`);
};


function updateProviderLogo(providerId, logo) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            alert("Provider logo updated successfully!");
        }
    }
    xhttp.open("POST", "provider_logo_update.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`provider_id=${providerId}&logo=${logo}`);
};  


function updateProviderUrl(providerId, url) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            alert("Provider URL updated successfully!");
            document.querySelector('.provider_details .provider_url').contentEditable = false;
             const editUrlBtn = document.querySelector('.provider_details button[name=edit_provider_url]');
             if (editUrlBtn) {
                editUrlBtn.style.display = 'block';
             }
        }
    }
    xhttp.open("POST", "provider_url_update.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`provider_id=${providerId}&url=${url}`);
};  



function addNewProvider(providerName, providerUrl, providerLogo, providerDescription) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            document.querySelector("#modalAddNewProvider").close();
            const reponse = JSON.parse(this.responseText);
            const newProviderId = reponse.provider_id;
            const html = `<div class="provider_card" data-id="${newProviderId}" data-name="${providerName}">${providerName}</div>`;
            document.querySelector(".providers").insertAdjacentHTML('beforeend', html);
            alert("New provider added successfully!");
        }
    }
    xhttp.open("POST", "providers_add_new.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    const data = `providerName=${providerName}&providerUrl=${providerUrl}&providerLogo=${providerLogo}&providerDescription=${providerDescription}`;
    xhttp.send(data);
}

function createTransaction(providerName) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            if (response.status === 'success') {
                loadTransactions(providerName, 'all');
            } else {
                alert(response.message || 'Failed to create transaction.');
            }
        }
    }
    xhttp.open("POST", "provider_transaction_create.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`provider_name=${encodeURIComponent(providerName)}`);
};


function loadTransactions(provider_name, filter) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const transactionsDiv = document.querySelector(".provider_transactions");
            if (transactionsDiv) {
                transactionsDiv.innerHTML = this.responseText;
                transactionsDiv.dataset.name = provider_name;
                console.log(`Transactions for provider ${provider_name} loaded with filter: ${filter}`);
            }
        }
    }
    xhttp.open("GET", "provider_transactions.php?provider_name=" + encodeURIComponent(provider_name) + "&filter=" + filter, true);
    //xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
};


function GetTickers(letter = '') {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('tickerDetailsContent').innerHTML = this.responseText;
        }
    }
    xhttp.open('GET', PORTFOLIO_API + 'tickers_get.php?letter=' + letter, true);
    xhttp.send();
}

function FindTicker(ticker) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('tickerDetailsContent').innerHTML = this.responseText;
        }
    }
    xhttp.open('GET', PORTFOLIO_API + 'tickers_get.php?ticker=' + ticker, true);
    xhttp.send();
}

function updateTransactionTicker(id, ticker) {
    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_ticker.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&ticker=${ticker}`);
}

function updateTransactionCurrency(id, currency) {
    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_currency.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&currency=${currency}`);
}

function updateTransactionCategory(id, category) {
    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_category.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&category=${category}`);
}

function updateTakeProfit(id, takeProfit) {
    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_take_profit.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&take_profit=${takeProfit}`);
}

function updateStopLoss(id, stopLoss) {
    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_stop_loss.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&stop_loss=${stopLoss}`);
}

function updateTransactionLongShort(id, longShort) {
    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_long_short.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&long_short=${longShort}`);
}

function updateTransactionEntryPrice(id, entryPrice) {
    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_entry_price.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&entry_price=${entryPrice}`);
}

function updateTransactionQuantity(id, quantity) {
    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_quantity.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&quantity=${quantity}`);
}

function updateSpotPerpetual(id, value) {
    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_spot_perpetual.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&spot_perpetual=${value}`);
}

function updateTransactionManualBot(id, manualBot) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const btn = document.querySelector(`tr[data-id="${id}"] button[name="manual_bot"]`);
            if (btn) {
                btn.innerText = manualBot;
            }
        }
    }
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_manual_bot.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&manual_bot=${manualBot}`);
}

function updateTransactionLeverage(id, leverage) {
    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_leverage.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&leverage=${leverage}`);
}

function updateTransactionNote(id, note) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            const btn = document.querySelector(`tr[data-id="${id}"] button[name="notes"]`);
            if (btn) {
                btn.innerText = String(response.note_count);
            }
            document.getElementById('note_text').value = '';
            modalNote.close();
        }
    }
    xhttp.open('POST', PORTFOLIO_API + 'transaction_update_note.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`transaction_id=${id}&note=${encodeURIComponent(note)}`);
}


function createDialogModal(purpose) {
    const dialog = document.createElement('dialog');
    dialog.id = `dialog-${purpose}`;
    document.body.appendChild(dialog);
    return dialog;
}
