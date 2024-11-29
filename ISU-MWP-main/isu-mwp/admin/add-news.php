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
    <title>Add News</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link id="main_css" href="../../styles/style41.css" rel="stylesheet">
    <link href="../../styles/easy-responsive-tabs.css" rel="stylesheet">
    <link href="../../styles/additional-styles.css" rel="stylesheet">
    <?php include 'admin-bar.php'; ?>
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
    </style>
</head>
<body style="background-color: #EEEEEE;">
    <div class="content-container">
        <h2 style="text-align: center;">Add News</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" style="height: 200px;" id="message" name="message" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" name="submit" class="btn btn-primary" style="display: block; margin: 0 auto; background-color: #CC0000;">
                Add News
            </button>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['submit'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "water_polo";

    $title = $_POST['title'];
    $date = $_POST['date'];
    $message = $_POST['message'];
    $active = 1; // Default active value

    // Handle file upload
    $uploadDir = "../../uploads/news_images/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Store the relative path for database storage
            $imagePath = str_replace("../../", "", $imagePath);
        } else {
            echo "Failed to upload image.";
        }
    }

    $conn = new mysqli($servername, $username, $password, $databaseName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO news (title, date_published, message, active, image_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $title, $date, $message, $active, $imagePath);

    if ($stmt->execute()) {
        echo '<div class="return-message" style="text-align: center; margin: 50px auto; width: fit-content;">News added successfully.</div>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
