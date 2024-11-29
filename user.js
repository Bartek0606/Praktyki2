document.getElementById("show-posts").addEventListener("click", function () {
  document.getElementById("posts-container").style.display = "block";
  document.getElementById("likes-container").style.display = "none";
});

document.getElementById("show-likes").addEventListener("click", function () {
  document.getElementById("posts-container").style.display = "none";
  document.getElementById("likes-container").style.display = "block";
});
