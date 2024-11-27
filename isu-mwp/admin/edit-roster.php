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
    <title>Edit Player</title>
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
        .form-container input, .form-container select {
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
        <h2 style="text-align: center;">Manage Roster</h2>
        
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
            $rosterID = $_POST['rosterID'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $year = $_POST['year'];
            $hometown_city = $_POST['hometown_city'];
            $hometown_state = $_POST['hometown_state'];
            $hometown_country = $_POST['hometown_country'];
            $position = $_POST['position'];
            $role = $_POST['role'];
            $active_year = $_POST['active_year'];
            $active = isset($_POST['active']) ? 1 : 0;

            $stmt = $conn->prepare("UPDATE roster SET first_name = ?, last_name = ?, year = ?, hometown_city = ?, hometown_state = ?, hometown_country = ?, position = ?, role = ?, active_year = ?, active = ? WHERE rosterID = ?");
            $stmt->bind_param("ssssssssiii", $first_name, $last_name, $year, $hometown_city, $hometown_state, $hometown_country, $position, $role, $active_year, $active, $rosterID);

            if ($stmt->execute()) {
                echo '<div style="text-align: center; margin: 20px;">Player updated successfully.</div>';
            } else {
                echo '<div style="text-align: center; color: red; margin: 20px;">Error: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        // Check if editing a roster entry
        if (isset($_GET['edit'])) {
            $rosterID = $_GET['edit'];

            // Fetch roster entry to edit
            $sql = "SELECT * FROM roster WHERE rosterID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $rosterID);
            $stmt->execute();
            $result = $stmt->get_result();
            $roster = $result->fetch_assoc();

            if ($roster) {
                ?>
                <div class="form-container">
                    <h3>Edit Player</h3>
                    <form method="post" action="">
                        <input type="hidden" name="rosterID" value="<?php echo $roster['rosterID']; ?>">
                        <label for="first_name">First Name:</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($roster['first_name']); ?>" required>
                        
                        <label for="last_name">Last Name:</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($roster['last_name']); ?>" required>
                        
                        <label for="year">Year:</label>
                        <input type="text" name="year" id="year" value="<?php echo htmlspecialchars($roster['year']); ?>" required>

                        <label for="hometown_city">Hometown City:</label>
                        <input type="text" name="hometown_city" id="hometown_city" value="<?php echo htmlspecialchars($roster['hometown_city']); ?>" required>

                        <label for="hometown_state">Hometown State:</label>
                        <input type="text" name="hometown_state" id="hometown_state" value="<?php echo htmlspecialchars($roster['hometown_state']); ?>" required>

                        <label for="hometown_country">Hometown Country:</label>
                        <input type="text" name="hometown_country" id="hometown_country" value="<?php echo htmlspecialchars($roster['hometown_country']); ?>" required>

                        <label for="position">Position:</label>
                        <input type="text" name="position" id="position" value="<?php echo htmlspecialchars($roster['position']); ?>" required>

                        <label for="role">Role:</label>
                        <input type="text" name="role" id="role" value="<?php echo htmlspecialchars($roster['role']); ?>" required>

                        <label for="active_year">Active Year:</label>
                        <input type="text" name="active_year" id="active_year" value="<?php echo htmlspecialchars($roster['active_year']); ?>" required>

                        <label for="active">Active:</label>
                        <input type="checkbox" name="active" id="active" <?php echo $roster['active'] == 1 ? 'checked' : ''; ?>>

                        <button type="submit" name="update" class="btn btn-danger">Update Player</button>
                    </form>
                </div>
                <?php
            } else {
                echo '<div style="text-align: center; margin: 20px; color: red;">No such roster entry found.</div>';
            }
        } else {
            // Fetch roster entries
            $sql = "SELECT * FROM roster ORDER BY year ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Year</th>
                        <th>Position</th>
                        <th>Role</th>
                        <th>Actions</th>
                      </tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['first_name'] . '</td>';
                    echo '<td>' . $row['last_name'] . '</td>';
                    echo '<td>' . $row['year'] . '</td>';
                    echo '<td>' . $row['position'] . '</td>';
                    echo '<td>' . $row['role'] . '</td>';
                    echo '<td>
                            <a href="?edit=' . $row['rosterID'] . '" class="edit-btn">Edit</a>
                          </td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<div style="text-align: center; margin: 20px;">No player found.</div>';
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
