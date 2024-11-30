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
    <title>Edit Users</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../styles/style41.css" rel="stylesheet">
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
        <h2 style="text-align: center;">Manage Users</h2>

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

        // Check for update submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            $id = $_POST['id'];
            $full_name = $_POST['full_name'];
            $super_admin = $_POST['super_admin'];
            $active = isset($_POST['active']) ? 1 : 0;

            $stmt = $conn->prepare("UPDATE users SET full_name = ?, super_admin = ?, active = ? WHERE id = ?");
            $stmt->bind_param("siii", $full_name, $super_admin, $active, $id);

            if ($stmt->execute()) {
                echo '<div style="text-align: center; margin: 20px;">User updated successfully.</div>';
            } else {
                echo '<div style="text-align: center; color: red; margin: 20px;">Error: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        // Check if editing a user entry
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];

            // Fetch user entry to edit
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                ?>
                <div class="form-container">
                    <h3>Edit User</h3>
                    <form method="post" action="edit-users.php">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <label for="full_name">Full Name:</label>
                        <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

                        <label for="super_admin">Super Admin:</label>
                        <select name="super_admin" id="super_admin" required>
                            <option value="1" <?php echo $user['super_admin'] == 1 ? 'selected' : ''; ?>>Yes</option>
                            <option value="0" <?php echo $user['super_admin'] == 0 ? 'selected' : ''; ?>>No</option>
                        </select>

                        <label for="active">Active:</label>
                        <input type="checkbox" name="active" id="active" <?php echo $user['active'] == 1 ? 'checked' : ''; ?>>

                        <button type="submit" name="update" class="btn btn-danger">Update User</button>
                    </form>
                </div>
                <?php
            } else {
                echo '<div style="text-align: center; margin: 20px; color: red;">No such user found.</div>';
            }
        } else {
            // Fetch user entries
            $sql = "SELECT id, full_name, email, super_admin, active FROM users ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Super Admin</th>
                        <th>Active</th>
                        <th>Actions</th>
                      </tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                    echo '<td>' . ($row['super_admin'] == 1 ? 'Yes' : 'No') . '</td>';
                    echo '<td>' . ($row['active'] == 1 ? 'Yes' : 'No') . '</td>';
                    echo '<td>
                            <a href="?edit=' . $row['id'] . '" class="edit-btn">Edit</a>
                          </td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo "<p style='text-align: center;'>No users found.</p>";
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
