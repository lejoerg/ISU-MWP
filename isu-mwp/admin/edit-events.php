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
    <title>Edit Events</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link id="main_css" href="../../styles/style41.css" rel="stylesheet">
    <link href="../../styles/easy-responsive-tabs.css" rel="stylesheet">
    <link href="../../styles/additional-styles.css" rel="stylesheet">
    <?php include 'admin-bar.php'; ?>
    <style>
        /* Same styling as edit-news.php */
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
.edit-btn {
    background-color: #CC0000; /* Red color */
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none; /* Ensure no underline */
    display: block;
    margin: 0 auto;
    text-align: center;
}

.edit-btn:hover {
    background-color: #A10000;
	color: white;
	text-decoration: none;
}

.edit-btn:visited {
    color: white;
    background-color: #CC0000;
}

.edit-btn:focus, .edit-btn:active {
    outline: none;
    text-decoration: none;
}
        .form-container {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container input, .form-container textarea {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-container button {
            background-color: #CC0000;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body style="background-color: #EEEEEE;">
    <div class="content-container">
        <h2 style="text-align: center;">Manage Events</h2>
        
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

        // Check for edit submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            $event_id = $_POST['event_id'];
            $title = $_POST['title'];
            $location = $_POST['location'];
            $time = $_POST['time'];
            $date = $_POST['date'];
            $description = $_POST['description'];
            $active = isset($_POST['active']) ? 1 : 0;

            $stmt = $conn->prepare("UPDATE events SET title = ?, location = ?, time = ?, date = ?, description = ?, active = ? WHERE event_id = ?");
            $stmt->bind_param("ssssssi", $title, $location, $time, $date, $description, $active, $event_id);

            if ($stmt->execute()) {
                echo '<div style="text-align: center; margin: 20px;">Event updated successfully.</div>';
            } else {
                echo '<div style="text-align: center; color: red; margin: 20px;">Error: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        // Check if editing an event entry
        if (isset($_GET['edit'])) {
            $event_id = $_GET['edit'];

            // Fetch event entry to edit
            $sql = "SELECT * FROM events WHERE event_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $event = $result->fetch_assoc();

            if ($event) {
                ?>
                <div class="form-container">
                    <h3>Edit Event</h3>
                    <form method="post" action="">
                        <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                        
                        <label for="location">Location:</label>
                        <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
                        
                        <label for="time">Time:</label>
                        <input type="time" name="time" id="time" value="<?php echo htmlspecialchars($event['time']); ?>" required>

                        <label for="date">Date:</label>
                        <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($event['date']); ?>" required>
                        
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>

                        <label for="active">Active:</label>
                        <input type="checkbox" name="active" id="active" <?php echo $event['active'] == 1 ? 'checked' : ''; ?>>

                        <button type="submit" name="update" class="btn btn-danger">Update Event</button>
                    </form>
                </div>
                <?php
            } else {
                echo '<div style="text-align: center; margin: 20px; color: red;">No such event entry found.</div>';
            }
        } else {
            // Fetch event entries
            $sql = "SELECT * FROM events ORDER BY date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<tr>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Actions</th>
                      </tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['title'] . '</td>';
                    echo '<td>' . $row['location'] . '</td>';
                    echo '<td>' . $row['time'] . '</td>';
                    echo '<td>' . $row['date'] . '</td>';
                    echo '<td>' . $row['description'] . '</td>';
                    echo '<td>
                            <a href="?edit=' . $row['event_id'] . '" class="edit-btn">Edit</a>
                          </td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo "<p style='text-align: center;'>No event entries found.</p>";
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
