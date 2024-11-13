<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../signin.php');
    exit;
}

if (isset($_POST['submit'])) {
    // Get Firebase User ID from form input
    $firebaseUid = $_POST['firebase_uid'];

    // Initialize cURL to call the Node.js admin service
    $ch = curl_init('http://localhost:3000/deleteUser');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['firebaseUid' => $firebaseUid]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if ($response === false) {
        $message = "Error: " . curl_error($ch);
        $status = 'error';
    } else {
        $userResponse = json_decode($response, true);

        // Check if the Node.js service deleted the user successfully
        if (isset($userResponse['message'])) {
            // Connect to local DB
            $mysqli = new mysqli('localhost', 'root', '', 'water_polo');
            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            // Delete user from the local 'users' table
            $stmt = $mysqli->prepare("DELETE FROM users WHERE firebase_uid = ?");
            $stmt->bind_param("s", $firebaseUid);

            if ($stmt->execute()) {
                $message = 'User deleted successfully.';
                $status = 'success';
            } else {
                $message = "Error: " . $stmt->error;
                $status = 'error';
            }

            $stmt->close();
            $mysqli->close();
        } else {
            $message = "Error deleting user: " . $userResponse['error'];
            $status = 'error';
        }
    }

    curl_close($ch);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
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
</head>
<body style="background-color: #EEEEEE;">
    <div class="content-container">
        <h2 style="text-align: center;">Delete User</h2>
        <form style="padding-bottom: 20px;" method="POST" action="">
            <div class="form-group">
                <label for="firebase_uid">Firebase User ID</label>
                <input type="text" class="form-control" id="firebase_uid" name="firebase_uid" required>
            </div>
            <button type="submit" name="submit" class="btn btn-danger" style="width: 100%; background-color: #CC0000;">Delete User</button>
        </form>

        <?php if (isset($message)) : ?>
            <div class="return-message <?php echo $status === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User ID</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch and display users from the database
                $conn = new mysqli('localhost', 'root', '', 'water_polo');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT full_name, email, firebase_uid, created_at FROM users";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['firebase_uid']) . "</td>";
                        echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No users found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
