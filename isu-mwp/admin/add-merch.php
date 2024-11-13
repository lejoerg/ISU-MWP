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
    <title>Add Merchandise</title>
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
    </style>
</head>
<body style="background-color: #EEEEEE;">
    <div class="content-container">
        <h2 style="text-align: center;">Add Merchandise</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="e.g., 25.00" required>
            </div>
            <div class="form-group">
                <label for="image">Upload Image</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary" style="display: block; margin: 0 auto; background-color: #CC0000;">
                Add Merchandise
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
    $description = $_POST['description'];
    $price = $_POST['price'];

    $conn = new mysqli($servername, $username, $password, $databaseName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Step 1: Handle the image upload
    $targetDir = "../../merch-img/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if upload is allowed and move file to server
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            echo "The file ". htmlspecialchars(basename($_FILES["image"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    } else {
        exit;
    }

    // Step 2: Get the highest merch_id from the merchandise table
    $query = "SELECT MAX(merch_id) AS max_merch_id FROM merchandise";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        $new_merch_id = $row['max_merch_id'] + 1;
    } else {
        $new_merch_id = 1;
    }

    // Step 3: Insert the new merchandise item with the new merch_id
    $stmt = $conn->prepare("INSERT INTO merchandise (merch_id, title, description, price, img_location) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issds", $new_merch_id, $title, $description, $price, $targetFile);

    if ($stmt->execute()) {
        echo '<div class="return-message" style="text-align: center; margin: 50px auto; width: fit-content;">Merchandise item added successfully.</div>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
