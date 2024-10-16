<html>
<head>
    <meta charset="utf-8">
    <title>Illinois State University - Men's Water Polo RSO</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <link href="../styles/easy-responsive-tabs.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.html'; ?>
    <main>
        <section class="about-us">
            <h1>Admin Settings</h1>
            <form action="" method="POST"> 
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br><br>
        
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>
        
                <button type="submit">Login</button>
            </form>

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

                // Check if the user exists and if the password matches with username
                if ($admin) {
                    if ($inputPassword === $admin['password']) { 
                        
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $admin['username'];
                        $_SESSION['firstname'] = $admin['firstname'];
                        $_SESSION['lastname'] = $admin['lastname'];

                        // User is taken to admin portal
                        header("Location: admin_portal.php");
                        exit;
                    } else {
                        // Password does not match
                        echo "<p>Invalid username or password!</p>";
                    }
                } else {
                    // Displays error message 
                    echo "<p>Invalid username or password!</p>";
                }
            }


            $conn->close();
            ?>
        </section>
    </main>
<script src="../js/external-js.js></script>
</body>	
</html>
