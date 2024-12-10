<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
    <main class="dashboard ml-64 min-h-screen bg-gray-50 p-8" style="padding-top: 6rem;">
        <div class="form-container mt-12 max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-8 space-y-12">
            <div class="form-group">
                <label for="form-select" class="block text-lg font-semibold text-gray-800 mb-4">Select Form :</label>
                <select id="form-select" class="block w-full px-4 py-2 border rounded-md" onchange="showForm(this.value)">
                    <option value="">-- Select Form --</option>
                    <option value="edit-form">Edit category</option>
                    <option value="delete-form">Delete category</option>
                    <option value="add-form">Add category</option>
                </select>
            </div>

            <!-- Formularz edycji kategorii -->
            <div id="edit-form" class="form-section" style="display: none;">
                <form method="POST" class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Category</h3>
                    <div class="form-group">
                        <label for="edit-category-select" class="block text-sm font-medium text-gray-600 mb-2">
                            Select Category:
                        </label>
                        <select id="edit-category-select" name="category_id" required
                                class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['category_id']) ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-category-input" class="block text-sm font-medium text-gray-600 mb-2">
                            New Category name:
                        </label>
                        <input type="text" id="edit-category-input" name="new_category_name" required
                               class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="form-group">
                        <label for="edit-description-input" class="block text-sm font-medium text-gray-600 mb-2">
                            New Category description:
                        </label>
                        <input id="edit-description-input" name="new_description" required
                               class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <button type="submit" name="edit_category"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                        Save
                    </button>
                </form>
            </div>

            <!-- Formularz usuwania kategorii -->
            <div id="delete-form" class="form-section" style="display: none;">
                <form method="POST" class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800">Delete category</h3>
                    <div class="form-group">
                        <label for="delete-category-select" class="block text-sm font-medium text-gray-600 mb-2">
                            Select Category:
                        </label>
                        <select id="delete-category-select" name="category_id" required
                                class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['category_id']) ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="delete_category"
                            class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition">
                        Delete
                    </button>
                </form>
            </div>

            <!-- Formularz dodawania kategorii -->
            <div id="add-form" class="form-section" style="display: none;">
                <form method="POST" class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800">Add new category</h3>
                    <div class="form-group">
                        <label for="add-category-input" class="block text-sm font-medium text-gray-600 mb-2">
                            Category name:
                        </label>
                        <input type="text" id="add-category-input" name="category_name" required
                               class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="form-group">
                        <label for="add-description-input" class="block text-sm font-medium text-gray-600 mb-2">
                            Category description:
                        </label>
                        <input id="add-description-input" name="description" required
                               class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <button type="submit" name="add_category"
                            class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition">
                        Save
                    </button>
                </form>
            </div>
        </div>
        </main>

</html>
