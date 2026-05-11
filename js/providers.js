const btnAddNewProvider = document.getElementById('btnAddNewProvider');
const modalAddNewProvider = document.getElementById('modalAddNewProvider');
const modalAddNewProviderSave = document.getElementById('modalAddNewProviderSave');
const modalAddNewProviderClose = document.getElementById('modalAddNewProviderClose');
const providerName = document.getElementById('providerName');
const providerUrl = document.getElementById('providerUrl');
const providerDescription = document.getElementById('providerDescription');
const providerList = document.querySelector('.providers');


providerList.addEventListener('click', function(e) {
    if(e.target && e.target.classList.contains('provider_card')) {
        const providerId = e.target.dataset.providerId;
        getProviderDetails(providerId);
    }
});


btnAddNewProvider.addEventListener('click', () => {
    modalAddNewProvider.showModal();
});

modalAddNewProviderClose.addEventListener('click', () => {
    modalAddNewProvider.close();
});

modalAddNewProvider.addEventListener('click', function(e) {
    if(e.target && e.target.tagName ==="BUTTON") {
        if(e.target.id ==="modalAddNewProviderSave") {
            //save new provider
        } else if(e.target.id ==="modalAddNewProviderClose") {
            modalAddNewProvider.style.display = 'none';
        }
    } 
});


function getProviderDetails(providerId) {
    //fetch provider details using providerId and display in provider_details section
    httpRequest('GET', `api/provider_details.php?providerId=${providerId}`, function(response) {
        const providerDetails = JSON.parse(response);
        const providerDetailsDiv = document.querySelector('.provider_details');
        providerDetailsDiv.innerHTML = `
            <h2>${providerDetails.name}</h2>
            <p>${providerDetails.description}</p>
            <a href="${providerDetails.url}" target="_blank">Visit Website</a>
        `;        
    });    
};