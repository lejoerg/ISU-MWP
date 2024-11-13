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
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js";
    import { getAuth, signInWithEmailAndPassword, getIdToken } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-auth.js";

    const firebaseConfig = {
        apiKey: "AIzaSyBZHa58oAwnVNRHZx-BLbV5SCxESJb5xqU",
        authDomain: "it-363.firebaseapp.com",
        projectId: "it-363",
        storageBucket: "it-363.firebasestorage.app",
        messagingSenderId: "783033560797",
        appId: "1:783033560797:web:8d4e0f091f307ddeafb534",
        measurementId: "G-HNJJQ5TP0F"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const auth = getAuth();

    async function signIn() {
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        try {
            const userCredential = await signInWithEmailAndPassword(auth, email, password);
            console.log(userCredential);

            // Retrieve the Firebase ID Token
            const idToken = await getIdToken(userCredential.user);
            console.log("ID Token:", idToken);

            // Send the ID token and session data to authenticate.php
            const response = await fetch("authenticate.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    loggedin: true,
                    idToken: idToken // Send ID Token for future Firebase operations
                })
            });

            const data = await response.json();
            console.log(data);

            if (data.status === "success") {
                window.location.href = "admin-portal.php";
            } else {
                alert("Failed to set session: " + data.message);
            }
        } catch (error) {
            alert("We couldn't verify your credentials. Please make sure your username and password are correct, and try again.");
        }
    }

    window.signIn = signIn;
	
	firebase.auth().currentUser.getIdToken()
    .then(function(idToken) {
        // Send the ID token to your backend
        fetch('authenticate.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
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


    // Add the 'DOMContentLoaded' event listener
    document.addEventListener('DOMContentLoaded', function () {
        const currentPath = window.location.pathname.split("/").pop();
        const navLinks = document.querySelectorAll('.o-nav-main__link');

        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    });
</script>
</body>
</html>
