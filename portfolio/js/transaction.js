const add-note-dialog = document.getElementById('add-note-dialog');
const add-note-form = document.getElementById('add-note-form');
const cancel-add-note = document.getElementById('cancel-add-note');


add-note-form.addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(add-note-form);
    const transactionId = formData.get('transaction_id');
    const noteText = formData.get('note_text');
    createNote(transactionId, noteText);
});

cancel-add-note.addEventListener('click', function() {
    add-note-dialog.close();
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