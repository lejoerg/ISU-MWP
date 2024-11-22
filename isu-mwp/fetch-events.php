<?php
$servername = "localhost";
$username = "root";
$password = "";
$databaseName = "water_polo";

// Create connection
$conn = new mysqli($servername, $username, $password, $databaseName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Current date
$currentDate = date("Y-m-d");

// Check if we should include past events
$showPast = isset($_GET['show_past']) && $_GET['show_past'] === 'true';

$sql = $showPast
    ? "SELECT title, location, time, date, description FROM events WHERE active = 1 ORDER BY date ASC, time ASC"
    : "SELECT title, location, time, date, description FROM events WHERE active = 1 AND date >= '$currentDate' ORDER BY date ASC, time ASC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $isPastEvent = ($row['date'] < $currentDate);
        $eventClass = $isPastEvent ? 'event-container past-event' : 'event-container';

        $formattedTime = date("g:i A", strtotime($row['time']));
        $formattedDate = date("F j, Y", strtotime($row['date']));
        $imageSrc = '../img/default-event.jpg';
        if ($row['title'] === "Tournament") {
            $imageSrc = '../img/tournament-event.jpg';
        } elseif ($row['title'] === "Team Practice") {
            $imageSrc = '../img/horton-event.jpg';
        }

        echo '<div class="' . $eventClass . '">';
        echo '<div class="event-image"><img src="' . $imageSrc . '" alt="Event Image"></div>';
        echo '<div class="event-details">';
        echo '<h1 style="padding: 0 25px; color: black;">' . $row['title'] . '</h1>';
        echo '<div class="event-section time"><strong>Time:</strong> ' . $formattedTime . '</div>';
        echo '<div class="event-section date"><strong>Date:</strong> ' . $formattedDate . '</div>';
        echo '<div class="event-section location"><strong>Location:</strong> ' . $row['location'] . '</div>';
        echo '<div class="event-section description"><i>' . $row['description'] . '</i></div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "<p>No events found.</p>";
}

$conn->close();
?>
