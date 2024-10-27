<html>
<head>
    <meta charset="utf-8">
    <title>Illinois State University - Men's Water Polo RSO</title>
	<link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <link href="../styles/easy-responsive-tabs.css" rel="stylesheet">
	<link href="../styles/additional-styles.css" rel="stylesheet">
	<?php include 'navbar.html'; ?>
</head>
<body>
    <main>
        <section class="about-us">
            <h1 style="padding: 10px 30px; text-align: left; color: black;">Upcoming Schedule</h1>
        </section>
    </main>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$databaseName = "water_polo";

// Create Connection
$conn = new mysqli($servername, $username, $password, $databaseName);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Current date
$currentDate = date("Y-m-d");

// Query to fetch upcoming events (date > current date), sorted by date
$sql = "SELECT title, location, time, date, description FROM events WHERE date > '$currentDate' ORDER BY date ASC";
$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
    // Loop through each event and display in the container
    while($row = $result->fetch_assoc()) {
        // Display event details
        echo '<div class="event-container">';
        echo '<div class="event-image">';
        echo '<img src="../img/horton-event.jpg" alt="Event Image">'; // Placeholder image, update as needed
        echo '</div>';
        echo '<div class="event-details">';
        echo '<h1 style="padding: 0 25px; color: black;">' . $row['title'] . '</h1>';
        echo '<div class="event-section time"><strong>Time:</strong> ' . $row['time'] . '</div>';
        echo '<div class="event-section date"><strong>Date:</strong> ' . $row['date'] . '</div>';
        echo '<div class="event-section location"><strong>Location:</strong> ' . $row['location'] . '</div>';
        echo '<div class="event-section description"><i>' . $row['description'] . '</i></div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "<p>No upcoming events found.</p>";
}

// Close connection
$conn->close();
?>
<script> 
document.addEventListener('DOMContentLoaded', function () {
    const currentPath = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll('.o-nav-main__link');

    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
});
</script>
<?php include 'footer.html'; ?>
</body>	
</html>