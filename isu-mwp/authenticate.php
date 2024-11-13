<?php
session_start();

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

            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['user_email'] = $userEmail;
			$_SESSION['firebase_id_token'] = $idToken;

            // Return a success response
            echo json_encode(['status' => 'success', 'message' => 'Session set successfully']);
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
?>
