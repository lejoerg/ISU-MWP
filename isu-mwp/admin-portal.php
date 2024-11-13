<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not authenticated
    header("Location: signin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Illinois State University - Men's Water Polo RSO</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <link href="../styles/easy-responsive-tabs.css" rel="stylesheet">
    <link href="../styles/additional-styles.css" rel="stylesheet">
    <?php include 'navbar.html'; ?>
    <style>
        .admin-controls-container {
            display: flex;
            justify-content: space-around;
            padding-top: 15px;
			padding-bottom: 15px;
        }

        .admin-control-box {
            width: 20%;
            height: 600px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            transition: transform 0.3s;
        }
		
        .admin-control-box a {
            text-decoration: none;
            color: #CC00000;
            font-size: 18px;
            font-weight: bold;
        }

        .admin-control-box h3 {
            margin-top: 20px;
            font-size: 22px;
            color: #CC0000;
        }
		
		.spaced-link {
			display: inline-block;
			margin-bottom: 30px;
		}
		
		.spaced-link:link,
		.spaced-link:visited,
		.spaced-link:active {
			color: #CC0000;
		}
		
		.spaced-link:hover {
			color: #800000;
			text-decoration: underline;
		}
		
		.icon-container {
			padding: 30px;
			text-align: center;
			margin-top: auto;
		}
		
		.icon-style {
			width: 75px;
			height: 75px;
			font-size: 24px;
			color: #333;
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 0 auto; /* Center horizontally if it's a block element */
		}
		
	.logout-button {
		float: right;
		background-color: #CC0000;
		color: white;
		font-size: 18px;
		padding: 10px 20px;
		border: none;
		border-radius: 5px;
		cursor: pointer;
		text-decoration: none;
		margin-top: 15px;
	}

	.logout-button:link,
	.logout-button:visited,
	.logout-button:hover,
	.logout-button:active {
		color: white;
	}


        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }
    </style>
</head>
<body>
    <!-- Admin Portal Content -->
    <div class="container">
		<div class="header-container">
			<h1 style="padding-top: 10px; padding-left: 30px;">Welcome to the Admin Portal</h1>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
        <div class="admin-controls-container">
            <!-- Container 1 -->
            <div class="admin-control-box">
                <h2>Events</h2>
                <p style="padding-bottom: 40px;"><i>Manage events for the team.</i></p>
					<a href="events.php" class="spaced-link">Go to Events</a><br>
					<a href="admin/add-events.php" class="spaced-link">Add Event</a><br>
					<a href="admin/delete-events.php" class="spaced-link">Delete Event</a><br>
					<a href="" class="spaced-link"></a><br>
            <div class="icon-container">
                <img class="icon-style" src="../img/calendar3.png"></img>
            </div>
            </div>
            <!-- Container 2 -->
            <div class="admin-control-box">
                <h2>Roster</h2>
                <p style="padding-bottom: 40px;"><i>Update and manage team roster.</i></p>
                <a href="roster.php" class="spaced-link">Go to Roster</a><br>
				<a href="admin/add-to-roster.php" class="spaced-link">Add to Roster</a><br>
				<a href="admin/delete-from-roster.php" class="spaced-link">Delete from Roster</a><br>
				<a href="" class="spaced-link"></a><br>
			<div class="icon-container">
                <img class="icon-style" src="../img/group.png"></img>
            </div>
            </div>
            <!-- Container 3 -->
            <div class="admin-control-box">
                <h2>Merch</h2>
                <p style="padding-bottom: 40px;"><i>Manage merchandise listings.</i></p>
                <a href="merchandise.php" class="spaced-link">Go to Merch</a><br>
				<a href="admin/add-merch.php" class="spaced-link">Add Merch</a><br>
				<a href="admin/delete-merch.php" class="spaced-link">Delete Merch</a><br>
				<a href="" class="spaced-link"></a><br>
			<div class="icon-container">
                <img class="icon-style" src="../img/grocery-store.png"></img>
            </div>
            </div>
            <!-- Container 4 -->
            <div class="admin-control-box">
                <h2>Admin Users</h2>
                <p style="padding-bottom: 40px;"><i>Manage administrator users.</i></p>
                <a href="admin/users.php" class="spaced-link">View Admin Users</a><br>
				<a href="admin/add-users.php" class="spaced-link">Add Admin Users</a><br>
				<a href="admin/delete-users.php" class="spaced-link">Delete Admin Users</a><br>
				<a href="" class="spaced-link"> </a><br>
			<div class="icon-container">
                <img class="icon-style" src="../img/group.png"></img>
            </div>
			</div>
        </div>
    </div>
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
