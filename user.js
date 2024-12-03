document.addEventListener("DOMContentLoaded", function () {
  const postsButton = document.getElementById("show-posts");
  const likesButton = document.getElementById("show-likes");
  const eventsButton = document.getElementById("show-events");

  const postsContainer = document.getElementById("posts-container");
  const likesContainer = document.getElementById("likes-container");
  const eventsContainer = document.getElementById("events-container");

  if (postsButton) {
    postsButton.addEventListener("click", function () {
      postsContainer.style.display = "block";
      if (likesContainer) likesContainer.style.display = "none";
      if (eventsContainer) eventsContainer.style.display = "none";
    });
  }

  if (likesButton) {
    likesButton.addEventListener("click", function () {
      if (postsContainer) postsContainer.style.display = "none";
      likesContainer.style.display = "block";
      if (eventsContainer) eventsContainer.style.display = "none";
    });
  }

  if (eventsButton) {
    eventsButton.addEventListener("click", function () {
      if (postsContainer) postsContainer.style.display = "none";
      if (likesContainer) likesContainer.style.display = "none";
      eventsContainer.style.display = "block";
    });
  }
});
