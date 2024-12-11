// Popup do dodawania wydarzenia
function openAddEventPopup() {
  document.getElementById("add-event-popup").style.display = "flex";
  document.getElementById("overlay").style.display = "block";
}

function closeAddEventPopup() {
  document.getElementById("add-event-popup").style.display = "none";
  document.getElementById("overlay").style.display = "none";
}

// WYpelnianie formularza do edycji danymi wybranego posta
function openEditPopup(eventId, name, date, location, description) {
  document.getElementById("event-id").value = eventId;
  document.getElementById("event-name").value = name;
  document.getElementById("event-date").value = date;
  document.getElementById("event-location").value = location;
  document.getElementById("event-description").value = description;
  document.getElementById("edit-popup").style.display = "flex";
  document.getElementById("overlay").style.display = "block";
}

// Zamkniecie okna do edycji
function closePopup() {
  document.getElementById("edit-popup").style.display = "none";
  document.getElementById("overlay").style.display = "none";
}

function showForm(selectedForm) {
  const forms = document.querySelectorAll(".form-section");
  forms.forEach((form) => (form.style.display = "none")); // Ukryj wszystkie formularze

  if (selectedForm) {
    document.getElementById(selectedForm).style.display = "block"; // Pokaż wybrany formularz
  }
}

document.addEventListener("DOMContentLoaded", () => {
  // Ukryj wszystkie formularze na starcie
  showForm("");
});

function showForm(selectedForm) {
  const forms = document.querySelectorAll(".form-section");
  forms.forEach((form) => (form.style.display = "none")); // Ukryj wszystkie formularze

  if (selectedForm) {
    document.getElementById(selectedForm).style.display = "block"; // Pokaż wybrany formularz
  }
}
document.addEventListener("DOMContentLoaded", () => {
  // Ukryj wszystkie formularze na starcie
  showForm("");
});

document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".editpost_button");
  const popupModal = document.getElementById("popupModal");
  const overlay = document.getElementById("overlay");
  const cancelEdit = document.getElementById("cancelEdit");

  const editPostIdField = document.getElementById("editPostId");
  const editTitleField = document.getElementById("editTitle");
  const editContentField = document.getElementById("editContent");
  const categoryField = document.getElementById("category_id");

  // Pokazywanie popupu po kliknięciu w przycisk edycji
  editButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const postId = button.getAttribute("data-post-id");
      const postTitle = button.getAttribute("data-post-title");
      const postContent = button.getAttribute("data-post-content");
      const postCategory = button.getAttribute("data-post-category");

      editPostIdField.value = postId;
      editTitleField.value = postTitle;
      editContentField.value = postContent;
      categoryField.value = postCategory;

      popupModal.classList.remove("hidden");
      overlay.classList.remove("hidden");
    });
  });

  // Ukrywanie popupu
  if (cancelEdit) {
    cancelEdit.addEventListener("click", () => {
      popupModal.classList.add("hidden");
      overlay.classList.add("hidden");
    });
  }

  // Zamknięcie popupu po kliknięciu na overlay
  overlay.addEventListener("click", () => {
    popupModal.classList.add("hidden");
    overlay.classList.add("hidden");
  });
});
