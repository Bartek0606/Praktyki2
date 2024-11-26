<?php
session_start();
include 'db_connection.php';
include 'COs/navbar.php';
$isLoggedIn = isset($_SESSION['user_id']);

$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to homepage
    exit;
}
// Sprawdzenie ID kategorii w URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $category_id = intval($_GET['id']); // Zabezpieczenie danych wejściowych
} else {
    die('Nieprawidłowe ID kategorii');
}

// Zapytanie o nazwę kategorii
$sql_category = "
    SELECT name 
    FROM categories 
    WHERE category_id = ?;
";
$stmt_category = $conn->prepare($sql_category);
$stmt_category->bind_param("i", $category_id);
$stmt_category->execute();
$result_category = $stmt_category->get_result();

// Sprawdzenie, czy kategoria istnieje
if ($result_category->num_rows > 0) {
    $category = $result_category->fetch_assoc();
    $category_name = htmlspecialchars($category['name']); // Nazwa kategorii
} else {
    die('Kategoria o podanym ID nie istnieje.');
}



$sql_posts = "
    SELECT 
        posts.post_id,
        posts.title, 
        posts.content, 
        posts.image, 
        posts.created_at, 
        users.username, 
        users.profile_picture, 
        categories.name AS category_name 
    FROM 
        posts 
    JOIN 
        users ON posts.user_id = users.user_id
    JOIN 
        categories ON posts.category_id = categories.category_id
    WHERE 
        categories.category_id = ? 
    ORDER BY 
        posts.created_at DESC;
";
$sql_category = "
    SELECT blog_information.title, blog_information.content, blog_information.image FROM blog_information JOIN categories ON blog_information.category_id = categories.category_id WHERE  categories.category_id = ?;
";
// Przygotowanie zapytania SQL
$stmt_category = $conn->prepare($sql_category);

// Podłączenie parametru do zapytania
$stmt_category->bind_param("i", $category_id);

// Wykonanie zapytania
$stmt_category->execute();

// Pobranie wyników
$result_category = $stmt_category->get_result();

// Przygotowanie zapytania SQL
$stmt_posts = $conn->prepare($sql_posts);

// Podłączenie parametru do zapytania
$stmt_posts->bind_param("i", $category_id);

// Wykonanie zapytania
$stmt_posts->execute();

// Pobranie wyników
$result_posts = $stmt_posts->get_result();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="fotografia.css">
    <link rel="stylesheet" href="glowna.css">
    <link rel="stylesheet" href="navbar.css">
    <script src="fotografia.js"></script>
    <title>Blog o fotografii</title>
</head>
<body>
    
<header>
    <?php
        echo $navbar->render();
    ?>
</header>

<section id="podstawy-fotografii" class="hero-section">
    <div class="hero-container">


        <?php 
        if ($result_category->num_rows > 0) {
            while ($category = $result_category->fetch_assoc()) {
                 // Wyświetlanie obrazu kategorii
                if (!empty($category['image'])) {
                    echo "<img src='" . 'data:image/jpeg;base64,' . base64_encode($category['image']) . "' alt='" . htmlspecialchars($category['title']) . "' class='hero-image'>";
                            }
                echo "<div class='hero-content'>";
               echo "<h1>" . htmlspecialchars($category['title']) . "</h1>";
               echo "<hr class='hero-divider'>";
               echo "<p>" . $category['content'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Brak postów w tej kategorii.</p>";
        }
        ?>
    </div>
</section>


<div class="tlo_posty">
    <section id="posty" class="posts-section">
    <h1 class="posts-title">Posts about <?php echo $category_name; ?></h1>
        <a href="#" class="see-more">See more posts</a>

        <div class="posts-container">
            <?php
            if ($result_posts->num_rows > 0) {
                while ($post = $result_posts->fetch_assoc()) {
                    $post_url = 'post.php?id=' . $post['post_id'];

                    echo "<div class='post'>";
                    echo '<a href="' . $post_url . '" class="post-link">'; 
                    echo "<img src='" . $post['image_url'] . "' alt='" . $post['title'] . "'>";
                    echo "<h3>" . $post['title'] . "</h3>";
                    echo "<br />";
                    echo "<p>" . $post['content']. "</p>";
                    echo "<br />";
                    echo "<p>Add by: <strong>" . $post['username'] . "</strong></p>";
                    echo "<p>Category: <strong>" . $post['category_name'] . "</strong></p>";
                    echo "</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>Brak postów w tej kategorii.</p>";
            }

            ?>
        </div>
    </section>
</div>

<section id="opinie" class="reviews-section">
    <h2 class="reviews-title">User opinions about the blog</h2>
    <div class="reviews-slider">
        <button class="reviews-prev-button">&larr;</button> <!-- Strzałka w lewo -->
        <div class="reviews-container">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="review">';
                    echo '<img src="' . $row['profile_picture_url'] . '" alt="Zdjęcie użytkownika" class="review-image">';
                    echo '<p class="review-username">' . $row['username'] . '</p>';
                    echo '<p class="review-text">"' . $row['content'] . '"</p>';
                    echo '</div>';
                }
            } else {
                echo "Brak recenzji dotyczących fotografii.";
            }
            ?>
        </div>
        <button class="reviews-next-button">&rarr;</button> <!-- Strzałka w prawo -->
    </div>
</section>




</body>
</html>
