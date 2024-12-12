<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<main class="dashboard p-8 bg-gray-50 ml-64 min-h-screen" style="padding-top: 6rem;">

<h2 class="text-xl font-semibold text-center text-gray-800 mt-3 mb-4">All Posts</h2>
<ul class="post-list m-auto space-y-4 w-4/6 mt-3">
    <?php if (empty($posts)): ?>
        <li class="text-gray-500 italic">No posts found.</li>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <li class="post-item bg-white shadow-md rounded-lg p-4 flex items-start relative">
                <?php if (!empty($post["image"])): ?>
                    <div class="post-image w-1/6 mr-5 h-full rounded-md overflow-hidden">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($post["image"]); ?>" alt="Post Image" class="w-full h-full object-cover">
                    </div>
                <?php endif; ?>

                <div class="post-content flex-1">
                    <h3 class="text-lg font-semibold text-gray-700"><?php echo ($post["tittle"]); ?></h3>
                    <p class="text-gray-600 mt-3"><?php echo ($post["content"]); ?></p>
                    <p class="text-sm text-gray-500 mt-3"><strong>Category:</strong> <?php echo htmlspecialchars($post["category_name"]); ?></p>
                    <p class="text-sm text-gray-400 mt-3"><em>Created at: <?php echo htmlspecialchars($post["created_at"]); ?></em></p>
                </div>

                <div class="actions absolute bottom-4 right-4 flex space-x-2">
                    <button class="editpost_button bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200" data-post-id="<?php echo $post["post_id"]; ?>">Edit</button>
                    <form method="post" class="inline">
                        <input type="hidden" name="delete_post_id" value="<?php echo $post["post_id"]; ?>">
                        <button type="submit" class="deletepost_button bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-200">Delete</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
</main>
</body>
</html>