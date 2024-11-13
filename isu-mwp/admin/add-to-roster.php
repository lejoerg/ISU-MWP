<?php
session_start();
// Check if the user is logged in as an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../signin.php');
    exit;
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$databaseName = "water_polo";
$conn = new mysqli($servername, $username, $password, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distinct active years for the dropdown
$yearsResult = $conn->query("SELECT DISTINCT active_year FROM roster ORDER BY active_year DESC");

// Set default selected year to current year
$currentYear = date("Y");
$selectedYear = isset($_POST['active_year']) ? $_POST['active_year'] : $currentYear;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Roster</title>
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
        <h2 style="text-align: center;">Add to Roster</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="year">Year</label>
                <select class="form-control" id="year" name="year" required>
                    <option value="Freshman">Freshman</option>
                    <option value="Sophomore">Sophomore</option>
                    <option value="Junior">Junior</option>
                    <option value="Senior">Senior</option>
                </select>
            </div>
            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" class="form-control" id="position" name="position" placeholder="e.g., Goalie" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <input type="text" class="form-control" id="role" name="role" placeholder="e.g., President" required>
            </div>
            <div class="form-group">
                <label for="active_year">Active Year</label>
                <input type="text" class="form-control" id="active_year" name="active_year" required>
            </div>
            <div class="form-group">
                <label for="hometown_city">Hometown City</label>
                <input type="text" class="form-control" id="hometown_city" name="hometown_city" required>
            </div>
            <div class="form-group">
                <label for="hometown_state">Hometown State</label>
                <input type="text" class="form-control" id="hometown_state" name="hometown_state" required>
            </div>
            <div class="form-group">
                <label for="hometown_country">Hometown Country</label>
                <input type="text" class="form-control" id="hometown_country" name="hometown_country" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary" style="display: block; margin: 0 auto; background-color: #CC0000;">
                Add to Roster
            </button>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['submit'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $year = $_POST['year'];
    $position = $_POST['position'];
    $role = $_POST['role'];
    $active_year = $_POST['active_year'];
    $hometown_city = $_POST['hometown_city'];
    $hometown_state = $_POST['hometown_state'];
    $hometown_country = $_POST['hometown_country'];

    // Step 1: Get the highest rosterID from the roster table
    $query = "SELECT MAX(rosterID) AS max_rosterID FROM roster";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        $new_rosterID = $row['max_rosterID'] + 1;
    } else {
        $new_rosterID = 1; // Start with 1 if no existing data
    }

    // Step 2: Insert new player into roster
    $stmt = $conn->prepare("INSERT INTO roster (rosterID, first_name, last_name, year, position, role, active_year, hometown_city, hometown_state, hometown_country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssss", $new_rosterID, $first_name, $last_name, $year, $position, $role, $active_year, $hometown_city, $hometown_state, $hometown_country);

    if ($stmt->execute()) {
        echo '<div class="return-message" style="text-align: center; margin: 50px auto; width: fit-content;">Player added to roster successfully.</div>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
