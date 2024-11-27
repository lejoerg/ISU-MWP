<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Admin Users</title>
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
</style>
<body style="background-color: #EEEEEE">

<h2 style="text-align: left; padding-left: 30px;">Active Admin Users</h2>
<table class="table">
    <thead>
        <tr>
            <th>Full Name</th>            
            <th>Email</th>
			<th>User ID</th>
            <th>Created At</th>
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

        // Fetch users data from the database
        $sql = "SELECT full_name, email, firebase_uid, created_at FROM users";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
				echo "<td>" . htmlspecialchars($row['firebase_uid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "</tr>";

            }
        } else {
            echo "<tr><td colspan='5'>No users found</td></tr>";
        }

        $conn->close();
        ?>
    </tbody>
</table>
</body>
</html>
