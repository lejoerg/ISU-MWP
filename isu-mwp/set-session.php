<?php
session_start();

// Get the ID token from the request
$data = json_decode(file_get_contents('php://input'), true);
$idToken = $data['idToken'] ?? '';

if ($idToken) {
    // Use Firebase Admin SDK to verify the token
    require 'vendor/autoload.php'; // Ensure you have installed firebase/php-jwt

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    // Replace with your Firebase project's secret key
    $firebase_secret = "YOUR_FIREBASE_SECRET_KEY";

    try {
        $decoded = JWT::decode($idToken, new Key($firebase_secret, 'HS256'));

        // Set session if token is valid
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $decoded->uid; // Set other user info as needed

        http_response_code(200);
        echo json_encode(['message' => 'Session set successfully.']);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid token.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Token not provided.']);
}
?>
