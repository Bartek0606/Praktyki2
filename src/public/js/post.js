function toggleReplyForm(commentId) {
  const replyForm = document.getElementById("reply-form-" + commentId);
  const editForm = document.getElementById("edit-comment-form-" + commentId);

  if (replyForm && (!editForm || editForm.style.display === "none")) {
    replyForm.style.display =
      replyForm.style.display === "none" || replyForm.style.display === ""
        ? "block"
        : "none";
  }
}

function toggleEditForm(commentId) {
  const form = document.getElementById("edit-comment-form-" + commentId);
  const replyForm = document.getElementById("reply-form-" + commentId);

  if (form && (!replyForm || replyForm.style.display === "none")) {
    form.style.display = form.style.display === "none" ? "block" : "none";
  }
}
