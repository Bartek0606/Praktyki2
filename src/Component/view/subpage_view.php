<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../../public/js/fotografia.js"></script>
    <title>Hobbyhub Categories</title>
</head>
<body class="bg-gray-100 dark:bg-gray-900">
<header>
    <?php echo $navbar->render(); ?>
</header>

<section id="podstawy-fotografii" class="bg-gray-900 text-white">
    <div class="relative h-[60vh] overflow-hidden">
        <?php if ($result_blog_info->num_rows > 0): ?>
            <?php while ($category = $result_blog_info->fetch_assoc()): ?>
                <?php if (!empty($category['image'])): ?>
                    <div class='absolute inset-0 bg-cover bg-center' style="background-image: url('data:image/jpeg;base64,<?= base64_encode($category['image']) ?>'); filter: blur(8px); opacity: 0.6;"></div>
                <?php endif; ?>
                <div class='relative z-10 flex flex-col items-center justify-center h-full px-6 text-center'>
                    <h1 class='text-4xl font-extrabold md:text-6xl'><?= htmlspecialchars($category['title']) ?></h1>
                    <hr class='my-4 w-1/4 border-t-4 border-blue-500'>
                    <p class='text-lg md:text-xl max-w-3xl'><?= $category['content'] ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class='relative z-10 flex items-center justify-center h-full text-center'>
                <p class='text-2xl font-semibold'>Brak postów w tej kategorii.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="max-w-7xl mx-auto py-12 dark:bg-gray-900">
    <div class="text-center dark:bg-gray-900">
        <h1 class="text-4xl font-extrabold text-white text-shadow">Posts about <?= $category_name ?></h1>
    </div>
    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php if ($result_posts->num_rows > 0): ?>
            <?php while ($post = $result_posts->fetch_assoc()): ?>
                <?php 
                $post_url = 'post.php?id=' . $post['post_id']; 
                $profile_picture = getProfilePicture($post['profile_picture']); 
                ?>
                <div class='relative bg-white shadow-lg rounded-lg overflow-hidden h-80 card'>
                    <a href='<?= $post_url ?>' class='block h-full'>
                        <img src='data:image/jpeg;base64,<?= base64_encode($post['image']) ?>' alt='Post Image' class='absolute inset-0 w-full h-full object-cover'>
                        <div class='absolute inset-0 card-content p-6 flex flex-col justify-end'>
                            <div class='flex items-center'>
                                <img class='w-8 h-8 rounded-full user-photo' src='<?= $profile_picture ?>' alt='User photo'>
                                <span class='ml-2 text-gray-300 text-sm'><?= $post['created_at'] ?></span>
                                <span class='ml-2 text-white font-medium text-sm'><?= $post['username'] ?></span>
                            </div>
                            <h3 class='mt-2 text-xl font-semibold text-white'><?= $post['title'] ?></h3>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class='text-white'>Brak postów w tej kategorii.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
