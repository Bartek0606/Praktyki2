<?php

class CommentRenderer {
    public function renderComments($comments, $search_query) {
        ob_start();
        ?>
        <main class="dashboard p-8 bg-gray-50 ml-64 min-h-screen" style="padding-top: 6rem;">
            <h2 class="text-xl font-semibold text-center text-gray-800 mt-3 mb-4">All Comments</h2>

            <div class="comments-container m-auto space-y-4 w-4/6 mt-3">
                <div id="searchdiv" class="mb-8">
                    <form method="GET" class="flex items-center justify-center space-x-4">
                        <input type="text" name="search_query" placeholder="Search by full name"
                               value="<?php echo htmlspecialchars($search_query); ?>"
                               class="w-1/4 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Search</button>
                    </form>
                </div>

                <?php if (empty($comments)): ?>
                    <p class="text-gray-500 italic text-center">No comments found.</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment bg-white shadow-md rounded-lg p-6">

                            <div class="comment-header flex justify-between items-center mb-3">
                                <p class="font-bold text-gray-800">User: <?php echo htmlspecialchars($comment['full_name']); ?></p>
                                <p class="text-gray-500 text-sm"><?php echo date('Y-m-d H:i:s', strtotime($comment['created_at'])); ?></p>
                            </div>
                            <div class="comment-body mb-4">
                                <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                            </div>

                            <div class="comment-footer text-right">
                                <a href="?delete_comment_id=<?php echo $comment['comment_id']; ?>"
                                   class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
                                   onclick="return confirm('Are you sure you want to delete this comment?');">
                                    Delete
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
        <?php
        return ob_get_clean();
    }
}
