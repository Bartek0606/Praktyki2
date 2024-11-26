document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".editpost_button");
  const popupModal = document.getElementById("popupModal");
  const closePopup = document.getElementById("closePopup");
  const overlay = document.getElementById("overlay");
  const editForm = document.getElementById("editForm");
  let currentPostId = null; // Bieżące ID posta

  // Kliknięcie w przycisk "Edytuj"
  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      currentPostId = button.getAttribute("data-post-id"); // Pobierz ID posta
      popupModal.style.display = "block";
      overlay.style.display = "block";

      // Opcjonalnie możesz dynamicznie załadować dane posta (np. AJAX).
    });
  });

  // Zamknięcie popupu
  closePopup.addEventListener("click", function () {
    popupModal.style.display = "none";
    overlay.style.display = "none";
  });

  overlay.addEventListener("click", function () {
    popupModal.style.display = "none";
    overlay.style.display = "none";
  });

  // Wysłanie formularza edycji
  editForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Pobierz wybrane dane
    const selectedCategory = document.getElementById("editCategory").value;

    // Wykonaj żądanie AJAX do edycji kategorii
    fetch("update_post.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        post_id: currentPostId, // Przekaż ID posta
        category_id: selectedCategory, // Nowa kategoria
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Category updated successfully!");
          location.reload(); // Odśwież stronę
        } else {
          alert("Error updating category: " + data.error);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });

    // Zamknij popup
    popupModal.style.display = "none";
    overlay.style.display = "none";
  });
});
