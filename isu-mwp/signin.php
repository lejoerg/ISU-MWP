<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Illinois State University - Men's Water Polo RSO</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <link href="../styles/easy-responsive-tabs.css" rel="stylesheet">
    <link href="../styles/additional-styles.css" rel="stylesheet">
    <?php include 'navbar.html'; ?>
</head>
<body>
    <div class="container" style="padding-top: 60px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-body">
                        <h3 class="text-center">Admin Sign In</h3>
                        <form id="signInForm">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
                            </div>
                            <button type="button" class="btn btn-block" onclick="signIn()" style="background-color: #CC0000; color: white;">
                                Sign In
                            </button>
                            <p class="text-center mt-3">
                                Contact us to request an Admin account.
                            </p>
                        </form>
                        <!-- Display error message if set -->
                        <p id="errorMessage" class="text-danger text-center" style="display: none;"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js";
    import { getAuth, signInWithEmailAndPassword, getIdToken } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-auth.js";

    const firebaseConfig = {
        INSERT_FIREBASE_CONFIG_HERE
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const auth = getAuth();

    async function signIn() {
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        // Reset error message
        document.getElementById("errorMessage").style.display = "none";

        try {
            // Firebase sign-in attempt
            const userCredential = await signInWithEmailAndPassword(auth, email, password);
            console.log(userCredential);

            // Retrieve the Firebase ID Token
            const idToken = await getIdToken(userCredential.user);
            console.log("ID Token:", idToken);

            // Send the ID token to authenticate.php
            const response = await fetch("authenticate.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    loggedin: true,
                    idToken: idToken // Send ID Token for session management
                })
            });

            const data = await response.json();
            console.log(data);

            if (data.status === "success") {
                // Set session information (make sure this is properly handled in authenticate.php)
                // Check if the user is a super-admin (using the super_admin value from the response)
                if (data.super_admin === 1) {
                    window.location.href = "super-portal.php"; // Redirect to super-admin portal
                } else {
                    window.location.href = "admin-portal.php"; // Redirect to regular admin portal
                }
            } else {
                // Display error message if login failed
                document.getElementById("errorMessage").textContent = "Failed to authenticate user: " + data.message;
                document.getElementById("errorMessage").style.display = "block";
            }
        } catch (error) {
            // Handle Firebase authentication errors
            console.error(error);
            document.getElementById("errorMessage").textContent = "We couldn't verify your credentials. Please make sure your username and password are correct, and try again.";
            document.getElementById("errorMessage").style.display = "block";
        }
    }

    window.signIn = signIn;

    // This code is for debugging purposes (getting Firebase ID token from the current user)
    firebase.auth().currentUser?.getIdToken()
    .then(function(idToken) {
        // Send the ID token to your backend for further processing
        fetch('authenticate.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ idToken: idToken })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Handle success or error
        });
    })
    .catch(function(error) {
        console.log('Error getting ID token:', error);
    });

</script>
</body>
</html>
