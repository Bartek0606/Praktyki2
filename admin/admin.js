// Funkcja otwierająca popup dla dodawania wydarzenia
function openAddEventPopup() {
  document.getElementById("add-event-popup").style.display = "flex";
  document.getElementById("overlay").style.display = "block"; // Pokazuje overlay
}

// Funkcja zamykająca popup dla dodawania wydarzenia
function closeAddEventPopup() {
  document.getElementById("add-event-popup").style.display = "none";
  document.getElementById("overlay").style.display = "none"; // Ukrywa overlay
}

// Funkcja otwierająca popup i wypełniająca formularz danymi wydarzenia (do edycji)
function openEditPopup(eventId, name, date, location, description) {
  document.getElementById("event-id").value = eventId;
  document.getElementById("event-name").value = name;
  document.getElementById("event-date").value = date;
  document.getElementById("event-location").value = location;
  document.getElementById("event-description").value = description;
  document.getElementById("edit-popup").style.display = "flex";
  document.getElementById("overlay").style.display = "block"; // Pokazuje overlay
}

// Funkcja zamykająca popup dla edycji
function closePopup() {
  document.getElementById("edit-popup").style.display = "none";
  document.getElementById("overlay").style.display = "none"; // Ukrywa overlay
}
