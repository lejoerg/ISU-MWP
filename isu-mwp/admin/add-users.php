<?php
$mysqli = new mysqli('localhost', 'root', '', 'water_polo');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
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
    <title>Create New Admin User</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
	<link id="main_css" href="../../styles/style41.css" rel="stylesheet">
    <link href="../../styles/easy-responsive-tabs.css" rel="stylesheet">
    <link href="../../styles/additional-styles.css" rel="stylesheet">
	<?php include 'admin-bar.php'; ?>
</head>
<style>
	    .content-container {
			width: 40%;
            margin: 20px auto; /* Center the container */
            padding: 20px;
            background-color: white;
            border: 1px solid #ccc; /* Light gray border */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
        }
		
</style>
<body style="background-color: #EEEEEE;">
    <div class="content-container">
        <h2 style="text-align: center;">Create New Admin User</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
		<button type="submit" name="submit" class="btn btn-primary" style="display: block; margin: 0 auto; text-align: center; background-color: #CC0000; ">
			Create User
		</button>
        </form>
    </div>
</body>
</html>

<?php
// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Firebase URL and Admin API Key
    $firebaseUrl = 'https://identitytoolkit.googleapis.com/v1/accounts:signUp?key=AIzaSyBZHa58oAwnVNRHZx-BLbV5SCxESJb5xqU';

    // Get user details from the form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $fullName = $_POST['full_name'];
    $createdAt = date('Y-m-d H:i:s'); // Current timestamp

    // Prepare data for Firebase user creation
    $userData = [
        'email' => $email,
        'password' => $password,
        'returnSecureToken' => true
    ];

    // Initialize cURL to call Firebase API
    $ch = curl_init($firebaseUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if($response === false) {
        echo "Error: " . curl_error($ch);
    } else {
        $userResponse = json_decode($response, true);

        // Check if user creation was successful
        if (isset($userResponse['idToken'])) {
            $firebaseUid = $userResponse['localId']; // Firebase User ID

            // Connect to your local database
            $mysqli = new mysqli('localhost', 'root', '', 'water_polo');
            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            // Insert user into your local 'users' table
            $stmt = $mysqli->prepare("INSERT INTO users (full_name, email, created_at, firebase_uid) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullName, $email, $createdAt, $firebaseUid);

            if ($stmt->execute()) {
				echo '<div class="return-message" style="text-align: center; margin: 50px auto; width: fit-content;">User created successfully in Firebase and local database.</div>';
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $mysqli->close();
        } else {
            echo "Error creating user: " . $userResponse['error']['message'];
        }
    }

    curl_close($ch);
}
?>
