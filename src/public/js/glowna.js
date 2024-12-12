const slider = document.getElementById("slider");
const prev = document.getElementById("prev");
const next = document.getElementById("next");

// Funkcja przesuwająca slider w lewo lub w prawo
function slide(direction) {
  const scrollAmount = 320; // szerokość karty + odstęp
  slider.scrollLeft += direction === "next" ? scrollAmount : -scrollAmount;
}

prev.addEventListener("click", () => slide("prev"));
next.addEventListener("click", () => slide("next"));
