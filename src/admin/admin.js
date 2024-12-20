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
