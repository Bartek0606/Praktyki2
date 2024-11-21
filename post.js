function checkLoginStatus(event) {
  // Check if the user is logged in by the PHP variable passed into the script
  var isLoggedIn = document.getElementById("isLoggedIn").value === "true";

  // If not logged in, prevent form submission, change the message color, and scroll to the error message
  if (!isLoggedIn) {
    event.preventDefault(); // Prevent form submission

    // Scroll to the error message
    var errorMessage = document.getElementById("error-message");
    if (errorMessage) {
      errorMessage.scrollIntoView({ behavior: "smooth", block: "center" });
    }
  }
}
