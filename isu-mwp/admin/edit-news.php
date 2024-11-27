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
    <title>Edit News</title>
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
        <h2 style="text-align: center;">Manage News</h2>
        
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
            $news_id = $_POST['news_id'];
            $title = $_POST['title'];
            $message = $_POST['message'];
            $active = isset($_POST['active']) ? 1 : 0;

            $stmt = $conn->prepare("UPDATE news SET title = ?, message = ?, active = ? WHERE news_id = ?");
            $stmt->bind_param("ssii", $title, $message, $active, $news_id);

            if ($stmt->execute()) {
                echo '<div style="text-align: center; margin: 20px;">News updated successfully.</div>';
            } else {
                echo '<div style="text-align: center; color: red; margin: 20px;">Error: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }

        // Check if editing a news entry
        if (isset($_GET['edit'])) {
            $news_id = $_GET['edit'];

            // Fetch news entry to edit
            $sql = "SELECT * FROM news WHERE news_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $news_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $news = $result->fetch_assoc();

            if ($news) {
                ?>
                <div class="form-container">
                    <h3>Edit News</h3>
                    <form method="post" action="">
                        <input type="hidden" name="news_id" value="<?php echo $news['news_id']; ?>">
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>
                        
                        <label for="message">Message:</label>
                        <textarea name="message" id="message" rows="5" required><?php echo htmlspecialchars($news['message']); ?></textarea>

                        <label for="active">Active:</label>
                        <input type="checkbox" name="active" id="active" <?php echo $news['active'] == 1 ? 'checked' : ''; ?>>

                        <button type="submit" name="update" class="btn btn-danger">Update News</button>
                    </form>
                </div>
                <?php
            } else {
                echo '<div style="text-align: center; margin: 20px; color: red;">No such news entry found.</div>';
            }
        } else {
            // Fetch news entries
            $sql = "SELECT * FROM news ORDER BY date_published DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<tr>
                        <th>Title</th>
                        <th>Date Published</th>
                        <th>Message</th>
                        <th>Actions</th>
                      </tr>';

                while ($row = $result->fetch_assoc()) {
                    $formattedDate = date("F j, Y", strtotime($row['date_published']));

                    echo '<tr>';
                    echo '<td>' . $row['title'] . '</td>';
                    echo '<td>' . $formattedDate . '</td>';
                    echo '<td>' . $row['message'] . '</td>';
                    echo '<td>
                            <a href="?edit=' . $row['news_id'] . '" class="edit-btn">Edit</a>
                          </td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo "<p style='text-align: center;'>No news entries found.</p>";
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
