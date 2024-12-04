<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Illinois State University - Men's Water Polo RSO</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <link href="../styles/additional-styles.css" rel="stylesheet">
    <?php include 'navbar.html'; ?>
</head>
<style>
.news-container {
    border: 2px solid black;
    border-radius: 10px;
    margin: 20px auto;
    width: 85%;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.25);
	background-color: #f8f9fa;
}

.news-header {
    display: flex;
	padding: 10px;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ddd;
    margin-bottom: 10px;
    padding-bottom: 5px;
    background-color: #CC0000;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
	color: #FFFFFF;
}

.news-title {
    flex-grow: 1;
}

.news-title h2 {
    margin: 0;
    font-size: 1.5em;
    font-weight: bold;
    text-align: left;
}

.news-title h4 {
    margin: 5px 0 0 0;
    font-size: 1.2em;
    font-weight: normal;
    text-align: left;
	color: #EEEEEE;
}

.news-date {
    font-size: 1em;
    font-weight: bold;
    text-align: right;
}

.news-container p {
    margin: 20px 10px;
    font-size: 1.1em;
    color: #444;
    line-height: 1.5em;
	text-align: left;
	padding: 10px;
}

</style>
<body>
    <main>
        <section>
            <h1 style="font-size: 3em; padding: 10px 30px; text-align: left; color: black;">News and Announcements</h1>
        </section>

<div id="newsContainer">
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

// Set default values for startDate and endDate if they are not provided
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '2024-01-01'; // Default to Jan 1, 2024
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d'); // Default to today's date

// Prepare SQL statement
$sql = "SELECT title, date_published, message 
        FROM news 
        WHERE date_published BETWEEN ? AND ? 
          AND active = 1 
        ORDER BY date_published DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

// Generate output
$output = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $formattedDate = date("F j, Y", strtotime($row['date_published']));
        $output .= '<div class="news-container">';
        $output .= '<div class="news-header">';
        $output .= '<div class="news-title">';
        $output .= '<h2>' . htmlspecialchars($row['title']) . '</h2>';
        $output .= '</div>';
        $output .= '<div class="news-date">' . $formattedDate . '</div>';
        $output .= '</div>';
        $output .= '<p>' . nl2br(htmlspecialchars($row['message'])) . '</p>';
        $output .= '</div>';
    }
} else {
    $output = "<p>No announcements found for the selected date range.</p>";
}

echo $output;

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
</script>
    <?php include 'footer.html'; ?>
</body>
</html>
