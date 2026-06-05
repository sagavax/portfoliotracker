const btnAddNewProvider = document.getElementById('btnAddNewProvider');
const modalAddNewProvider = document.getElementById('modalAddNewProvider');
const modalAddNewProviderSave = document.getElementById('modalAddNewProviderSave');
const modalAddNewProviderClose = document.getElementById('modalAddNewProviderClose');
const providerName = document.getElementById('providerName');
const providerUrl = document.getElementById('providerUrl');
const providerDescription = document.getElementById('providerDescription');
const providerList = document.querySelector('.providers');
const providerDetails = document.querySelector('.provider_details');




if (providerDetails) {
    providerDetails.addEventListener('input', function(e) {
        if(e.target && e.target.classList.contains('provider_description')) {
            const providerId = e.target.closest('.provider_details').dataset.id;
            const updatedDescription = e.target.innerText;
            //save updated description to database using AJAX
            updateProviderDescription(providerId, updatedDescription);
        }
    });
}



providerList.addEventListener('click', function(e) {
    if(e.target && e.target.classList.contains('provider_card')) {
        const providerId = e.target.dataset.id;
        getProviderDetails(providerId);
    }
});


if (btnAddNewProvider && modalAddNewProvider) {
    btnAddNewProvider.addEventListener('click', () => {
        modalAddNewProvider.showModal();
    });
}

if(modalAddNewProvider) {
modalAddNewProviderClose.addEventListener('click', () => {
    modalAddNewProvider.close();
});
}

if(modalAddNewProvider) {
modalAddNewProvider.addEventListener('click', function(e) {
    if(e.target && e.target.tagName ==="BUTTON") {
        if(e.target.id ==="modalAddNewProviderSave") {
            //save new provider
        } else if(e.target.id ==="modalAddNewProviderClose") {
            modalAddNewProvider.style.display = 'none';
        }
    } 
});
}

function httpRequest(method, url, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            callback(xhr.responseText);
        }
    };
    xhr.send();
};


function getProviderDetails(providerId) {
  const xhttp = new XMLHttpRequest();
   xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const detailsDiv = document.querySelector(".provider_details");
            if (detailsDiv) {
                detailsDiv.innerHTML = this.responseText;
                detailsDiv.dataset.id = providerId;
            }
        }
    }
    xhttp.open("GET", "provider_details.php?providerId=" + providerId, true);
    //xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
};



function updateProviderDescription(providerId, description) {
    const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert("Provider description updated successfully!");
        }
    }
    xhttp.open("POST", "provider_description_update.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`provider_id=${providerId}&description=${description}`);
};