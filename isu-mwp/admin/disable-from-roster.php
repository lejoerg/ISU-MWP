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
    <title>Disable Player from Roster</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link id="main_css" href="../../styles/style41.css" rel="stylesheet">
    <link href="../../styles/easy-responsive-tabs.css" rel="stylesheet">
    <link href="../../styles/additional-styles.css" rel="stylesheet">
    <?php include 'admin-bar.php'; ?>
    <style>
        .content-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #CC0000;
            color: white;
        }
        .delete-btn {
            background-color: #CC0000;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }
        .re-enable-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body style="background-color: #EEEEEE;">
    <div class="content-container">
        <h2 style="text-align: center;">Manage Active Players</h2>
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $rosterID = $_POST['rosterID'];

            if ($_POST['submit'] === 'Disable') {
                $stmt = $conn->prepare("UPDATE roster SET active = 0 WHERE rosterID = ?");
            } elseif ($_POST['submit'] === 'Re-enable') {
                $stmt = $conn->prepare("UPDATE roster SET active = 1 WHERE rosterID = ?");
            }
            
            $stmt->bind_param("i", $rosterID);

            if ($stmt->execute()) {
                echo '<div style="text-align: center; margin: 20px;">Player status updated successfully.</div>';
            } else {
                echo '<div style="text-align: center; color: red; margin: 20px;">Error: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        // Fetch active players
        $sql_active = "SELECT * FROM roster WHERE active = 1 ORDER BY last_name ASC";
        $result_active = $conn->query($sql_active);

        if ($result_active->num_rows > 0) {
            echo '<table>';
            echo '<tr>
                    <th>Player ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Year</th>
                    <th>Hometown</th>
                    <th>Position</th>
                    <th>Role</th>
                    <th>Actions</th>
                  </tr>';

            while ($row = $result_active->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['rosterID'] . '</td>';
                echo '<td>' . $row['first_name'] . '</td>';
                echo '<td>' . $row['last_name'] . '</td>';
                echo '<td>' . $row['year'] . '</td>';
                echo '<td>' . $row['hometown_city'] . ', ' . $row['hometown_state'] . '</td>';
                echo '<td>' . $row['position'] . '</td>';
                echo '<td>' . $row['role'] . '</td>';
                echo '<td>
                        <form method="post" action="">
                            <input type="hidden" name="rosterID" value="' . $row['rosterID'] . '">
                            <button type="submit" name="submit" value="Disable" class="delete-btn">Disable</button>
                        </form>
                      </td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo "<p style='text-align: center;'>No active players found in the roster.</p>";
        }

        // Fetch disabled players
        echo '<h2 style="text-align: center; margin-top: 40px;">Manage Disabled Players</h2>';
        $sql_disabled = "SELECT * FROM roster WHERE active = 0 ORDER BY last_name ASC";
        $result_disabled = $conn->query($sql_disabled);

        if ($result_disabled->num_rows > 0) {
            echo '<table>';
            echo '<tr>
                    <th>Player ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Year</th>
                    <th>Hometown</th>
                    <th>Position</th>
                    <th>Role</th>
                    <th>Actions</th>
                  </tr>';

            while ($row = $result_disabled->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['rosterID'] . '</td>';
                echo '<td>' . $row['first_name'] . '</td>';
                echo '<td>' . $row['last_name'] . '</td>';
                echo '<td>' . $row['year'] . '</td>';
                echo '<td>' . $row['hometown_city'] . ', ' . $row['hometown_state'] . '</td>';
                echo '<td>' . $row['position'] . '</td>';
                echo '<td>' . $row['role'] . '</td>';
                echo '<td>
                        <form method="post" action="">
                            <input type="hidden" name="rosterID" value="' . $row['rosterID'] . '">
                            <button type="submit" name="submit" value="Re-enable" class="re-enable-btn">Enable</button>
                        </form>
                      </td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo "<p style='text-align: center;'>No disabled players found in the roster.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
