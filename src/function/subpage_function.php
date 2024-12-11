<?php
function getCategory($conn, $category_id) {
    $sql = "SELECT name FROM categories WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getPosts($conn, $category_id) {
    $sql = "
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
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getCategoryBlogInfo($conn, $category_id) {
    $sql = "
        SELECT blog_information.title, blog_information.content, blog_information.image 
        FROM blog_information 
        JOIN categories ON blog_information.category_id = categories.category_id 
        WHERE categories.category_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getProfilePicture($profile_picture) {
    $default_image_src = '/src/public/image/default.png'; // Ścieżka do domyślnego zdjęcia profilowego
    
    if (!empty($profile_picture) && $profile_picture !== 'default.png') {
        return 'data:image/jpeg;base64,' . base64_encode($profile_picture);
    }
    return $default_image_src;
}
?>
