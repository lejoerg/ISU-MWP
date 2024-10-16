<?php
session_start();

$servername = "localhost";
$username = "root";  
$password = "";      
$databaseName = "waterpoloclub"; 

$conn = new mysqli($servername, $username, $password, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $inputUsername); 
    $stmt->execute();

    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && $inputPassword === $admin['password']) { 
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $admin['username'];
        $_SESSION['firstname'] = $admin['firstname'];
        $_SESSION['lastname'] = $admin['lastname'];

        header("Location: admin_portal.php");
        exit;
    } else {
        echo "<p>Invalid username or password!</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Login</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <style>
        .center-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .content-box {
            width: 80%;
            max-width: 400px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php include 'navbar.html'; ?>

<div class="center-container">
    <div class="content-box">
        <h1>Admin Login</h1>
        <form action="login.php" method="POST"> 
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">Login</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
        ?>
    </div>
</div>

<script src="../js/external-js.js"></script>
</body>
</html>

