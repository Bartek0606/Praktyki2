function validateForm() {
  var imageInput = document.getElementById("image");
  if (!imageInput.files.length) {
    // Show the error modal
    document.getElementById("errorModal").classList.remove("hidden");
    return false;
  }
  return true;
}
