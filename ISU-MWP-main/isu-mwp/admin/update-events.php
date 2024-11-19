<?php
session_start();
// Check if the user is logged in as an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../signin.php');
    exit;
}

// Ensure an event ID is provided for updating
if (!isset($_GET['event_id'])) {
    echo "Error: Event ID not provided.";
    exit;
}

$event_id = intval($_GET['event_id']);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$databaseName = "water_polo";

$conn = new mysqli($servername, $username, $password, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch existing event details
$query = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Error: Event not found.";
    exit;
}

$event = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Event</title>
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
        <h2 style="text-align: center;">Update Event</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" class="form-control" id="time" name="time" value="<?php echo htmlspecialchars($event['time']); ?>" required>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($event['date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary" style="display: block; margin: 0 auto; background-color: #CC0000;">
                Update Event
            </button>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    // Update the event details
    $updateQuery = "UPDATE events SET title = ?, location = ?, time = ?, date = ?, description = ? WHERE event_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssssi", $title, $location, $time, $date, $description, $event_id);

    if ($stmt->execute()) {
        echo '<div class="return-message" style="text-align: center; margin: 50px auto; width: fit-content;">Event updated successfully.</div>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
