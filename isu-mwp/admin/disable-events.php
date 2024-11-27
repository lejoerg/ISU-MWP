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
    <title>Manage Events</title>
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
        .toggle-btn {
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
        <h2 style="text-align: center;">Manage Active Events</h2>
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

        // Check for delete submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $event_id = $_POST['event_id'];

            if ($_POST['submit'] === 'Disable') {
                // Update the event status to inactive
                $stmt = $conn->prepare("UPDATE events SET active = 0 WHERE event_id = ?");
            } elseif ($_POST['submit'] === 'Re-enable') {
                // Update the event status to active
                $stmt = $conn->prepare("UPDATE events SET active = 1 WHERE event_id = ?");
            }
            
            $stmt->bind_param("i", $event_id);

            if ($stmt->execute()) {
                echo '<div style="text-align: center; margin: 20px;">Event status updated successfully.</div>';
            } else {
                echo '<div style="text-align: center; color: red; margin: 20px;">Error: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        // Fetch events
        $sql_active = "SELECT * FROM events WHERE active = 1 ORDER BY date ASC, time ASC";
        $result_active = $conn->query($sql_active);

        if ($result_active->num_rows > 0) {
            echo '<table>';
            echo '<tr>
                    <th>Event ID</th>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Time</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>';

            while ($row = $result_active->fetch_assoc()) {
                $formattedTime = date("g:i A", strtotime($row['time']));
                $formattedDate = date("F j, Y", strtotime($row['date']));

                echo '<tr>';
                echo '<td>' . $row['event_id'] . '</td>';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td>' . $row['location'] . '</td>';
                echo '<td>' . $formattedTime . '</td>';
                echo '<td>' . $formattedDate . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '<td>
                        <form method="post" action="">
                            <input type="hidden" name="event_id" value="' . $row['event_id'] . '">
                            <button type="submit" name="submit" value="Disable" class="toggle-btn">Disable</button>
                        </form>
                      </td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo "<p style='text-align: center;'>No events found.</p>";
        }

        // Fetch disabled events
        echo '<h2 style="text-align: center; margin-top: 40px;">Manage Disabled Events</h2>';
        $sql_disabled = "SELECT * FROM events WHERE active = 0 ORDER BY date ASC, time ASC";
        $result_disabled = $conn->query($sql_disabled);

        if ($result_disabled->num_rows > 0) {
            echo '<table>';
            echo '<tr>
                    <th>Event ID</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>';

            while ($row = $result_disabled->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['event_id'] . '</td>';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td>' . $row['date'] . '</td>';
                echo '<td>' . $row['time'] . '</td>';
                echo '<td>' . $row['location'] . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '<td>
                        <form method="post" action="">
                            <input type="hidden" name="event_id" value="' . $row['event_id'] . '">
                            <button type="submit" name="submit" value="Re-enable" class="toggle-btn re-enable-btn">Enable</button>
                        </form>
                      </td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo "<p style='text-align: center;'>No disabled events found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
