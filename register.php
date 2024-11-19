<?php
// Zmienna przechowująca komunikaty o błędach
$emailError = '';
$passwordError = '';
$confirmPasswordError = '';
$fieldsError = false; // Flaga, która wskazuje czy jakieś pole jest puste

// Sprawdzanie danych po wysłaniu formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdzanie, czy pola nie są puste
    if (empty($_POST['email']) || empty($_POST['fullname']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        $fieldsError = true;
    }

    // Walidacja e-maila
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $emailError = "Niepoprawny adres e-mail.";
    }

    // Walidacja haseł
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $passwordError = "Hasła nie pasują do siebie.";
    }

    // Jeśli nie ma błędów, przetwarzamy dane (np. zapisujemy do bazy)
    if (!$fieldsError && empty($emailError) && empty($passwordError)) {
        // Tutaj można zapisać użytkownika do bazy danych
        // Na razie tylko komunikat sukcesu
        echo "Rejestracja zakończona sukcesem!";
        // Po poprawnej rejestracji, nie wyświetlamy nic, strona nie będzie odświeżana
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up • HobbyHub</title>
    <link rel="stylesheet" href="register.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div id="container">
    <div id="logo">
        <h1>HobbyHub</h1>
    </div>
    <div id="form_login">
        <form id="registerForm" method="POST" action="register.php">
            <input type="text" id="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"><br>
            <?php if ($emailError): ?>
                <p class="error"><?php echo $emailError; ?></p>
            <?php endif; ?>
            <input type="text" id="fullname" name="fullname" placeholder="Full Name" value="<?php echo isset($_POST['fullname']) ? $_POST['fullname'] : ''; ?>"><br>
            <input type="text" id="username" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>"><br>
            <input type="password" id="password" name="password" placeholder="Password"><br>
            <?php if ($passwordError): ?>
                <p class="error"><?php echo $passwordError; ?></p>
            <?php endif; ?>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password"><br>
            <?php if ($fieldsError): ?>
                <p class="error">Wszystkie pola są wymagane.</p>
            <?php endif; ?>
            <button class="button-10" role="button" type="submit">Sign up</button>
        </form>
    </div>
</div>

<div id="container_register">
    <p>Have an account? <a href="login.php">Log in</a></p>
</div>

<!-- Add JavaScript to handle validation and form submission -->
<script>
document.getElementById('registerForm').addEventListener('submit', function(event) {
    // Get all the form fields
    const email = document.getElementById('email').value;
    const fullname = document.getElementById('fullname').value;
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    let valid = true;

    // Reset error messages
    document.querySelectorAll('.error').forEach(function(error) {
        error.remove();
    });

    // Check if any field is empty
    if (!email || !fullname || !username || !password || !confirmPassword) {
        valid = false;
        showError('Wszystkie pola są wymagane.');
    }

    // Check if email is valid
    if (!validateEmail(email)) {
        valid = false;
        showError('Niepoprawny adres e-mail.', 'email');
    }

    // Check if passwords match
    if (password !== confirmPassword) {
        valid = false;
        showError('Hasła nie pasują do siebie.', 'password');
    }

    // If not valid, prevent form submission
    if (!valid) {
        event.preventDefault();
    }
});

// Email validation function
function validateEmail(email) {
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return regex.test(email);
}

// Function to display error messages
function showError(message, fieldId = '') {
    const errorElement = document.createElement('p');
    errorElement.classList.add('error');
    errorElement.textContent = message;

    if (fieldId) {
        const field = document.getElementById(fieldId);
        field.insertAdjacentElement('afterend', errorElement);
    } else {
        const form = document.getElementById('registerForm');
        form.insertAdjacentElement('afterbegin', errorElement);
    }
}
</script>

</body>
</html>
