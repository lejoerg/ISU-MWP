<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Portal</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.html'; ?>
    <h1>Welcome to the Admin Portal</h1>
    <h2>Admin Controls</h2>
    <ul>
        <li><a href="edit_roster.php">Edit/Update Roster</a></li>
        <li><a href="update_stats.php">Update Statistics</a></li>
        <li><a href="update_events.php">Update Events and Event Information</a></li>
    </ul>
    
    <script src="../js/external-js.js"></script>
</body>
</html>
