<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../..//public/js/user.js" defer></script>
    <title><?php echo htmlspecialchars($user['username']); ?>'s Profile • HobbyHub</title>
    <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
    <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
</head>

<body class="bg-gray-900">
<header>
    <?php echo $navbar->render(); ?>
</header>
<main>
  <section class="relative block h-500-px">
    <div class="absolute top-0 w-full h-full bg-center bg-cover" style="
        background-image: url('https://images.unsplash.com/photo-1499336315816-097655dcfbda?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=2710&amp;q=80');
    ">
        <span id="blackOverlay" class="w-full h-full absolute opacity-50 bg-black"></span>
    </div>
</section>
<div class="container mx-auto px-4">
    <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg -mt-64">
        <div class="px-6 bg-gray-800 shadow-xl">
            <div class="flex flex-wrap justify-center">
                <div class="w-full lg:w-3/12 px-4 lg:order-2 flex justify-center">
                    <div class="relative group">
                        <?php
                        $image_src = '/src/public/image/default.png';  // Variable with full path to default.png
                        ?>
                        <img alt="Profile Picture" 
                             src="<?php echo $user['profile_picture'] && $user['profile_picture'] !== 'default.png' ? 'data:image/jpeg;base64,' . base64_encode($user['profile_picture']) : $image_src; ?>"
                             class="shadow-xl rounded-full h-auto align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-150-px transition-transform duration-300 group-hover:scale-110">
                    </div>
                </div>
                <div class="w-full lg:w-4/12 px-4 lg:order-3 lg:text-right lg:self-center">
                    <div class="py-6 px-3 mt-32 sm:mt-0">
                        <?php if ($isLoggedIn): ?>
                            <?php if ($userId == $profileUserId): ?>
                                <!-- Edit Profile Button -->
                                <a href="edit_profile.php">
                                    <button class="bg-blue-500 text-white font-bold px-4 py-2 rounded-md hover:bg-blue-600 transition-transform transform hover:-translate-y-1">
                                        Edit Profile
                                    </button>
                                </a>
                            <?php else: ?>
                                <!-- Follow and Message Buttons -->
                                <div class="flex gap-4 justify-end">
                                    <!-- Follow Button -->
                                    <form method="POST" action="">
                                        <button name="follow" 
                                                class="h-10 px-4 py-2 rounded-md text-white transition-transform duration-300 transform hover:scale-105
                                                <?php echo $isFollowing ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'; ?>">
                                            <?php echo $isFollowing ? 'Unfollow' : 'Follow'; ?>
                                        </button>
                                    </form>

                                    <!-- Message Button -->
                                    <a href="message.php?id=<?php echo $profileUserId; ?>" 
                                       class="h-10 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-transform transform hover:scale-105">
                                        Message
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="w-full lg:w-4/12 px-4 lg:order-1">
                    <div class="flex justify-center py-4 lg:pt-4 pt-8">
                        <div class="mr-4 p-3 text-center">
                            <span class="text-xl font-bold block uppercase tracking-wide text-white"><?php echo $posts_count; ?></span>
                            <span class="text-sm text-gray-300">Posts</span>
                        </div>
                        <div class="mr-4 p-3 text-center cursor-pointer hover:scale-110 transition-transform" onclick="showModal('Followers', <?php echo $profileUserId; ?>)">
                            <span class="text-xl font-bold block uppercase tracking-wide text-white"><?php echo $followers_count; ?></span>
                            <span class="text-sm text-gray-300">Followers</span>
                        </div>
                        <div class="lg:mr-4 p-3 text-center cursor-pointer hover:scale-110 transition-transform" onclick="showModal('Following', <?php echo $profileUserId; ?>)">
                            <span class="text-xl font-bold block uppercase tracking-wide text-white"><?php echo $following_count; ?></span>
                            <span class="text-sm text-gray-300">Following</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <!-- Display Username -->
                   <h3 class="text-4xl font-semibold leading-normal mb-2 text-white">
                    <?php echo htmlspecialchars($user['username'])?>
                </h3>

                <!-- Display Full Name -->
                <p class="text-lg leading-normal text-gray-300">
                    <?php echo htmlspecialchars($user['full_name']); ?>
                </p>

                <div class="mt-10 py-10 border-t border-gray-700 text-center">
                    <div class="flex flex-wrap justify-center">
                        <div class="w-full lg:w-9/12 px-4">
                            <p class="mb-4 text-lg leading-relaxed text-gray-300">
                                <?php echo nl2br(htmlspecialchars($user['bio'])); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Popup Modal for Followers/Following -->
    <div id="popup-container" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-gray-700 w-11/12 md:w-2/3 lg:w-1/2 p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
        <h3 id="popup-title" class="text-xl font-bold text-white"></h3>
        <button id="close-popup" class="text-gray-400 hover:text-gray-200 text-xl font-bold">&times;</button>
        </div>
        <div id="popup-content" class="text-gray-300 space-y-4">
        <!-- The list of followers/following will be dynamically loaded -->
        </div>
    </div>
    </div>

    <!-- Section buttons at the bottom -->
    <div class="toggle-buttons flex justify-center mt-6 space-x-4">
    <button id="show-posts" class="toggle-btn px-4 py-2 bg-gray-800 hover:bg-gray-900 transform hover:scale-105 rounded-md text-white transition-all duration-300">
        <?php echo $isLoggedIn && $userId == $profileUserId ? "Your Posts" : htmlspecialchars($user['username']) . "'s Posts"; ?>
    </button>
    <?php if ($isLoggedIn && $userId == $profileUserId): ?>
        <button id="show-likes" class="toggle-btn px-4 py-2 bg-gray-800 hover:bg-gray-900 transform hover:scale-105 rounded-md text-white transition-all duration-300">Your Likes</button>
    <?php endif; ?>
    <button id="show-events" class="toggle-btn px-4 py-2 bg-gray-800 hover:bg-gray-900 transform hover:scale-105 rounded-md text-white transition-all duration-300">
        <?php echo $isLoggedIn && $userId == $profileUserId ? "Your Events" : htmlspecialchars($user['username']) . "'s Events"; ?>
    </button>
    <button id="show-items" class="toggle-btn px-4 py-2 bg-gray-800 hover:bg-gray-900 transform hover:scale-105 rounded-md text-white transition-all duration-300">
        <?php echo $isLoggedIn && $userId == $profileUserId ? "Your Items" : htmlspecialchars($user['username']) . "'s Items"; ?>
    </button>
    </div>
    
    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg w-3/4 max-w-xl p-6">
            <div class="flex justify-between items-center">
                <h2 id="modal-title" class="text-xl font-bold text-white"></h2>
                <button id="close-modal" class="text-white text-lg">&times;</button>
            </div>
            <div id="modal-content" class="mt-4 text-gray-300"></div>
        </div>
    </div>

    <!-- User posts -->
 <div id="post-container" class="container mx-auto mt-6 bg-gray-800 p-6 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold text-white mb-6">
        <?php echo $isLoggedIn && $userId == $profileUserId ? "Your Posts" : htmlspecialchars($user['username']) . "'s Posts"; ?>
        <hr class="border-t-4 w-32 border-orange-500 mt-3">
    </h2>
    
    <?php $result_posts = getUserPosts($conn, $profileUserId); ?>
    <?php if ($result_posts->num_rows > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($post = $result_posts->fetch_assoc()): ?>
                <a href="post.php?id=<?php echo $post['post_id']; ?>" class="post-link block bg-gray-700 hover:bg-gray-600 rounded-lg transition-all transform hover:scale-105">
                    <div class="post p-4">
                        <?php if (!empty($post['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" alt="Post Image" class="post-image mb-4 rounded-lg shadow-md transition-shadow duration-300 hover:shadow-xl">
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <h3 class="text-xl font-semibold text-white mb-2"><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p class="category text-gray-400 mb-2"><strong>Category:</strong> <?php echo htmlspecialchars($post['category_name']); ?></p>
                            <p class="post-author text-gray-400 mb-2"><strong>By:</strong> <?php echo htmlspecialchars($post['author_username']); ?></p>
                            <p class="post-date text-gray-400"><strong>Date:</strong> <?php echo htmlspecialchars($post['created_at']); ?></p>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-400">No posts yet. Start creating posts!</p>
    <?php endif; ?>
</div>

  <div id="likes-container" class="container likes-container mt-6 mx-auto bg-gray-800 p-6 rounded-lg shadow-lg" style="display: none;">
    <h2 class="text-3xl font-bold text-white mb-6">
        Your Likes
        <hr class="border-t-4 w-32 border-orange-500 mt-3">
    </h2>

    <?php
    $result_like = getLikedPosts($conn, $userId);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
        handleLikeUnlike($conn, $userId, $_POST['post_id']);
    }

    if ($result_like->num_rows > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($like = $result_like->fetch_assoc()):
                $isLiked = checkIfLiked($conn, $userId, $like['post_id']);
            ?>
                <a href="post.php?id=<?php echo $like['post_id']; ?>" class="post-link block bg-gray-700 hover:bg-gray-600 rounded-lg transition-all transform hover:scale-105">
                    <div class="post p-4">
                        <?php if (!empty($like['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($like['image']); ?>" alt="Post Image" class="post-image mb-4 rounded-lg shadow-md transition-shadow duration-300 hover:shadow-xl">
                        <?php endif; ?>
                        <div class="post-content">
                            <h3 class="text-xl font-semibold text-white mb-2"><?php echo htmlspecialchars($like['title']); ?></h3>
                            <p class="category text-gray-400 mb-2"><strong>Category:</strong> <?php echo htmlspecialchars($like['category_name']); ?></p>
                            <p class="post-author text-gray-400 mb-2"><strong>By:</strong> <?php echo htmlspecialchars($like['author_username']); ?></p>
                            <p class="post-date text-gray-400"><strong>Date:</strong> <?php echo htmlspecialchars($like['created_at']); ?></p>
                            
                            <form method="POST" action="" class="relative mt-4" id="like-form-<?php echo $like['post_id']; ?>">
                                <input type="hidden" name="post_id" value="<?php echo $like['post_id']; ?>">
                                <button type="submit" name="like" class="like-btn absolute bottom-4 right-4 bg-none border-none cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="<?php echo $isLiked ? 'red' : 'none'; ?>" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-400">No posts liked yet.</p>
    <?php endif; ?>
</div>

<div id="events-container" class="container events-container mt-6 mx-auto bg-gray-800 p-6 rounded-lg shadow-lg" style="display: none;">
    <h2 class="text-3xl font-bold text-white mb-6">
        Your Events
        <hr class="border-t-4 w-32 border-orange-500 mt-3">
    </h2>

    <?php 
    $result_events = getUserEvents($conn, $profileUserId); 

    if ($result_events->num_rows > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($event = $result_events->fetch_assoc()): ?>
                <a href="event.php?id=<?php echo $event['event_id']; ?>" class="event-link block bg-gray-700 hover:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition-all">
                    <div class="event-card p-4">
                        <div class="event-header mb-4">
                            <h3 class="text-xl font-semibold text-white"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                        </div>
                        <div class="event-body mb-4">
                            <p class="text-gray-400"><strong class="text-white">Description:</strong> <?php echo htmlspecialchars($event['event_description']); ?></p>
                            <p class="text-gray-400 mb-2"><strong class="text-white">Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                            <p class="event-date text-gray-400"><?php echo htmlspecialchars($event['event_date']); ?></p>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-400">
            <?php
            if ($isLoggedIn && $userId == $profileUserId) {
                echo "You are not attending any events yet.";
            } else {
                echo htmlspecialchars($user['username']) . " is not attending any events.";
            }
            ?>
        </p>
    <?php endif; ?>
</div>

<div id="items-container" class="container items-container mt-6 mx-auto bg-gray-800 p-6 rounded-lg shadow-lg" style="display: none;">
    <h2 class="text-3xl font-bold text-white mb-3">
        <?php echo $isLoggedIn && $userId == $profileUserId ? "Your Items" : htmlspecialchars($user['username']) . "'s Items"; ?>
    </h2>
    <hr class="border-t-4 w-32 border-orange-500 mb-6 mt-1">
    
    <?php 
    $result_items = getUserItems($conn, $profileUserId);

    if ($result_items->num_rows > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($item = $result_items->fetch_assoc()): ?>
                <a href="item_details.php?item_id=<?php echo $item['item_id']; ?>" class="item-card block bg-gray-700 hover:bg-gray-600 rounded-lg shadow-md transition-all transform hover:scale-105">
                    <div class="p-4">
                        <!-- Item Image -->
                        <?php if (!empty($item['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>" alt="Item Image" class="w-full h-48 object-cover rounded-lg mb-4">
                        <?php else: ?>
                            <img src="public/image/default-item.png" alt="Default Item Image" class="w-full h-48 object-cover rounded-lg mb-4">
                        <?php endif; ?>

                        <!-- Item Details -->
                        <div class="item-details">
                            <h3 class="text-xl font-semibold text-white mb-2"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="text-gray-400 mb-2"><strong class="text-white">Price:</strong> <?php echo number_format($item['price'], 2); ?> zł</p>
                            <p class="text-gray-400 mb-2"><strong class="text-white">Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
                            <p class="text-gray-400"><strong class="text-white">Added on:</strong> <?php echo htmlspecialchars($item['created_at']); ?></p>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-400">No items to display.</p>
    <?php endif; ?>
</div>

</div>
</main>
</body>
</html>
 
