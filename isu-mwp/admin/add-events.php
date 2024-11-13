<?php
session_start();
// Check if the user is logged in as an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../signin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link id="main_css" href="../../styles/style41.css" rel="stylesheet">
    <link href="../../styles/easy-responsive-tabs.css" rel="stylesheet">
    <link href="../../styles/additional-styles.css" rel="stylesheet">
    <?php include 'admin-bar.html'; ?>
    <style>
        .content-container {
            width: 40%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body style="background-color: #EEEEEE;">
    <div class="content-container">
        <h2 style="text-align: center;">Add Event</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary" style="display: block; margin: 0 auto; background-color: #CC0000;">
                Add Event
            </button>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['submit'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "water_polo";

    $title = $_POST['title'];
    $location = $_POST['location'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $conn = new mysqli($servername, $username, $password, $databaseName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Step 1: Get the highest event_id from the events table
    $query = "SELECT MAX(event_id) AS max_event_id FROM events"; // Correct table and column
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        $new_event_id = $row['max_event_id'] + 1; // Increment by 1
    } else {
        // Handle query error or set default value if no data
        $new_event_id = 1; // If no data, start from 1 or an appropriate value
    }

    // Step 2: Insert the new event along with the new event_id
    $stmt = $conn->prepare("INSERT INTO events (event_id, title, location, time, date, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $new_event_id, $title, $location, $time, $date, $description);

    if ($stmt->execute()) {
        echo '<div class="return-message" style="text-align: center; margin: 50px auto; width: fit-content;">Event added successfully.</div>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


