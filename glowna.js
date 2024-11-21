const slider = document.querySelector(".event-slider");
const leftBtn = document.querySelector(".left-btn");
const rightBtn = document.querySelector(".right-btn");

// Funkcja przesuwająca slider
function scrollSlider(direction) {
  const scrollAmount = 270; // Ilość przesunięcia
  if (direction === "left") {
    slider.scrollBy({ left: -scrollAmount, behavior: "smooth" });
  } else if (direction === "right") {
    slider.scrollBy({ left: scrollAmount, behavior: "smooth" });
  }
}

// Obsługa kliknięć strzałek
leftBtn.addEventListener("click", () => scrollSlider("left"));
rightBtn.addEventListener("click", () => scrollSlider("right"));

// Toggle dropdown menu visibility
function toggleDropdown() {
  const menu = document.getElementById("dropdownMenu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Close dropdown if clicked outside
window.onclick = function (event) {
  if (!event.target.matches(".dropdown-button")) {
    const dropdowns = document.getElementsByClassName("dropdown-menu");
    for (let i = 0; i < dropdowns.length; i++) {
      dropdowns[i].style.display = "none";
    }
  }
};
