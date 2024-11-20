document.addEventListener("DOMContentLoaded", () => {
  const reviews = document.querySelectorAll(".review");
  const prevButton = document.querySelector(".reviews-prev-button");
  const nextButton = document.querySelector(".reviews-next-button");
  const reviewsContainer = document.querySelector(".reviews-container");
  let currentReview = 0;

  // Funkcja do aktualizacji aktywnej opinii
  function updateReview() {
    // Usuwamy klasę "active" ze wszystkich opinii
    reviews.forEach((review) => review.classList.remove("active"));
    // Dodajemy klasę "active" do obecnej opinii
    reviews[currentReview].classList.add("active");
    // Przesuwamy kontener, aby pokazać odpowiednią opinię
    reviewsContainer.style.transform = `translateX(-${currentReview * 100}%)`;
  }

  // Funkcja do przejścia do poprzedniej opinii
  prevButton.addEventListener("click", () => {
    currentReview = (currentReview - 1 + reviews.length) % reviews.length;
    updateReview();
  });

  // Funkcja do przejścia do następnej opinii
  nextButton.addEventListener("click", () => {
    currentReview = (currentReview + 1) % reviews.length;
    updateReview();
  });

  // Ustawienie początkowej opinii
  updateReview();
});
