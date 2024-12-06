document.addEventListener("DOMContentLoaded", function () {
  const postsButton = document.getElementById("show-posts");
  const likesButton = document.getElementById("show-likes");
  const eventsButton = document.getElementById("show-events");
  const itemsButton = document.getElementById("show-items");

  const postsContainer = document.getElementById("posts-container");
  const likesContainer = document.getElementById("likes-container");
  const eventsContainer = document.getElementById("events-container");
  const itemsContainer = document.getElementById("items-container");

  function hideAllContainers() {
    postsContainer.style.display = "none";
    likesContainer.style.display = "none";
    eventsContainer.style.display = "none";
    itemsContainer.style.display = "none";
  }

  if (postsButton) {
    postsButton.addEventListener("click", function () {
      hideAllContainers();
      postsContainer.style.display = "block";
    });
  }

  if (likesButton) {
    likesButton.addEventListener("click", function () {
      hideAllContainers();
      likesContainer.style.display = "block";
    });
  }

  if (eventsButton) {
    eventsButton.addEventListener("click", function () {
      hideAllContainers();
      eventsContainer.style.display = "block";
    });
  }

  if (itemsButton) {
    itemsButton.addEventListener("click", function () {
      hideAllContainers();
      itemsContainer.style.display = "block";
    });
  }
});
