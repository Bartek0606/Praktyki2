<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<section class="w-4/6 mx-auto py-12 mb-12">
    <div class="text-left mb-8">
        <h2 class="text-2xl font-bold text-white mb-10">Posts about Technology</h2>
        <a  href="subpage.php?id=1" 
           class="px-6 py-3 bg-gray-700 text-white font-bold rounded-lg hover:bg-gray-800 transition">
            View All Technology Posts
        </a>
    </div>
    <div class="grid grid-cols-2 gap-12 bg-gray-900">
        <?php
        if ($categoryPostsResult->num_rows > 0) {
            while ($row = $categoryPostsResult->fetch_assoc()) {
                $post_url = '../templates/post.php?id=' . $row['post_id'];
                $hasImage = !empty($row['image']);

                echo "<div class='flex h-64 bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 hover:shadow-xl'>";

                if ($hasImage) {
                    echo "<div class='w-1/3 h-full'>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='Post Image' class='w-full h-full object-cover'>";
                    echo "</div>";
                }

                echo "<div class='p-6 w-2/3 bg-gray-800 flex flex-col justify-between'>";
                echo "<span class='text-sm uppercase text-gray-400 font-semibold'>" . htmlspecialchars($row['category_name']) . "</span>";
                echo "<a href='{$post_url}' class='text-xl font-bold text-white hover:text-blue-400 transition'>" . htmlspecialchars($row['title']) . "</a>";
                echo "<p class='text-gray-300 mt-2 line-clamp-3'>" . htmlspecialchars(substr($row['content'], 0, 150)) . "...</p>";
                echo "<div class='flex items-center mt-4'>";
                echo "<span class='text-sm text-gray-500'>by " . htmlspecialchars($row['author_name']) . "</span>";
                echo "<span class='text-sm text-gray-400 ml-4'>" . htmlspecialchars($row['created_at']) . "</span>";
                echo "</div>";
                echo "</div>";

                echo "</div>";
            }
        } else {
            echo "<p class='text-center text-gray-500'>No posts found in this category.</p>";
        }
        ?>
    </div>
</section>


<section class="w-4/6 mx-auto py-12 mb-12">
    <div class="text-left mb-8">
        <h2 class="text-2xl font-bold text-white mb-10">Posts about Lifestyle</h2>
        <a href="subpage.php?id=2"  
           class="px-6 py-3 bg-gray-700 text-white font-bold rounded-lg hover:bg-gray-800 transition">
            View All Lifestyle Posts
        </a>
    </div>
    <div class="grid grid-cols-2 gap-12 bg-gray-900">
        <?php
        if ($categoryPostsResult2->num_rows > 0) {
            while ($row = $categoryPostsResult2->fetch_assoc()) {
                $post_url = '../templates/post.php?id=' . $row['post_id'];
                $hasImage = !empty($row['image']);

                echo "<div class='flex h-64 bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 hover:shadow-xl'>";

                if ($hasImage) {
                    echo "<div class='w-1/3 h-full'>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='Post Image' class='w-full h-full object-cover'>";
                    echo "</div>";
                }

                echo "<div class='p-6 w-2/3 bg-gray-800 flex flex-col justify-between'>";
                echo "<span class='text-sm uppercase text-gray-400 font-semibold'>" . htmlspecialchars($row['category_name']) . "</span>";
                echo "<a href='{$post_url}' class='text-xl font-bold text-white hover:text-blue-400 transition'>" . htmlspecialchars($row['title']) . "</a>";
                echo "<p class='text-gray-300 mt-2 line-clamp-3'>" . htmlspecialchars(substr($row['content'], 0, 150)) . "...</p>";
                echo "<div class='flex items-center mt-4'>";
                echo "<span class='text-sm text-gray-500'>by " . htmlspecialchars($row['author_name']) . "</span>";
                echo "<span class='text-sm text-gray-400 ml-4'>" . htmlspecialchars($row['created_at']) . "</span>";
                echo "</div>";
                echo "</div>";

                echo "</div>";
            }
        } else {
            echo "<p class='text-center text-gray-500'>No posts found in this category.</p>";
        }
        ?>
    </div>
</section>



<section class="w-4/6 mx-auto py-12 mb-12">
    <div class="text-left mb-8">
        <h2 class="text-2xl font-bold text-white mb-10">Posts about Travel</h2>
        <a href="subpage.php?id=4"  
           class="px-6 py-3 bg-gray-700 text-white font-bold rounded-lg hover:bg-gray-800 transition">
            View All Travel Posts
        </a>
    </div>
    <div class="grid grid-cols-2 gap-12 bg-gray-900">
        <?php
        if ($categoryPostsResult3->num_rows > 0) {
            while ($row = $categoryPostsResult3->fetch_assoc()) {
                $post_url = '../templates/post.php?id=' . $row['post_id'];
                $hasImage = !empty($row['image']);

                echo "<div class='flex h-64 bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 hover:shadow-xl'>";

                if ($hasImage) {
                    echo "<div class='w-1/3 h-full'>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='Post Image' class='w-full h-full object-cover'>";
                    echo "</div>";
                }

                echo "<div class='p-6 w-2/3 bg-gray-800 flex flex-col justify-between'>";
                echo "<span class='text-sm uppercase text-gray-400 font-semibold'>" . htmlspecialchars($row['category_name']) . "</span>";
                echo "<a href='{$post_url}' class='text-xl font-bold text-white hover:text-blue-400 transition'>" . htmlspecialchars($row['title']) . "</a>";
                echo "<p class='text-gray-300 mt-2 line-clamp-3'>" . htmlspecialchars(substr($row['content'], 0, 150)) . "...</p>";
                echo "<div class='flex items-center mt-4'>";
                echo "<span class='text-sm text-gray-500'>by " . htmlspecialchars($row['author_name']) . "</span>";
                echo "<span class='text-sm text-gray-400 ml-4'>" . htmlspecialchars($row['created_at']) . "</span>";
                echo "</div>";
                echo "</div>";

                echo "</div>";
            }
        } else {
            echo "<p class='text-center text-gray-500'>No posts found in this category.</p>";
        }
        ?>
    </div>
</section>

</section>
</body>
</html>