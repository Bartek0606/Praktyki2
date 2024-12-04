<?php
ob_start();
session_start();
include 'db_connection.php';
include 'Component/navbar.php';
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="fotografia.js"></script>
    <title>Blog o fotografii</title>
</head>
<style>
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }
        .user-photo:hover {
            transform: rotate(360deg);
            transition: transform 0.5s ease-in-out;
        }
        .bg-gradient {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
        }
        .text-shadow {
            text-shadow: 1px 2px 5px rgba(0, 0, 0, 0.3);
        }
        .card {
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-content {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }
    </style>

<body>
    
<header>
    <?php
        echo $navbar->render();
    ?>
</header>

<section id="podstawy-fotografii" class="bg-gray-900 text-white relative z-10">
    <div class="relative h-[60vh] overflow-hidden">
        <?php 
        if ($result_category->num_rows > 0) {
            while ($category = $result_category->fetch_assoc()) {
                if (!empty($category['image'])) {
                    // Displaying category image as a background
                    echo "<div class='absolute inset-0 bg-cover bg-center' style=\"background-image: url('data:image/jpeg;base64," . base64_encode($category['image']) . "'); filter: blur(8px); opacity: 0.6;\"></div>";
                }
                echo "<div class='relative z-10 flex flex-col items-center justify-center h-full px-6 text-center'>";
                echo "<h1 class='text-4xl font-extrabold md:text-6xl'>" . htmlspecialchars($category['title']) . "</h1>";
                echo "<hr class='my-4 w-1/4 border-t-4 border-blue-500'>";
                echo "<p class='text-lg md:text-xl max-w-3xl'>" . $category['content'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<div class='relative z-10 flex items-center justify-center h-full text-center'>";
            echo "<p class='text-2xl font-semibold'>Brak postów w tej kategorii.</p>";
            echo "</div>";
        }
        ?>
    </div>
</section>


<div class="max-w-7xl mx-auto py-12">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 text-shadow">Posts about <?php echo $category_name; ?></h1>
        </div>
        <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            if ($result_posts->num_rows > 0) {
                while ($post = $result_posts->fetch_assoc()) {
                    $post_url = 'post.php?id=' . $post['post_id'];
                    
                    echo "<div class='relative bg-white shadow-lg rounded-lg overflow-hidden h-80 card'>";
                    echo "<a href='{$post_url}' class='block h-full'>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($post['image']) . "' alt='Post Image' class='absolute inset-0 w-full h-full object-cover'>";
                    echo "<div class='absolute inset-0 card-content p-6 flex flex-col justify-end'>";
                    echo "<div class='flex items-center'>";
                    echo "<img class='w-8 h-8 rounded-full user-photo' src='data:image/jpeg;base64," . base64_encode($post['profile_picture']) . "' alt='User photo'>";
                    echo "<span class='ml-2 text-gray-300 text-sm'>" . $post['created_at'] . "</span>";
                    echo "<span class='ml-2 text-white font-medium text-sm'>" . $post['username'] . "</span>";
                    echo "</div>";
                    echo "<h3 class='mt-2 text-xl font-semibold text-white'>" . $post['title'] . "</h3>";
                    echo "</div>";
                    echo "</a>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-white'>Brak postów w tej kategorii.</p>";
            }
            ?>
        </div>
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
<?php
$conn->close();
ob_end_flush();
?>