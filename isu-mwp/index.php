<html>
<head>
    <meta charset="utf-8">
    <title>Illinois State University - Men's Water Polo RSO</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <link href="../styles/easy-responsive-tabs.css" rel="stylesheet">
	<link href="../styles/additional-styles.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.html'; ?>
    <main>
</head>
<body>
<div class="image-container">
  <img src="../img/pool-cropped.jpg" alt="Background Image" class="background-image">
  <div class="overlay">
    <h1 style="color: white; text-align: left; padding: 0px 0px 30px 30px; font-size: 3.5em;">Welcome to the Team!</h1>

  </div>
</div>
    </main>
	
<?php
$servername = "localhost";
$username = "root";
$password = "";
$databaseName = "water_polo";

// Create Connection
$conn = new mysqli($servername, $username, $password, $databaseName);

// Check Connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}
echo "Connected successfully";

$sql = "SELECT * FROM roster";
$result = $conn->query($sql);

if ($result->num_rows > 0)
{
	echo "<table>";
	while($row= $result->fetch_assoc())
	{
		echo "<tr><td>".$row["first_name"]."</td></tr>";
	}
	echo "</table>";
}
?>	
<?php include 'footer.html'; ?>
<script src="../js/external-js.js></script>
</body>
</html>

<br>
create footer
<br>
<br>
Information about ISU club
history
google maps api of practice location
how to play water polo
