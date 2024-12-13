<?php
session_start(); 
include '../../Component/navbar.php';
include '../../../db_connection.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
$userName = $isLoggedIn ? $_SESSION['username'] : null;

$navbar = new Navbar($conn, $isLoggedIn, $userId, $userName);

$category_name = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); 
    header("Location: index.php"); 
    exit;
}

$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Pobierz wydarzenia
$sql_events = "SELECT event_id, event_name, event_description, event_date, location FROM events ORDER BY event_date ASC";
$events_result = $conn->query($sql_events);

$firstDayOfMonth = date('w', mktime(0, 0, 0, $month, 1, $year));

// Tworzymy obiekt DateTime dla pierwszego dnia miesiąca
$date = new DateTime("$year-$month-01");
$daysInMonth = $date->format('t');

// Tworzymy tablicę z dniami miesiąca
$daysArray = array_fill(0, $daysInMonth, '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalendarz wydarzeń - Miesięczny Widok</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .calendar-container {
            background-color: #1f2937; /* bg-gray-800 */
        }
        .calendar-header, .calendar-day {
            color: #cbd5e1; /* text-gray-400 */
        }
        .calendar-cell {
            background-color: #374151; /* bg-gray-700 */
        }
        .event-card {
            background-color: #2563eb; /* bg-blue-600 */
            border-color: #1d4ed8; /* border-blue-500 */
        }
        .event-card-title {
            color: #e0f2fe; /* text-blue-50 */
        }
        .event-card-time, .event-card-location {
            color: #d1d5db; /* text-gray-400 */
        }
    </style>
    <script>
        function changeMonth(offset) {
            const urlParams = new URLSearchParams(window.location.search);
            let month = parseInt(urlParams.get('month')) || new Date().getMonth() + 1;
            let year = parseInt(urlParams.get('year')) || new Date().getFullYear();

            month += offset;
            if (month > 12) {
                month = 1;
                year++;
            } else if (month < 1) {
                month = 12;
                year--;
            }

            urlParams.set('month', month);
            urlParams.set('year', year);
            window.location.search = urlParams.toString();
        }
    </script>
</head>
<body class="bg-gray-900 p-6">
    <div class="bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="flex justify-between items-center py-4 px-6 calendar-header">
            <button onclick="changeMonth(-1)" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-bold py-2 px-4 rounded-l">
                &lt; Previous
            </button>
            <div class="text-center text-2xl font-bold calendar-header"><?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></div>
            <button onclick="changeMonth(1)" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-bold py-2 px-4 rounded-r">
                Next &gt;
            </button>
        </div>
        <div class="grid grid-cols-7 bg-gray-700 text-center text-xs font-bold uppercase tracking-wide text-gray-400">
            <div class="py-2 calendar-day">Sun</div>
            <div class="py-2 calendar-day">Mon</div>
            <div class="py-2 calendar-day">Tue</div>
            <div class="py-2 calendar-day">Wed</div>
            <div class="py-2 calendar-day">Thu</div>
            <div class="py-2 calendar-day">Fri</div>
            <div class="py-2 calendar-day">Sat</div>
        </div>
        <div class="grid grid-cols-7 gap-px bg-gray-700 border-t border-gray-600 calendar-container">
            <?php
            // Puste komórki na początku miesiąca
            for ($i = 0; $i < $firstDayOfMonth; $i++) {
                echo '<div class="bg-gray-800 h-32 p-2"></div>';
            }

            if ($events_result->num_rows > 0) {
                while ($row = $events_result->fetch_assoc()) {
                    $eventId = $row['event_id'];
                    $eventName = $row['event_name'];
                    $eventDescription = $row['event_description'];
                    $eventDate = $row['event_date'];
                    $eventLocation = $row['location'];

                    $eventDateFormatted = date('d M Y, H:i A', strtotime($eventDate));
                    $eventDay = date('j', strtotime($eventDate));
                    $eventMonth = date('m', strtotime($eventDate));
                    $eventYear = date('Y', strtotime($eventDate));

                    // Sprawdź, czy wydarzenie jest w bieżącym miesiącu
                    if ($eventMonth == $month && $eventYear == $year) {
                        // Dodaj wydarzenie do odpowiedniego dnia
                        $daysArray[$eventDay-1] .= '<a href="event.php?id=' . $eventId . '">';
                        $daysArray[$eventDay-1] .= '<div class="bg-blue-600 border-l-4 border-blue-500 p-2 mb-2 rounded event-card">';
                        $daysArray[$eventDay-1] .= '<p class="font-semibold event-card-title">' . $eventName . '</p>';
                        $daysArray[$eventDay-1] .= '<p class="text-xs event-card-time">' . $eventDateFormatted . '</p>';
                        $daysArray[$eventDay-1] .= '<p class="text-xs event-card-location">' . $eventLocation . '</p>';
                        $daysArray[$eventDay-1] .= '</div>';
                        $daysArray[$eventDay-1] .= '</a>';
                    }
                }
            }

            // Wyświetl dni miesiąca z wydarzeniami
            for ($i = 0; $i < $daysInMonth; $i++) {
                echo '<div class="bg-gray-800 h-32 p-2 calendar-cell">' . ($daysArray[$i] ?: '<p class="text-gray-500 text-xs">No events</p>') . '</div>';
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
