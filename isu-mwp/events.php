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
            <h1 style="font-size: 3em; padding: 10px 30px; text-align: left; color: black;">Upcoming Schedule</h1>
            <!-- Checkbox to toggle past events display -->
            <label style="display: flex; align-items: center; margin-left: 100px;">
                <input type="checkbox" id="showPastEvents" onchange="togglePastEvents()" style="margin-right: 10px; accent-color: #CC0000;"> 
                Show Past Events
            </label>
        </section>
        
        <div id="eventsContainer">
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

            // Determine if past events should be shown
            $showPast = isset($_GET['show_past']) && $_GET['show_past'] === 'true';

            // Fetch events based on the checkbox
            $sql = $showPast 
                ? "SELECT title, location, time, date, description FROM events ORDER BY date ASC, time ASC" 
                : "SELECT title, location, time, date, description FROM events WHERE date >= '$currentDate' ORDER BY date ASC, time ASC";

            $result = $conn->query($sql);

            // Check if there are any results
            if ($result->num_rows > 0) {
                // Loop through each event and display in the container
                while ($row = $result->fetch_assoc()) {
                    // Format time and date
                    $formattedTime = date("g:i A", strtotime($row['time']));
                    $formattedDate = date("F j, Y", strtotime($row['date']));
                    
                    // Determine the image based on the event title
                    if ($row['title'] == "Tournament") {
                        $imageSrc = '../img/tournament-event.jpg';
                    } elseif ($row['title'] == "Team Practice") {
                        $imageSrc = '../img/horton-event.jpg';
                    } else {
                        $imageSrc = '../img/default-event.jpg';
                    }

                    // Set class based on whether the event is past or upcoming
                    $eventClass = ($row['date'] < $currentDate) ? 'event-container past-event' : 'event-container';
                    
                    // Display event details
                    echo '<div class="' . $eventClass . '">';
                    echo '<div class="event-image"><img src="' . $imageSrc . '" alt="Event Image"></div>';
                    echo '<div class="event-details">';
                    echo '<h1 style="padding: 0 25px; color: black;">' . $row['title'] . '</h1>';
                    echo '<div class="event-section time"><strong>Time:</strong> <span>' . $formattedTime . '</span></div>';
                    echo '<div class="event-section date"><strong>Date:</strong> ' . $formattedDate . '</div>';
                    echo '<div class="event-section location"><strong>Location:</strong> ' . $row['location'] . '</div>';
                    echo '<div class="event-section description"><i>' . $row['description'] . '</i></div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>No events found.</p>";
            }

            // Close connection
            $conn->close();
            ?>
        </div>
    </main>
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

    function togglePastEvents() {
        const checkbox = document.getElementById('showPastEvents');

        if (checkbox.checked) {
            // Send request to fetch past events if checkbox is checked
            fetchPastEvents();
        } else {
            // Reload the page to only show upcoming events
            location.reload();
        }
    }

    function fetchPastEvents() {
        const eventsContainer = document.getElementById('eventsContainer');

        // AJAX request to fetch and display past events
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch-events.php?show_past=true", true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                eventsContainer.innerHTML = xhr.responseText;

                // Apply grayscale filter for past events
                document.querySelectorAll('.past-event').forEach(event => {
                    // Set the entire event container to grayscale
                    event.style.filter = 'grayscale(100%)';
                });
            }
        };
        xhr.send();
    }
</script>

    <?php include 'footer.html'; ?>
</body>
</html>
