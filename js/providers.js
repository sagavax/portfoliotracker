const btnAddNewProvider = document.getElementById('btnAddNewProvider');
const modalAddNewProvider = document.getElementById('modalAddNewProvider');
const modalAddNewProviderSave = document.getElementById('modalAddNewProviderSave');
const modalAddNewProviderClose = document.getElementById('modalAddNewProviderClose');
const providerName = document.getElementById('providerName');
const providerUrl = document.getElementById('providerUrl');
const providerDescription = document.getElementById('providerDescription');
const providerList = document.querySelector('.providers');
const providerDetails = document.querySelector('.provider_details');
const modalProviderLogoEditor = document.getElementById('modalProviderLogoEditor');
const edit_provider_url = document.querySelector('button[name=edit_provider_url]');


if (modalAddNewProviderSave){
    modalAddNewProviderSave.addEventListener('click', () => {
        const providerName = document.getElementById('provider_name').value;
        const providerUrl = document.getElementById('provider_url').value;
        const providerLogo = document.getElementById('provider_logo').value;
        const providerDescription = document.getElementById('provider_description').innerText;
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
    });
}




providerList.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('provider_card')) {
        const providerId = e.target.dataset.id;
        getProviderDetails(providerId);
    }
});


if (btnAddNewProvider && modalAddNewProvider) {
    btnAddNewProvider.addEventListener('click', () => {
        modalAddNewProvider.showModal();
    });
}

if (modalAddNewProvider) {
    modalAddNewProviderClose.addEventListener('click', () => {
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


function getProviderDetails(providerId) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
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
        }
    }
    xhttp.open("POST", "providers_add_new.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    const data = `providerName=${providerName}&providerUrl=${providerUrl}&providerLogo=${providerLogo}&providerDescription=${providerDescription}`;
    xhttp.send(data);
}
