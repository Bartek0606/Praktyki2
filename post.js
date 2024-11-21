// post.js
function checkLoginStatus(event) {
    // Check if the user is logged in by the PHP variable passed into the script
    var isLoggedIn = document.getElementById('isLoggedIn').value === 'true';
    
    // If not logged in, prevent form submission and change the message color
    if (!isLoggedIn) {
        event.preventDefault(); // Prevent form submission
        document.getElementById('login-prompt').style.color = 'red'; // Change message color to red
    }
}
