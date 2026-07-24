const bug_list = document.querySelector('.bug_list');
const modal_show_status = document.querySelector('.modal_show_status');
const modal_show_priority = document.querySelector('.modal_show_priority');
const new_bug_form = document.querySelector('.new_bug form'); // Assuming this is the form element for adding a new bug
const bug_footer = document.querySelector('.bug_footer');
const modal_add_comment = document.querySelector('.modal_add_comment');
//markdown editor



modal_add_comment.addEventListener("click", function(event) {
    if(event.target.name === "create_comment"){
        const bugId = sessionStorage.getItem('bug_id');
        const commentText = document.querySelector('.modal_add_comment textarea[name="comment_text"]').value;
        if(commentText.trim() === "") {
            alert("Comment cannot be empty.");
            return;
        }
        SaveComment(bugId, commentText);
        document.querySelector('.modal_add_comment textarea[name="comment_text"]').value = "";
        document.querySelector('.modal_add_comment').close();
    }
});


new_bug_form.addEventListener('submit', function(event) {
    event.preventDefault();

    const bugTitle = document.querySelector('input[name="bug_title"]').value;
    const bugDescription = document.querySelector('textarea[name="bug_text"]').value;
    let bugPriority = document.querySelector('select[name="bug_priority"]').value;
    let bugStatus = document.querySelector('select[name="bug_status"]').value;
    
    if (bugDescription === "") {
        alert("Please fill in all required fields.");
        return;
    }

    if (bugPriority === "0") bugPriority = "medium";
    if (bugStatus === "0") bugStatus = "new";

    //console.log(`New bug added: ${bugTitle} - ${bugDescription} - ${bugPriority} - ${bugStatus}`);
    SaveBug(bugTitle, bugDescription, bugPriority, bugStatus);
});

bug_list.addEventListener('click', function(event) {
    const targetClass = event.target.classList;

    if (event.target.tagName === 'DIV' && (targetClass.contains("bug_status") || targetClass.contains("bug_priority"))) {
        const bugId = event.target.closest(".bug").getAttribute('bug-id');
        sessionStorage.setItem('bug_id', bugId);
        console.log(bugId);

        const modal = targetClass.contains("bug_status") ? modal_show_status : modal_show_priority;
        
        if (!modal) return;

        const rect = event.target.getBoundingClientRect();
        
        // Posunutie o 10px doľava
        modal.style.left = `${rect.left + rect.width / 2 - modal.offsetWidth / 2 - 10}px`;
        modal.style.top = `${rect.top - modal.offsetHeight - 20}px`;

        modal.showModal();
    } 
    
    if (event.target.tagName === 'BUTTON') {
        const bugId = event.target.closest(".bug")?.getAttribute('bug-id');
        sessionStorage.setItem('bug_id',bugId);
        if (!bugId) return;
    
        switch (event.target.name) {
            case "see_bug_details":
                window.location.href = `bug.php?bug_id=${bugId}`;
                break;
            case "bug_remove":
                alert("bug_remove");
                break;
            case "to_fixed":
                alert("mark bug as fixed");
                break;
            case "to_reopen":
                alert("reopen bug");
                break;
            case "add_comment":
                console.log("add comment");
                 document.querySelector('.modal_add_comment').showModal()
                break;    
        }
    }
});


modal_show_status.addEventListener('click', function(event) {
if (event.target.tagName === 'LI') {    
    const bugId = sessionStorage.getItem('bug_id');
    const bugStatus = event.target.innerText;
    console.log(`Bug ${bugId} status updated to ${bugStatus}`);
    if(document.querySelector(`.bug[bug-id='${bugId}'] .bug_status`).innerText === "fixed"){
        alert("Cannot change status of a fixed bug.");
        modal_show_status.close();
        return;
        
    } else {
    changeBugStatus(bugId, bugStatus);
    modal_show_status.close();
    }
}
});

modal_show_priority.addEventListener('click', function(event) {
    if (event.target.tagName === 'LI') {    
        const bugId = sessionStorage.getItem('bug_id');
        const bugPriority = event.target.innerText;
        console.log(`Bug ${bugId} status updated to ${bugPriority}`);
        if(document.querySelector(`.bug[bug-id='${bugId}'] .bug_status`).innerText === "fixed"){
            alert("Cannot change priority of a fixed bug.");
            modal_show_priority.close();
            return;
            
        } else {
        changeBugPriority(bugId, bugPriority);
        modal_show_priority.close();
        }
    }
});


function changeBugStatus(bugId, bugStatus) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        // Check if the request is complete and was successful
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(`.bug[bug-id='${bugId}'] .bug_status`).innerText = bugStatus;
           
        }
    };
    xhttp.open("POST", "bugs_change_status.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Send the request with the videoId and modpackId
    var params = "bug_id=" + encodeURIComponent(bugId) + "&bug_status=" + encodeURIComponent(bugStatus);
    xhttp.send(params);
}

function changeBugPriority(bugId, bugPriority) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        // Check if the request is complete and was successful
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(`.bug[bug-id='${bugId}'] .bug_priority`).innerText = bugPriority;
            document.querySelector(`.bug[bug-id='${bugId}'] .bug_priority`).classList.remove("low", "medium", "high", "critical");
            document.querySelector(`.bug[bug-id='${bugId}'] .bug_priority`).classList.add(bugPriority);
            //document.querySelector(`.bug[bug-id='${bugId}'] .bug_priority`).style.border = "1px solid #d1d1d1"; 
        }
    };
    xhttp.open("POST", "bugs_change_priority.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Send the request with the videoId and modpackId
    var params = "bug_id=" + encodeURIComponent(bugId) + "&bug_priority=" + encodeURIComponent(bugPriority);
    xhttp.send(params);
}




function SaveBug(bugTitle, bugDescription, bugPriority, bugStatus) {
    //console.log(`Saving bug: ${bugTitle} - ${bugDescription} - ${bugPriority} - ${bugStatus}`);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
             const response = JSON.parse(this.responseText);
             if(response.message==="Bug created successfully"){
                alert("Bug added successfully!");
                console.log("Bug added successfully!");
                fetchLatestBugs();
            }
        }
    };
    xhttp.open("POST", "bugs_create.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var params = "bug_title=" + encodeURIComponent(bugTitle) + "&bug_description=" + encodeURIComponent(bugDescription) + "&bug_priority=" + encodeURIComponent(bugPriority) + "&bug_status=" + encodeURIComponent(bugStatus);
    xhttp.send(params);
}



function markBugAsFixed(bugId) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        // Check if the request is complete and was successful
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(`.bug[bug-id='${bugId}'] .bug_status`).innerText = "Fixed";    
        }
    }
    xhttp.open("POST", "bugs_mark_as_fixed.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var params = "bug_id=" + encodeURIComponent(bugId);
    xhttp.send(params);
}


function SaveComment(bugId, commentText) {
    var xhttp = new XMLHttpRequest();    
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText === "Comment added successfully") {
                alert("Comment added successfully!");
                console.log("Comment added successfully!");
                
            }
        }
    };
    xhttp.open("POST", "bug_comments_create.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var params = "bug_id=" + encodeURIComponent(bugId) + "&comment_text=" + encodeURIComponent(commentText);
    xhttp.send(params);
    }


    function fetchLatestBugs(){
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const bugs = JSON.parse(this.responseText);
                if (!bugs || bugs.length === 0) return;

                const bug = bugs[0];
                const isFixed = bug.is_fixed == 1;
                const fixedLabel = isFixed ? "<div class='span_fixed'>fixed</div>" : "";
                const addComment = "<button type='button' title='add comment' name='add_comment' class='button small_button'><i class='fa fa-comment'></i></button>";
                const actionButtons = isFixed
                    ? "<button type='button' name='see_bug_details' title='bug details' class='button small_button'><i class='fa fa-eye'></i></button>"
                    : `<button type='button' name='see_bug_details' title='bug details' class='button small_button'><i class='fa fa-eye'></i></button>
                       <button type='button' name='move_to_ideas' title='move to ideas' class='button small_button'><i class='fas fa-chevron-right'></i></button>
                       <button type='button' name='mark_fixed' title='mark as fixed' class='button small_button'><i class='fa fa-check'></i></button>
                       <button type='button' name='bug_remove' title='remove bug' class='button small_button'><i class='fa fa-times'></i></button>
                       ${addComment}`;

                const html = `<div class="bug" bug-id="${bug.bug_id}">
                    <div class="bug_title">${bug.bug_title} ${fixedLabel}</div>
                    <div class="bug_text">${bug.bug_text}</div>
                    <div class="bug_footer">
                        <div class="bug_status ${bug.bug_status}">${bug.bug_status}</div>
                        <div class="bug_priority ${bug.bug_priority}">${bug.bug_priority}</div>
                        <div class="nr_of_comments">${bug.count_comments} comments</div>
                        <div class="bug_action">${actionButtons}</div>
                    </div>
                </div>`;

                document.querySelector('.bug_list').insertAdjacentHTML('afterbegin', html);
                const newBug = document.querySelector(`.bug[bug-id="${bug.bug_id}"]`);
                newBug.style.backgroundColor = "#686143";
                setTimeout(() => {
                    newBug.style.backgroundColor = "#2C2A20";
                }, 2000)
            }
        };
        xhttp.open("GET", "bugs_fetch_latest.php", true);
        xhttp.send();
    }