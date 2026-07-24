const ideas_list = document.querySelector('.ideas_list');
const modal_show_status = document.querySelector('.modal_show_status');
const modal_show_priority = document.querySelector('.modal_show_priority');
const new_idea_form = document.querySelector('.new_idea form');




new_idea_form.addEventListener('submit', function(event) {
    event.preventDefault();
    const ideaTitle = document.getElementById('idea_title').value;
    const ideaText = document.getElementById('idea_text').value;
    
    if (ideaText.trim() === '') {
        alert("Please fill in the description of the idea.");
        return;
    }

    let ideaPriority = document.querySelector('select[name="idea_priority"]').value;
    let ideaStatus = document.querySelector('select[name="idea_status"]').value;

    if (ideaPriority === '0') ideaPriority = 'medium';
    if (ideaStatus === '0') ideaStatus = 'new';

    saveNewIdea(ideaTitle, ideaText, ideaPriority, ideaStatus);
});





ideas_list.addEventListener('click', function(event) {
    const targetClass = event.target.classList;

    if (event.target.tagName === 'DIV' && (targetClass.contains("idea_status") || targetClass.contains("idea_priority"))) {
        const ideaId = event.target.closest(".idea").getAttribute('idea-id');
        sessionStorage.setItem('idea_id', ideaId);
        console.log(ideaId);

        const modal = targetClass.contains("idea_status") ? modal_show_status : modal_show_priority;
        
        if (!modal) return;

        const rect = event.target.getBoundingClientRect();
        
        // Posunutie o 10px doľava
        modal.style.left = `${rect.left + rect.width / 2 - modal.offsetWidth / 2 - 10}px`;
        modal.style.top = `${rect.top - modal.offsetHeight - 20}px`;
        modal.showModal();

    } else if (event.target.tagName==="BUTTON" || event.target.tagName==="I") {
        const button = event.target.tagName === "I" ? event.target.closest("button") : event.target;
        //sconsole.log(event.target.tagName);
        const ideaId = event.target.closest(".idea").getAttribute('idea-id');
        //console.log(ideaId);
        sessionStorage.setItem('idea_id', ideaId);
        
        if(button.name==="see_idea_details"){
            window.location.href = `idea.php?idea_id=${ideaId}`;
        } else if (button.name==="delete_idea"){
            alert(`Idea ${ideaId} deleted.`);
            deleteIdea(ideaId);
            //document.querySelector(`.idea[idea-id='${ideaId}']`).remove();
        } else if (button.name==="to_apply"){
            alert(`Idea ${ideaId} moved to the Apply section.`);
            moveIdeaToApply(ideaId);
            //document.querySelector(`.idea[idea-id='${ideaId}']`).remove();
        } else if (button.name==="to_review"){
            //for the future use
        }
    }
});


modal_show_status.addEventListener('click', function(event) {
if (event.target.tagName === 'LI') {    
    const ideaId = sessionStorage.getItem('idea_id');
    const ideastatus = event.target.innerText;
    console.log(`idea ${ideaId} status updated to ${ideastatus}`);
    if(document.querySelector(`.idea[idea-id='${ideaId}'] .idea_status`).innerText === "fixed"){
        alert("Cannot change status of a fixed idea.");
        modal_show_status.close();
        return;
        
    } else {
    changeideastatus(ideaId, ideastatus);
    modal_show_status.close();
    }
}
});

modal_show_priority.addEventListener('click', function(event) {
    if (event.target.tagName === 'LI') {    
        const ideaId = sessionStorage.getItem('idea_id');
        const ideaPriority = event.target.innerText;
        console.log(`idea ${ideaId} status updated to ${ideaPriority}`);
        if(document.querySelector(`.idea[idea-id='${ideaId}'] .idea_status`).innerText === "fixed"){
            alert("Cannot change priority of a fixed idea.");
            modal_show_priority.close();
            return;
            
        } else {
        changeideaPriority(ideaId, ideaPriority);
        modal_show_priority.close();
        }
    }
});


function changeideastatus(ideaId, ideastatus) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        // Check if the request is complete and was successful
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(`.idea[idea-id='${ideaId}'] .idea_status`).innerText = ideastatus;
           
        }
    };
    xhttp.open("POST", "ideas_change_status.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Send the request with the videoId and modpackId
    var params = "idea_id=" + encodeURIComponent(ideaId) + "&idea_status=" + encodeURIComponent(ideastatus);
    xhttp.send(params);
}

function changeideaPriority(ideaId, ideaPriority) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        // Check if the request is complete and was successful
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(`.idea[idea-id='${ideaId}'] .idea_priority`).innerText = ideaPriority;
            document.querySelector(`.idea[idea-id='${ideaId}'] .idea_priority`).classList.remove("low", "medium", "high", "critical");
            document.querySelector(`.idea[idea-id='${ideaId}'] .idea_priority`).classList.add(ideaPriority);
            //document.querySelector(`.idea[idea-id='${ideaId}'] .idea_priority`).style.border = "1px solid #d1d1d1"; 
        }
    };
    xhttp.open("POST", "ideas_change_priority.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Send the request with the videoId and modpackId
    var params = "idea_id=" + encodeURIComponent(ideaId) + "&idea_priority=" + encodeURIComponent(ideaPriority);
    xhttp.send(params);
}


function addNewComment(ideaId) {
    document.queryselector('.modal_add_comment').showModal();
}

function deleteIdea(ideaId) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        // Check if the request is complete and was successful
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(`.idea[idea-id='${ideaId}']`).remove();
        }
    };
    xhttp.open("POST", "ideas_delete.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var params = "idea_id=" + encodeURIComponent(ideaId);
    xhttp.send(params);
}


function moveIdeaToApply($ideaId) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        // Check if the request is complete and was successful
        if (this.readyState == 4 && this.status == 200) {
            //document.querySelector(`.idea[idea-id='${ideaId}']`).remove();
            alert("Idea moved to the Apply section.");
            window.location.reload();
        }
    };
    xhttp.open("POST", "ideas_move_to_apply.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var params = "idea_id=" + encodeURIComponent($ideaId);
    xhttp.send(params);
}


function saveNewIdea(ideaTitle, ideaText, ideaPriority, ideaStatus) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            if(response.success === "idea created successfully") {
                alert("Idea added successfully!");
                fetchLatestIdeas();
            }
        }
    };
    xhttp.open("POST", "ideas_create.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var params = "idea_title=" + encodeURIComponent(ideaTitle) + "&idea_text=" + encodeURIComponent(ideaText) + "&idea_priority=" + encodeURIComponent(ideaPriority) + "&idea_status=" + encodeURIComponent(ideaStatus);
    xhttp.send(params);
}

function fetchLatestIdeas(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const ideas = JSON.parse(this.responseText);
            if (!ideas || ideas.length === 0) return;

            const idea = ideas[0];
            const isApplied = idea.is_implemented == 1;
            const actionButtons = isApplied
                ? "<div class='span_modpack'>applied</div>"
                : `<button type='button' name='delete_idea' class='button small_button'><i class='fa fa-times'></i></button>
                   <button type='button' name='to_apply' class='button small_button'><i class='fa fa-check'></i></button>`;

            const html = `<div class="idea" idea-id="${idea.idea_id}">
                <div class="idea_title">${idea.idea_title}</div>
                <div class="idea_text">${idea.idea_text}</div>
                <div class="idea_footer">
                    <input type="hidden" name="idea_id" value="${idea.idea_id}">
                    <input type="hidden" name="is_applied" value="${idea.is_implemented}">
                    <div class="nr_of_comments">${idea.count_comments} comment(s)</div>
                    <div class="idea_status">${idea.idea_status}</div>
                    <div class="idea_priority">${idea.idea_priority}</div>
                    <button type='button' name='see_idea_details' class='button small_button'><i class='fa fa-eye'></i></button>
                    ${actionButtons}
                </div>
            </div>`;

            document.querySelector('.ideas_list').insertAdjacentHTML('afterbegin', html);
             document.querySelector('.ideas_list').insertAdjacentHTML('afterbegin', html);
                const newBug = document.querySelector(`.idea[idea-id="${idea.idea_id}"]`);
                newBug.style.backgroundColor = "#686143";
                setTimeout(() => {
                    newBug.style.backgroundColor = "#2C2A20";
                }, 2000)
        }
    };
    xhttp.open("GET", "ideas_fetch_latest.php", true);
    xhttp.send();
}