    function toggleEditForm(messageId) {
        const editForm = document.getElementById(`edit-form-${messageId}`);
        const editButton = document.getElementById(`edit-button-${messageId}`);
        if (editForm.style.display === 'none') {
            editForm.style.display = 'block';
            editButton.style.display = 'none';
        } else {
            editForm.style.display = 'none';
            editButton.style.display = 'inline';
        }
    }
