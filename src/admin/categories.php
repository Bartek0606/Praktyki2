<?php
// Ładujemy plik logiki
include __DIR__ . '/logic/categories_logic.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function showForm(selectedForm) {
            const forms = document.querySelectorAll('.form-section');
            forms.forEach(form => form.style.display = 'none'); // Ukryj wszystkie formularze

            if (selectedForm) {
                document.getElementById(selectedForm).style.display = 'block'; // Pokaż wybrany formularz
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            // Ukryj wszystkie formularze na starcie
            showForm('');
        });
    </script>
</head>

<body class="bg-gray-50 text-gray-700">
<div class="admin-panel">
    <?php echo $sidebar->getSidebarHtml(); ?>

    <?php // Ładujemy widok
include __DIR__ . '/Views/categories_view.php'; ?>
</div>
</body>
</html>
