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


document.addEventListener('DOMContentLoaded', () => {
    const popupContainer = document.getElementById('popup-container');
    const popupContent = document.getElementById('popup-content');
    const popupTitle = document.getElementById('popup-title');
    const closePopup = document.getElementById('close-popup');

    // Funkcja do otwierania popupu
    function openPopup(title, type) {
        popupTitle.textContent = title;
        popupContent.innerHTML = "<p class='text-gray-400'>Loading...</p>";

        // Pobranie danych z serwera
        fetch(`fetch_followers_following.php?type=${type}&user_id=<?php echo $profileUserId; ?>`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const list = data.map(user => `
                        <div class="flex items-center space-x-4 p-2 hover:bg-gray-600 rounded-md">
                            <img src="${user.profile_picture}" alt="${user.username}'s profile picture" class="w-12 h-12 rounded-full">
                            <div>
                                <p class="text-white font-semibold">${user.username}</p>
                                <p class="text-gray-400">${user.full_name || ''}</p>
                            </div>
                        </div>
                    `).join('');
                    popupContent.innerHTML = list;
                } else {
                    popupContent.innerHTML = "<p class='text-gray-400'>No users to display.</p>";
                }
            })
            .catch(error => {
                popupContent.innerHTML = "<p class='text-red-500'>An error occurred while loading data.</p>";
                console.error(error);
            });

        popupContainer.classList.remove('hidden');
    }

    // Obsługa zamykania popupu
    closePopup.addEventListener('click', () => {
        popupContainer.classList.add('hidden');
    });

    // Obsługa kliknięcia w "Followers" i "Following"
    document.getElementById('show-followers').addEventListener('click', () => {
        openPopup('Followers', 'followers');
    });

    document.getElementById('show-following').addEventListener('click', () => {
        openPopup('Following', 'following');
    });
});

