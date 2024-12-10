<?php
function render_event_slider($events_result) {
    echo '<div class="w-full mx-auto max-w-5xl px-4 mt-8">';
    echo '<div class="flex justify-between items-center mb-4">';
    echo '<div class="text-2xl font-bold text-white">Events Section';
    echo '<hr class="border-t-4 mx-auto w-full border-orange-500 mb-6 mt-1">';
    echo '</div>';
    
    echo '<div class="flex space-x-2">';
    echo '<button id="prev" class="bg-gray-600 p-2 rounded-full text-white hover:bg-gray-500">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />';
    echo '</svg>';
    echo '</button>';
    
    echo '<button id="next" class="bg-gray-600 p-2 rounded-full text-white hover:bg-gray-500">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />';
    echo '</svg>';
    echo '</button>';
    echo '</div>';
    echo '</div>';

    echo '<div id="slider" class="slider-container flex overflow-hidden space-x-4 mb-14">';

    if ($events_result->num_rows > 0) {
        while ($event = $events_result->fetch_assoc()) {
            $formatted_date = date("F j, Y, g:i a", strtotime($event['event_date']));
            $event_url = 'event.php?id=' . $event['event_id'];

            echo "<a href='" . $event_url . "' class='bg-gray-800 p-6 rounded-lg flex-none w-96 transition-transform transform hover:scale-105'>";
            echo "<div class='text-orange-400 text-sm mb-2'>" . htmlspecialchars($formatted_date) . "</div>";
            echo "<div class='text-lg text-white font-semibold'>" . htmlspecialchars($event['event_name']) . "</div>";
            echo "<div class='text-sm text-white mt-2'>" . htmlspecialchars($event['location']) . "</div>";
            echo "</a>";
        }
    } else {
        echo "<p class='text-center'>No events available.</p>";
    }

    echo '</div>';
    echo '</div>';
}
?>
