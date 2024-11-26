document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".editpost_button");
  const popupModal = document.getElementById("popupModal");
  const overlay = document.getElementById("overlay");
  const cancelEdit = document.getElementById("cancelEdit");

  // Pokaż popup
  editButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const postId = button.getAttribute("data-post-id");
      window.location.href = "?edit_post_id=" + postId;
    });
  });

  // Obsługa anulowania edycji
  if (cancelEdit) {
    cancelEdit.addEventListener("click", () => {
      popupModal.style.display = "none";
      overlay.style.display = "none";
      window.location.href = "admin.php";
    });
  }
});
