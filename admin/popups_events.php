<?php

class Event_Popups_Renderer {
    public function Render_Event_Popups_($comments, $search_query) {
        ob_start();
        ?>
       <div id="add-event-popup" class="popup fixed inset-0 flex justify-center items-center hidden z-50">
    <div class="popup-content bg-white shadow-lg rounded-lg p-8 w-[40rem]">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Add Event</h3>
        <form method="POST" action="events.php">
            <input type="hidden" name="add_event" value="1">
            <label class="block text-sm font-medium text-gray-700">Event name:</label>
            <input type="text" name="event_name" placeholder="Event Name" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event date:</label>
            <input type="datetime-local" name="event_date" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event location:</label>
            <input type="text" name="event_location" placeholder="Location" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event description:</label>
            <textarea name="event_description" placeholder="Description" required class="w-full h-32 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4"></textarea>

            <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">Save</button>
                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200" onclick="closeAddEventPopup()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="edit-popup" class="popup fixed inset-0 flex justify-center items-center hidden z-50">
    <div class="popup-content bg-white shadow-lg rounded-lg p-8 w-[40rem]">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Edit Event</h3>
        <form method="POST" id="edit-form" action="edit_event.php">
            <input type="hidden" id="event-id" name="event_id">

            <label class="block text-sm font-medium text-gray-700">Event name:</label>
            <input type="text" id="event-name" name="event_name" placeholder="Event Name" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event date:</label>
            <input type="datetime-local" id="event-date" name="event_date" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event location:</label>
            <input type="text" id="event-location" name="event_location" placeholder="Location" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4">

            <label class="block text-sm font-medium text-gray-700">Event description:</label>
            <textarea id="event-description" name="event_description" placeholder="Event Description" required class="w-full h-32 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-2 mb-4"></textarea>

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
