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
    <title>Manage Merchandise</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link id="main_css" href="../../styles/style41.css" rel="stylesheet">
    <link href="../../styles/easy-responsive-tabs.css" rel="stylesheet">
    <link href="../../styles/additional-styles.css" rel="stylesheet">
    <?php include 'admin-bar.html'; ?>
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
        <h2 style="text-align: center;">Manage Merchandise</h2>
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

        // Check for toggle submission (disable/enable)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $merch_id = $_POST['merch_id'];
            $current_status = $_POST['current_status'];

            // Toggle the active status
            $new_status = ($current_status == 1) ? 0 : 1;

            $stmt = $conn->prepare("UPDATE merchandise SET active = ? WHERE merch_id = ?");
            $stmt->bind_param("ii", $new_status, $merch_id);

            if ($stmt->execute()) {
                echo '<div style="text-align: center; margin: 20px;">Status updated successfully.</div>';
            } else {
                echo '<div style="text-align: center; color: red; margin: 20px;">Error: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        // Fetch active merchandise
        $sql = "SELECT * FROM merchandise WHERE active = 1 ORDER BY title ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<h2 style="text-align: center;">Active Merchandise</h2>';
            echo '<table>';
            echo '<tr>
                    <th>Merchandise ID</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['merch_id'] . '</td>';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td>$' . number_format($row['price'], 2) . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '<td>
                        <form method="post" action="">
                            <input type="hidden" name="merch_id" value="' . $row['merch_id'] . '">
                            <input type="hidden" name="current_status" value="1">
                            <button type="submit" name="toggle" class="toggle-btn">Disable</button>
                        </form>
                      </td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo "<p style='text-align: center;'>No merchandise found.</p>";
        }

        // Fetch disabled merchandise
        $sql_disabled = "SELECT * FROM merchandise WHERE active = 0 ORDER BY title ASC";
        $result_disabled = $conn->query($sql_disabled);

        if ($result_disabled->num_rows > 0) {
            echo '<h2 style="text-align: center; margin-top: 40px;">Disabled Merchandise</h2>';
            echo '<table>';
            echo '<tr>
                    <th>Merchandise ID</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>';

            while ($row = $result_disabled->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['merch_id'] . '</td>';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td>$' . number_format($row['price'], 2) . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '<td>
                        <form method="post" action="">
                            <input type="hidden" name="merch_id" value="' . $row['merch_id'] . '">
                            <input type="hidden" name="current_status" value="0">
                            <button type="submit" name="toggle" class="toggle-btn re-enable-btn">Enable</button>
                        </form>
                      </td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo "<p style='text-align: center;'>No disabled merchandise found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
