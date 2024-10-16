<html>
<head>
    <meta charset="utf-8">
    <title>Illinois State University - Men's Water Polo RSO</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <link href="../styles/easy-responsive-tabs.css" rel="stylesheet">

    <style>
        .about-us {
            text-align: center; 
            margin-top: 50px; 
        }
        table {
            margin: 50px auto; 
            border-collapse: collapse; 
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: center;
        }
        img {
            border-radius: 50%; 
            width: 100px;
            height: 100px;
        }
    </style>

</head>

<body>
<?php include 'navbar.html'; ?>
    <main>
        <section class="about-us">
            <h1>Meet the Team!</h1>

            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $databaseName = "water_polo";

            // Creates connection
            $conn = new mysqli($servername, $username, $password, $databaseName);
            // Checks connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetches roster data
            $sql = "SELECT first_name, last_name, position, year, image FROM roster";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Starts table
                echo "<table>";
                echo "<tr><th>Image</th><th>First Name</th><th>Last Name</th><th>Position</th><th>Year</th></tr>";

                // Outputs data of each row
                while($row = $result->fetch_assoc()) {
                    // Uses a default image if NULL that I added to img folder
                    $player_image = $row["image"] ? $row["image"] : "default-player.png"; 

                    echo "<tr>";
                    echo "<td><img src='../img/" . $player_image . "' alt='Player Image'></td>";
                    echo "<td>" . $row["first_name"] . "</td>";
                    echo "<td>" . $row["last_name"] . "</td>";
                    echo "<td>" . $row["position"] . "</td>";
                    echo "<td>" . $row["year"] . "</td>";
                    echo "</tr>";
                }

                // Ends table
                echo "</table>";
            } else {
                echo "<p>No players found.</p>";
            }

            // Closes connection
            $conn->close();
            ?>
        </section>
    </main>
<?php include 'footer.html'; ?>
<script src="../js/external-js.js"></script>
</body>
</html>

-- create test table / data
CREATE TABLE roster (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    position VARCHAR(50),
    year VARCHAR(10),
    image VARCHAR(100) DEFAULT 'default-player.jpg'
);

--
INSERT INTO roster (first_name, last_name, position, year, image)
VALUES 
('John', 'Doe', 'Goalkeeper', 'Senior', 'NULL'),
('Jane', 'Smith', 'Defender', 'Junior', NULL),
('Mike', 'Johnson', 'Attacker', 'Sophomore', 'NULL');
