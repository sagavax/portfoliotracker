const add_note_dialog = document.getElementById('add-note-dialog');
const add_note_form = document.getElementById('add-note-form');
const cancel_add_note = document.getElementById('cancel-add-note');


add_note_form.addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(add_note_form);
    const transactionId = formData.get('transaction_id');
    const noteText = formData.get('note_text');
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
            add-note-dialog.close();
            location.reload(); // Reload the page to show the new note
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