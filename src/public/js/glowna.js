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


