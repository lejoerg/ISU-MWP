<?php
session_start();

// Check if the user is logged in and a super admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['super_admin'] != 1) {
    // Redirect to a restricted access page if not a super admin
    header("Location: not-found.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users</title>
    <link id="main_css" href="../../styles/style41.css" rel="stylesheet">
    <link href="../../styles/easy-responsive-tabs.css" rel="stylesheet">
    <link href="../../styles/additional-styles.css" rel="stylesheet">
    <?php include 'admin-bar.php'; ?>
</head>
<style>
    table {
        margin: 20px auto;
        width: 80%;
        border-collapse: collapse;
        text-align: center;
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td {
        padding: 12px;
        text-align: left;
        background-color: white;
    }
    th {
        background-color: #CC0000;
        color: white;
    }
    .container {
        margin: 20px auto;
        width: 90%;
        padding: 20px;
        background-color: #FFFFFF;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    h2 {
        text-align: left;
        padding-left: 30px;
    }
</style>
<body style="background-color: #EEEEEE">

<div class="container">
    <h2>Active Admin Users</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>User ID</th>
                <th>Created At</th>
				<th>Super Admin</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "water_polo";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch active users
            $sql = "SELECT full_name, email, firebase_uid, created_at, super_admin FROM users WHERE active = 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['firebase_uid']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
					echo "<td>" . htmlspecialchars($row['super_admin']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No active users found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="container">
    <h2>Inactive Users</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>User ID</th>
                <th>Created At</th>
				<th>Super Admin</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch inactive users
            $sql = "SELECT full_name, email, firebase_uid, created_at FROM users WHERE active = 0";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['firebase_uid']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
					echo "<td>" . htmlspecialchars($row['super_admin']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No inactive users found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
