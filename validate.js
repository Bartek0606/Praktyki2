document.getElementById("register-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Zatrzymuje domyślne wysyłanie formularza

    // Pobieranie danych z formularza
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;
    
    // Pobieramy miejsca na komunikaty błędów
    const emailError = document.getElementById("email-error");
    const passwordError = document.getElementById("password-error");

    // Czyszczenie komunikatów błędów
    emailError.textContent = '';
    passwordError.textContent = '';
    
    let hasErrors = false;

    // Walidacja e-maila
    if (!email.includes("@")) {
        emailError.textContent = "Email musi zawierać znak '@'.";
        emailError.style.color = "red";
        hasErrors = true;
    }

    // Walidacja haseł
    if (password !== confirmPassword) {
        passwordError.textContent = "Hasła muszą być takie same.";
        passwordError.style.color = "red";
        hasErrors = true;
    }

    // Jeśli nie ma błędów, można wysłać formularz (po przejściu wszystkich walidacji)
    if (!hasErrors) {
        this.submit(); // Wysyła formularz
    }
});
