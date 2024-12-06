document.addEventListener("DOMContentLoaded", function () {
  // Przyciski
  const postsButton = document.getElementById("show-posts");
  const likesButton = document.getElementById("show-likes");
  const eventsButton = document.getElementById("show-events");
  const itemsButton = document.getElementById("show-items");

  // Kontenery
  const postsContainer = document.getElementById("post-container"); // Poprawione ID
  const likesContainer = document.getElementById("likes-container");
  const eventsContainer = document.getElementById("events-container");
  const itemsContainer = document.getElementById("items-container");

  // Funkcja do ukrywania wszystkich kontenerów
  function hideAllContainers() {
    postsContainer.style.display = "none";
    likesContainer.style.display = "none";
    eventsContainer.style.display = "none";
    itemsContainer.style.display = "none";
  }

  // Obsługa przycisków
  if (postsButton) {
    postsButton.addEventListener("click", function () {
      hideAllContainers();
      postsContainer.style.display = "block"; // Pokaż posty
    });
  }

  if (likesButton) {
    likesButton.addEventListener("click", function () {
      hideAllContainers();
      likesContainer.style.display = "block"; // Pokaż polubienia
    });
  }

  if (eventsButton) {
    eventsButton.addEventListener("click", function () {
      hideAllContainers();
      eventsContainer.style.display = "block"; // Pokaż wydarzenia
    });
  }

  if (itemsButton) {
    itemsButton.addEventListener("click", function () {
      hideAllContainers();
      itemsContainer.style.display = "block"; // Pokaż przedmioty
    });
  }
});
