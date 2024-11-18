Opis Projektu:

Platforma Społecznościowa dla Hobbystów to strona, na której użytkownicy mogą tworzyć profile, publikować posty oraz zdjęcia, a także wchodzić w interakcje z innymi użytkownikami. Platforma umożliwia wyszukiwanie treści według tematów (np. fotografia, podróże, kulinaria), co ułatwia nawiązywanie kontaktów z osobami o podobnych zainteresowaniach.
Główne Funkcjonalności:

    Rejestracja i logowanie użytkowników (autoryzacja i autentykacja).
    Profile użytkowników – informacje, zdjęcie profilowe, opis.
    Publikowanie postów i zdjęć – użytkownicy mogą tworzyć posty z tekstem i zdjęciami, które widzą inni.
    Komentowanie i lajkowanie postów – interakcje między użytkownikami.
    Przeglądanie treści według kategorii zainteresowań – np. fotografia, sztuka, podróże.
    Wyszukiwarka – wyszukiwanie użytkowników, tematów i postów.
    Powiadomienia – o nowych komentarzach, lajkach, itp.

Technologie:

    Frontend: HTML, CSS, React, JavaScript
    Backend: PHP (logika aplikacji, autoryzacja)
    Baza danych: MySQL (przechowywanie danych o użytkownikach, postach, lajkach, itp.)

Podział pracy dla zespołu:
1. Frontend Developer 1: Profile użytkowników i system postów

    Tworzenie widoku profilu użytkownika, który zawiera:
        Zdjęcie profilowe, imię, bio.
        Listę postów użytkownika.
    Projektowanie systemu postów:
        Wyświetlanie treści postu (tekst i zdjęcia).
        Formularz do dodawania nowych postów.
    Użycie CSS i React do stylizacji i komponentów.

2. Frontend Developer 2: Strona główna, wyszukiwanie i przeglądanie treści według kategorii

    Projektowanie strony głównej:
        Wyświetlanie postów użytkowników, sortowanie według daty, popularności itp.
    System kategorii:
        Filtrowanie postów według tematów (fotografia, podróże, sztuka).
        Implementacja tagów i filtrowanie postów na stronie głównej.
    Implementacja wyszukiwarki:
        Wyszukiwanie po słowach kluczowych (temat, użytkownik).
    Praca z komponentami React, CSS.

3. Backend Developer: Autoryzacja, publikowanie postów i interakcje

    Tworzenie logiki backendowej dla:
        Rejestracji i logowania użytkowników (PHP + MySQL).
        Obsługi sesji użytkowników (logowanie, wylogowanie).
    Logika dodawania i edycji postów:
        Obsługa zapisywania postów (treść i zdjęcia) w bazie danych.
    System lajków i komentarzy:
        Implementacja dodawania komentarzy oraz lajkowania postów.
    Walidacja danych wejściowych (np. uniknięcie spamowania).

4. Administrator bazy danych i Backend Developer 2: Struktura bazy danych, powiadomienia i zarządzanie

    Projektowanie struktury bazy danych:
        Tabele użytkowników, postów, komentarzy, lajków, powiadomień.
    Implementacja powiadomień:
        System powiadomień (np. o nowych komentarzach, lajkach), które użytkownicy widzą na swoim profilu.
    Zarządzanie relacjami w bazie danych, np. między użytkownikami a postami, postami a komentarzami itp.

Schemat Bazy Danych (przykład):

    Użytkownicy:
        ID, imię, nazwisko, email, hasło (zaszyfrowane), bio, zdjęcie profilowe.

    Posty:
        ID postu, ID autora, treść, data publikacji, zdjęcie, kategoria.

    Komentarze:
        ID komentarza, ID postu, ID autora, treść, data publikacji.

    Lajki:
        ID lajka, ID postu, ID użytkownika.

    Powiadomienia:
        ID powiadomienia, ID użytkownika, typ powiadomienia (np. komentarz, lajk), treść, data.

Podział pracy i harmonogram:

    Tydzień 1:
        Frontend: Wstępny layout strony głównej, profile użytkowników.
        Backend: Uwierzytelnianie (logowanie, rejestracja) i struktura bazy danych.
    Tydzień 2:
        Frontend: System postów i kategorie, strona główna.
        Backend: Publikowanie postów, system komentarzy i lajków.
    Tydzień 3:
        Frontend: Integracja wyszukiwarki, dopracowanie UX.
        Backend: Powiadomienia, optymalizacja zapytań.
    Tydzień 4:
        Testowanie, optymalizacja, poprawki końcowe.

Dodatkowe Funkcjonalności (opcjonalnie):

    System prywatnych wiadomości – możliwość wysyłania wiadomości między użytkownikami.
    Zaawansowana personalizacja profilu – np. możliwość dodawania linków do profilu (media społecznościowe).
    Tryb ciemny/ciemny i jasny interfejs – przyjemniejsze doświadczenie użytkownika.

Projekt ten nie tylko jest ciekawy, ale również daje szerokie pole do rozwinięcia umiejętności frontendowych, backendowych i zarządzania danymi, jednocześnie pracując w zespole.
