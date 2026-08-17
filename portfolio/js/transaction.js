const add_note_dialog = document.getElementById('add-note-dialog');
const add_note_form = document.getElementById('add-note-form');
const cancel_add_note = document.getElementById('cancel-add-note');
const add_note_button = document.querySelector('button[name="add_note"]');
const transaction_notes_container = document.getElementById('transaction-notes-container');


add_note_button.addEventListener('click', function() {
    add_note_dialog.showModal();
});

transaction_notes_container.addEventListener('click', function(event) {
    if (event.target.name === 'edit_note') {
        const noteId = event.target.getAttribute('data-note-id');
        const note = document.querySelector(`.transaction-note[data-note-id='${noteId}'] .transaction-note-text`);
        note.contentEditable = true;
        note.focus();
    } else if (event.target.name === 'delete_note') {
        const noteId = event.target.getAttribute('data-note-id');
        removeNote(noteId);
    }
});

add_note_form.addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(add_note_form);
    const transactionId = formData.get('transaction_id');
    const noteText = formData.get('note_text');
    //sanitize noteText is empty
    if (!noteText.trim()) {
        alert('Poznámka nemôže byť prázdna.');
        return;
    }
    createNote(transactionId, noteText);
});

cancel_add_note.addEventListener('click', function() {
    add_note_dialog.close();
});



function createNote(transactionId, noteText) {
    fetch('transaction_note_create.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ transaction_id: transactionId, note_text: noteText })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Note created successfully, you can update the UI or show a success message
            add_note_dialog.close();
            // Optionally, you can append the new note to the list of notes without reloading the page, injsr
            const note_id = data.note_id;
            const created_at = data.created_at;
            const parentDiv = document.getElementById('transaction-notes-container');
            parentDiv.innerHTML += `
                <div class='transaction-note' data-note-id='${note_id}'><div class='transaction-note-text'>${noteText}</div>                                
                    <div class='transaction-note-actions'>
                        <div class='transaction-note-meta'>Vytvorené dňa: ${created_at}</div><button type='button' class='secondary' name='edit_note' data-note-id='${note_id}'><i class='fas fa-edit'></i> upraviť</button>
                         <button type='button' class='secondary' name='delete_note' data-note-id='${note_id}'><i class='fas fa-trash'></i> remove</button>
                    </div>
               </div>`; 

            //location.reload(); // Reload the page to show the new note
        } else {
            // Handle error, show an error message
            alert('Error creating note: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the note.');
    });
}    


function removeNote(noteId) {
    if (confirm('Naozaj chcete odstrániť túto poznámku?')) {
        fetch('transaction_note_delete.php', {  
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ note_id: noteId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Note deleted successfully, you can update the UI or show a success message
                const note = document.querySelector(`.transaction-note[data-note-id='${noteId}']`);
                if (note) {
                    note.remove();
                }
            } else {
                // Handle error, show an error message
                alert('Error deleting note: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the note.');
        });
    }
}