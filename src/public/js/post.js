function toggleReplyForm(commentId) {
  const replyForm = document.getElementById("reply-form-" + commentId);
  if (replyForm) {
      replyForm.style.display = replyForm.style.display === "none" || replyForm.style.display === "" ? "block" : "none";
  }
}

function toggleEditForm(typeOrId) {
  const formId = typeOrId === 'post' ? 'edit-post-form' : `edit-comment-form-${typeOrId}`;
  const form = document.getElementById(formId);
  if (form) {
      form.style.display = form.style.display === 'none' ? 'block' : 'none';
  }
}

function toggleEditForm(replyId) {
  const form = document.getElementById('edit-reply-form-' + replyId);
  if (form) {
      form.style.display = form.style.display === 'none' ? 'block' : 'none';
  }
}

