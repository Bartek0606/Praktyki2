function toggleReplyForm(commentId) {
  const form = document.getElementById(`reply-form-${commentId}`);
  form.style.display = form.style.display === "none" ? "block" : "none";
}

function toggleEditForm(commentId) {
  const form = document.getElementById(`edit-comment-form-${commentId}`);
  form.style.display = form.style.display === "none" ? "block" : "none";
}

function toggleEditReplyForm(commentId) {
    const form = document.getElementById(`edit-reply-form-${commentId}`);
    form.style.display = form.style.display === "none" ? "block" : "none";
}
