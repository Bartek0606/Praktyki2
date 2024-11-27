function toggleReplyForm(commentId) {
  const replyForm = document.getElementById("reply-form-" + commentId);
  replyForm.style.display =
    replyForm.style.display === "none" || replyForm.style.display === ""
      ? "block"
      : "none";
}
