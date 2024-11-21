<html>
<head>
    <meta charset="utf-8">
    <title>Illinois State University - Men's Water Polo RSO</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <link href="../styles/easy-responsive-tabs.css" rel="stylesheet">
	<link href="../styles/additional-styles.css" rel="stylesheet">
	<?php include 'navbar.html'; ?>
</head>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        .dropdown-container {
            margin-bottom: 20px;
            text-align: center;
        }
        .dropdown-container select {
            padding: 8px;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #CC0000;
			color: white;
        }
    </style>
<body>

<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$databaseName = "water_polo";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $databaseName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distinct active years for the dropdown
$yearsResult = $conn->query("SELECT DISTINCT active_year FROM roster ORDER BY active_year DESC");

// Get current year
$currentYear = date("Y");

// Initialize selectedYear with the current year
$selectedYear = $currentYear;

// Check if a year is submitted; if so, set selectedYear to that year
if (isset($_POST['active_year'])) {
    $selectedYear = $_POST['active_year'];
}

// Fetch roster data based on selected active year
$stmt = $conn->prepare("SELECT * FROM roster WHERE active_year = ? AND active = 1");
$stmt->bind_param("i", $selectedYear);
$stmt->execute();
$rosterResult = $stmt->get_result();
?>
    <section class="about-us">
        <h1 style="font-size: 3em; padding: 10px 30px; text-align: left; color: black;">Meet the Team!</h1>
    </section>
<div class="container">
	<div class="dropdown-container">
		<form method="POST" action="">
			<div style="display: flex; align-items: center; justify-content: left; white-space: nowrap;">
				<label for="active_year" style="margin-right: 10px; font-size: 1em;"><strong>Select Year:</strong></label>
				<select name="active_year" id="active_year" onchange="this.form.submit()" style="width: 100px;">
					<?php while($year = $yearsResult->fetch_assoc()): ?>
						<option value="<?= $year['active_year'] ?>" <?= $year['active_year'] == $selectedYear ? 'selected' : '' ?>>
							<?= $year['active_year'] ?>
						</option>
					<?php endwhile; ?>
				</select>
			</div>
		</form>
	</div>

    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Year</th>
                <th>Hometown</th>
                <th>Position</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $rosterResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['first_name']) ?></td>
                    <td><?= htmlspecialchars($row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['year']) ?></td>
                    <td><?= htmlspecialchars($row['hometown_city']) . ", " . htmlspecialchars($row['hometown_state']) . ", " . htmlspecialchars($row['hometown_country']) ?></td>
                    <td><?= htmlspecialchars($row['position']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
	
	<div style="text-align: center; padding: 40px 0;">
    <?php
		// Build the image filename based on the selected year
		$imageFilename = "../img/" . $selectedYear . "-active.jpg";
	
		// Check if the image file exists
		if (file_exists($imageFilename)) {
			echo '<img src="' . htmlspecialchars($imageFilename) . '" alt="Team Image" style="width: 80%;">';
		} else {
			// If the image does not exist, display nothing
			echo '';
		}
		?>
	</div>
	
</div>

<?php
// Close the database connection
$conn->close();
?>
<script> 
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
<?php include 'footer.html'; ?>
</body>	
</html>
