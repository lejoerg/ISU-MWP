<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$databaseName = "water_polo";

$conn = new mysqli($servername, $username, $password, $databaseName);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the POST body
    $data = json_decode(file_get_contents('php://input'), true);

    // Verify if 'loggedin' and 'idToken' are present in the data
    if (isset($data['loggedin']) && $data['loggedin'] === true && isset($data['idToken'])) {
        $idToken = $data['idToken'];

        // Firebase project details
        $firebaseProjectId = "it-363"; // Update this to match your project ID

        // Verify the ID Token with Firebase
        $url = "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=AIzaSyBZHa58oAwnVNRHZx-BLbV5SCxESJb5xqU";
        
        // Set up the request to Firebase to verify the ID Token
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode(["idToken" => $idToken]),
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        // Decode the Firebase response
        $firebaseResponse = json_decode($response, true);

        // Check if the token verification was successful
        if (isset($firebaseResponse['users'][0]['email'])) {
            $userEmail = $firebaseResponse['users'][0]['email'];

            // Query the database for additional user details
            $query = "SELECT id, super_admin, active FROM users WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $userEmail);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Set session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['user_email'] = $userEmail;
                $_SESSION['firebase_id_token'] = $idToken;
                $_SESSION['id'] = $user['id'];
                $_SESSION['super_admin'] = $user['super_admin'];
                $_SESSION['active'] = $user['active'];

                // Return a success response
    echo json_encode([
        'status' => 'success',
        'super_admin' => $user['super_admin']
    ]);
            } else {
                // Return failure response if user not found
                echo json_encode(['status' => 'error', 'message' => 'User not found in the database']);
            }
        } else {
            // Return failure response if token is invalid
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID token']);
        }
    } else {
        // Return failure response if required data is missing
        echo json_encode(['status' => 'error', 'message' => 'Missing or invalid data']);
    }
} else {
    // Return an error message for non-POST requests
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// Close the database connection
$conn->close();
?>
