<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../../public/js/post.js" defer></script>
    <title><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body class="bg-gray-900">
<header>
    <?php echo $navbar->render(); ?>
</header>

<main class="container mx-auto p-6">
<article class="bg-gray-700 rounded-lg shadow-md overflow-hidden mb-6">
    <div class="flex items-center p-4 border-b border-gray-600">
        <img src="<?php echo getUserImage($post['user_id'], $conn); ?>" alt="Profile Image" class="w-8 h-8 rounded-full mr-4">
        <div>
            <h3 class="font-semibold text-gray-200">
                <?php echo '<a href="user.php?id=' . urlencode($post['user_id']) . '" class="hover:underline">' . htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8') . '</a>'; ?>
            </h3>
            <span class="text-sm text-gray-400"><?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
    </div>
    <div class="flex p-4">
        <?php if (!empty($post['image'])): ?>
            <?php $imageSrc = 'data:image/jpeg;base64,' . base64_encode($post['image']); ?>
            <img src="<?php echo $imageSrc; ?>" alt="Post Image" class="w-1/3 max-w-lg rounded-lg mr-4 max-h-64 object-contain">
        <?php endif; ?>
        <div class="w-2/3">
            <h2 class="text-xl font-semibold text-gray-100 mb-4">
                <?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>
            </h2>
            <p class="text-gray-300 mb-4">
                <?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <div class="flex items-center justify-between text-gray-400">
                <span>
                    Category: <?php echo '<a href="subpage.php?id=' . urlencode($post['category_id']) . '" class="hover:underline">' . htmlspecialchars($post['category_name'], ENT_QUOTES, 'UTF-8') . '</a>'; ?>
                </span>
                <?php if ($post['is_question']): ?>
                    <span class="px-2 py-1 bg-yellow-400 text-yellow-900 rounded-full text-xs font-bold">Question</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <hr class="border-gray-600">
    <div class="flex items-center justify-between p-4 text-gray-400">
        <span class='text-sm text-gray-400'>Likes: <?php echo htmlspecialchars($likeCount, ENT_QUOTES, 'UTF-8'); ?></span>
        <?php if ($isLoggedIn): ?>
            <form method="POST" action="" class="relative" id="like-form-<?= $postId ?>">
                <input type="hidden" name="post_id" value="<?= $postId ?>">
                <button type="submit" name="like" class="like-btn bg-none border-none cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="<?= $isLiked ? 'red' : 'none' ?>" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                </button>
            </form>
        <?php endif; ?>
    </div>
</article>



<!-- Comment Section -->
<div class="comments-section mt-12 bg-gray-900 text-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-6">Comments</h2>

    <?php if ($isLoggedIn): ?>
        <form method="POST" class="comment-form mb-6 flex items-center">
            <img src="<?php echo getUserImage($userId, $conn); ?>" alt="Profile Image" class="w-10 h-10 rounded-full mr-4">
            <textarea name="comment_content" placeholder="Write your comment..." required class="w-full p-2 border border-gray-700 bg-gray-800 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4 text-white"></textarea>
            <button type="submit" name="submit_comment" class="ml-4 px-6 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition duration-200">Post</button>
        </form>
    <?php else: ?>
        <p class="text-red-800 bg-red-100 p-4 rounded-md mb-4">You must be logged in to post a comment.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['comment_success'])): ?>
        <p class="text-green-800 bg-green-100 p-4 rounded-md mb-4"><?php echo $_SESSION['comment_success']; unset($_SESSION['comment_success']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['edit_success'])): ?>
        <p class="text-green-800 bg-green-100 p-4 rounded-md mb-4"><?php echo $_SESSION['edit_success']; unset($_SESSION['edit_success']); ?></p>
    <?php endif; ?>

    <?php while ($comment = $resultComments->fetch_assoc()): ?>
    <div class="flex w-full justify-between border border-gray-700 rounded-md mb-6 p-4 bg-gray-800 shadow-sm">
        <div class="flex gap-3 items-start">
            <img src="<?php echo getUserImage($comment['user_id'], $conn); ?>" alt="Profile Image" class="w-10 h-10 rounded-full">
            <div>
                <h3 class="font-bold">
                    <?php echo '<a href="user.php?id=' . urlencode($comment['user_id']) . '" class="text-blue-400 hover:underline">' . htmlspecialchars($comment['username'], ENT_QUOTES, 'UTF-8') . '</a>'; ?>
                    <br>
                    <span class="text-sm text-gray-400 font-normal"><?php echo htmlspecialchars($comment['created_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                </h3>
                <p class="text-gray-300 mt-2"><?php echo htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8'); ?></p>

                <div class="flex justify-start items-center mt-4">
                    <?php if ($isLoggedIn): ?>
                        <button class="px-6 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition duration-200" onclick="toggleReplyForm(<?php echo $comment['comment_id']; ?>)">Reply</button>
                    <?php endif; ?>

                    <?php if ($isLoggedIn && isOwner($userId, $comment['user_id'])): ?>
                        <button class="ml-2 px-6 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition duration-200" onclick="toggleEditForm(<?php echo $comment['comment_id']; ?>)">Edit</button>
                    <?php endif; ?>
                </div>

                <!-- Reply Form -->
                <?php if ($isLoggedIn): ?>
                    <form method="POST" id="reply-form-<?php echo $comment['comment_id']; ?>" style="display:none;" class="mt-4 flex items-center">
                        <img src="<?php echo getUserImage($userId, $conn); ?>" alt="Profile Image" class="w-8 h-8 rounded-full mr-4">
                        <textarea name="comment_content" placeholder="Write your reply..." required class="w-full p-2 border border-gray-700 bg-gray-800 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4 text-white"></textarea>
                        <input type="hidden" name="parent_comment_id" value="<?php echo $comment['comment_id']; ?>">
                        <button type="submit" name="submit_comment" class="ml-4 px-6 py-2 bg-blue-500 text-white rounded-full hover:bg-green-600 transition duration-200">Post Reply</button>
                    </form>
                <?php endif; ?>

                <!-- Edit Comment Form -->
                <?php if ($isLoggedIn && isOwner($userId, $comment['user_id'])): ?>
                    <form method="POST" id="edit-comment-form-<?php echo $comment['comment_id']; ?>" style="display:none;" class="mt-4">
                        <textarea name="new_content" required class="w-full p-2 border border-gray-700 bg-gray-800 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 mb-4 text-white"><?php echo htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                        <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                        <button type="submit" name="edit_comment" class="px-6 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition duration-200">Save Changes</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Nested Replies -->
    <?php while ($reply = $resultReplies->fetch_assoc()): ?>
        <div class="flex w-full justify-between border border-gray-700 ml-5 rounded-md mb-6 p-4 bg-gray-800 shadow-sm">
            <div class="flex gap-3 items-start">
                <img src="<?php echo getUserImage($reply['user_id'], $conn); ?>" alt="Profile Image" class="w-8 h-8 rounded-full">
                <div>
                    <h3 class="font-bold">
                        <?php echo '<a href="user.php?id=' . urlencode($reply['user_id']) . '" class="text-blue-400 hover:underline">' . htmlspecialchars($reply['username'], ENT_QUOTES, 'UTF-8') . '</a>'; ?>
                        <br>
                        <span class="text-sm text-gray-400 font-normal"><?php echo htmlspecialchars($reply['created_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </h3>
                    <p class="text-gray-300 mt-2"><?php echo htmlspecialchars($reply['content'], ENT_QUOTES, 'UTF-8'); ?></p>

                    <div class="flex justify-start items-center mt-4">
                        <!-- Edit Reply Button -->
                        <?php if ($isLoggedIn && isOwner($userId, $reply['user_id'])): ?>
                            <button class="ml-2 px-6 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition duration-200" onclick="toggleEditReplyForm(<?php echo $reply['comment_id']; ?>)">Edit</button>
                        <?php endif; ?>
                    </div>

                    <!-- Edit Reply Form -->
                    <?php if ($isLoggedIn && isOwner($userId, $reply['user_id'])): ?>
                        <form method="POST" id="edit-reply-form-<?php echo $reply['comment_id']; ?>" style="display:none;" class="mt-4">
                            <textarea name="new_content" required class="w-full p-2 border border-gray-700 bg-gray-800 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 mb-4 text-white"><?php echo htmlspecialchars($reply['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <input type="hidden" name="reply_id" value="<?php echo $reply['comment_id']; ?>">
                            <button type="submit" name="edit_reply" class="px-6 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition duration-200">Save Changes</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php endwhile; ?>
</div>




        
</main>
</body>
</html>
