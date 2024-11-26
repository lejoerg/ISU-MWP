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
    <title>Edit Merchandise</title>
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
        .form-container input, .form-container select, .form-container textarea {
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

        // Check for edit submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            $merch_id = $_POST['merch_id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $active = isset($_POST['active']) ? 1 : 0;

            $img_location = $_FILES['img_location']['name'];
            $img_tmp = $_FILES['img_location']['tmp_name'];
            $upload_dir = "../../uploads/";
            
            if (!empty($img_location)) {
                // Move uploaded file to the designated directory
                $upload_path = $upload_dir . basename($img_location);
                if (move_uploaded_file($img_tmp, $upload_path)) {
                    $img_sql = ", img_location = '$upload_path'";
                } else {
                    echo '<div style="text-align: center; color: red; margin: 20px;">Image upload failed.</div>';
                }
            } else {
                $img_sql = ""; // If no new image is uploaded, don't change the img_location field
            }

            $sql = "UPDATE merchandise SET title = ?, description = ?, price = ?, active = ? $img_sql WHERE merch_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdii", $title, $description, $price, $active, $merch_id);

            if ($stmt->execute()) {
                echo '<div style="text-align: center; margin: 20px;">Merchandise updated successfully.</div>';
            } else {
                echo '<div style="text-align: center; color: red; margin: 20px;">Error: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        // Check if editing a merchandise entry
        if (isset($_GET['edit'])) {
            $merch_id = $_GET['edit'];

            // Fetch merchandise entry to edit
            $sql = "SELECT * FROM merchandise WHERE merch_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $merch_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $merchandise = $result->fetch_assoc();

            if ($merchandise) {
                ?>
                <div class="form-container">
                    <h3>Edit Merchandise</h3>
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="hidden" name="merch_id" value="<?php echo $merchandise['merch_id']; ?>">
                        
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($merchandise['title']); ?>" required>

                        <label for="description">Description:</label>
                        <textarea name="description" id="description" rows="4" required><?php echo htmlspecialchars($merchandise['description']); ?></textarea>

                        <label for="price">Price:</label>
                        <input type="number" name="price" id="price" step="0.01" value="<?php echo htmlspecialchars($merchandise['price']); ?>" required>

                        <label for="img_location">Image:</label>
                        <input type="file" name="img_location" id="img_location">
                        <small>Current Image: <a href="<?php echo htmlspecialchars($merchandise['img_location']); ?>" target="_blank">View</a></small>

                        <label for="active">Active:</label>
                        <input type="checkbox" name="active" id="active" <?php echo $merchandise['active'] == 1 ? 'checked' : ''; ?>>

                        <button type="submit" name="update" class="btn btn-danger">Update Merchandise</button>
                    </form>
                </div>
                <?php
            } else {
                echo '<div style="text-align: center; margin: 20px; color: red;">No such merchandise entry found.</div>';
            }
        } else {
            // Fetch merchandise entries
            $sql = "SELECT * FROM merchandise ORDER BY title ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Active</th>
                        <th>Actions</th>
                      </tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                    echo '<td>$' . number_format($row['price'], 2) . '</td>';
                    echo '<td>' . ($row['active'] == 1 ? 'Yes' : 'No') . '</td>';
                    echo '<td>
                            <a href="?edit=' . $row['merch_id'] . '" class="edit-btn">Edit</a>
                          </td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<div style="text-align: center; margin: 20px;">No merchandise found.</div>';
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
