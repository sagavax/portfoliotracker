const notes_header = document.getElementById("notes_header");
const notes = document.querySelector(".notes");
const search_wrapper_input = document.querySelector(".search_wrapper input");
const modalAddNote = document.getElementById("modalAddNote");

search_wrapper_input.addEventListener("input", function() {
    const searchValue = this.value.toLowerCase();
    searchNotes(searchValue);
});


notes.addEventListener("input", function(e) {
    if(e.target && e.target.matches(".note p")) {
        const noteText = e.target.innerHTML.trim();
        const noteId = e.target.closest(".note").getAttribute("data-note-id");
        updateNote(noteId, noteText);
    }
})



notes.addEventListener("click", function(e) {
    if(e.target && e.target.tagName === "BUTTON" && e.target.name === "add_note") {
        document.querySelector("#modalAddNote").showModal(); 
    }
})


modalAddNote.addEventListener('click', function(e) {
    if(e.target && e.target.tagName === "BUTTON" && e.target.id === "btnaddNote") {
        const textarea = modalNote.querySelector('tnote_text');
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


function searchNotes(searchValue) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            notes.innerHTML = this.responseText;
        }
    }
    xhttp.open("POST", "notes_search.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`search_value=${searchValue}`);
}


/* function updateTransactionNote(id, note) {
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
 */
function updateNote(id, noteText) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    }
    xhttp.open("POST", "notes_update.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`noteId=${id}&noteText=${encodeURIComponent(noteText)}`);
}