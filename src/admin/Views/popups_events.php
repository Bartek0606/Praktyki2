<?php

class Event_Popups_Renderer {
    public function renderAddEventPopup($errors = [], $formData = []) {
        ob_start();
        ?>
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 <?php echo !empty($errors) ? 'block' : 'hidden'; ?> z-40"></div>
        <div id="add-event-popup" class="popup fixed inset-0 flex justify-center items-center <?php echo !empty($errors) ? 'flex' : 'hidden'; ?> z-50">
            <div class="popup-content bg-white shadow-lg rounded-lg p-8 w-[40rem]">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Add Event</h3>
                <form method="POST" action="events.php">
                    <input type="hidden" name="add_event" value="1">

                    <label class="block text-sm font-medium text-gray-700">Event name:</label>
                    <input type="text" name="event_name" value="<?php echo htmlspecialchars($formData['event_name'] ?? ''); ?>" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-1">
                    <?php if (isset($errors['event_name'])): ?>
                        <p class="text-red-500 text-sm"><?php echo $errors['event_name']; ?></p>
                    <?php endif; ?>

                    <label class="block text-sm font-medium text-gray-700">Event date:</label>
                    <input type="datetime-local" name="event_date" value="<?php echo htmlspecialchars($formData['event_date'] ?? ''); ?>" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-1">
                    <?php if (isset($errors['event_date'])): ?>
                        <p class="text-red-500 text-sm"><?php echo $errors['event_date']; ?></p>
                    <?php endif; ?>

                    <label class="block text-sm font-medium text-gray-700">Event location:</label>
                    <input type="text" name="event_location" value="<?php echo htmlspecialchars($formData['event_location'] ?? ''); ?>" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-1">
                    <?php if (isset($errors['event_location'])): ?>
                        <p class="text-red-500 text-sm"><?php echo $errors['event_location']; ?></p>
                    <?php endif; ?>

                    <label class="block text-sm font-medium text-gray-700">Event description:</label>
                    <textarea name="event_description" 
                              class="w-full h-32 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-1"><?php echo htmlspecialchars($formData['event_description'] ?? ''); ?></textarea>
                    <?php if (isset($errors['event_description'])): ?>
                        <p class="text-red-500 text-sm"><?php echo $errors['event_description']; ?></p>
                    <?php endif; ?>

                    <div class="flex justify-end space-x-2">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">Save</button>
                        <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200" onclick="closeAddEventPopup()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function renderEditEventPopup() {
        ob_start();
        ?>
        <div id="edit-popup" class="popup fixed inset-0 flex justify-center items-center hidden z-50">
            <div class="popup-content bg-white shadow-lg rounded-lg p-8 w-[40rem]">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Edit Event</h3>
                <form method="POST" id="edit-form" action='events.php'>
                    <input type="hidden" id="event-id" name="event_id">

                    <label class="block text-sm font-medium text-gray-700">Event name:</label>
                    <input type="text" id="event-name" name="event_name" placeholder="Event Name" required 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

                    <label class="block text-sm font-medium text-gray-700">Event date:</label>
                    <input type="datetime-local" id="event-date" name="event_date" required 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

                    <label class="block text-sm font-medium text-gray-700">Event location:</label>
                    <input type="text" id="event-location" name="event_location" placeholder="Location" required 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

                    <label class="block text-sm font-medium text-gray-700">Event description:</label>
                    <textarea id="event-description" name="event_description" placeholder="Event Description" required 
                              class="w-full h-32 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4"></textarea>

                    <div class="flex justify-end space-x-2">
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition duration-200">Save</button>
                        <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200" onclick="closePopup()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
