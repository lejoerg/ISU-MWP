<?php
// Start the session
session_start();

// Default to the super-portal
$redirect_url = 'super-portal.php';

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'water_polo');

// Check database connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    // Fetch the super_admin value from the users table
    $stmt = $mysqli->prepare("SELECT super_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($super_admin);
    $stmt->fetch();
    $stmt->close();

    // Determine redirect URL based on super_admin value
    if (isset($super_admin) && $super_admin == 0) {
        $redirect_url = 'admin-portal.php';
    }
} else {
    // If the user is not logged in, redirect to login page
    $redirect_url = 'login.php';
}

// Close the database connection
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* Navbar styling */
        .admin-bar {
            background-color: #CC0000;
            color: white;
            font-size: 18px;
            font-weight: bold;
            line-height: 2;
            padding: 10px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .admin-bar .back-button {
            position: absolute;
            left: 15px;
            background-color: white;
            color: #CC0000;
            font-size: 18px;
            border: none;
            border-radius: 15px;
            padding: 5px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-weight: bold;
        }

        .admin-bar .back-button:hover {
            background-color: #ffffffaa;
        }

        .admin-bar .back-button::before {
            content: '←';
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-bar">
        <!-- Back button -->
        <button class="back-button" onclick="window.location.href='<?php echo htmlspecialchars($last_page); ?>';">
            Back
        </button>
        <!-- Title -->
        Admin Portal
    </div>
</body>
</html>
