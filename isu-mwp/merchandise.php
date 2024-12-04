<?php
$servername = "localhost";
$username = "root";
$password = "";
$databaseName = "water_polo";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli($servername, $username, $password, $databaseName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get merchandise products, including the price
$sql = "SELECT title, description, img_location, price FROM merchandise WHERE active = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Illinois State University - Men's Water Polo RSO</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <link href="../styles/easy-responsive-tabs.css" rel="stylesheet">
    <style>
        .products-container {
            width: 78%;
            margin: 0 auto;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .image-container {
            height: 300px;
            width: 300px;
            overflow: hidden;
            position: relative;
            margin: 0 auto;
        }
        .product {
            width: 26%;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            overflow: visible;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product img {
            width: 100%;
            height: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: transform 0.3s ease;
            z-index: 1;
        }

        .product:hover img {
            transform: scale(1.02) translate(-50%, -50%);
            z-index: 10;
        }

        .title {
            font-size: 1.2em;
            margin-top: 10px;
        }
        .description {
            font-size: 0.9em;
            color: #555;
        }
        .price {
            color: #CC0000;
            font-weight: bold;
        }
        .no-image {
            font-style: italic;
            color: #888;
            margin-top: 10px;
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
		.o-nav-main__link.active {
			background-color: #900; /* Change to desired color */
			color: white; /* Text color change */
		}
    </style>
</head>
<body>
<?php include 'navbar.html'; ?>
    <main>
        <section class="about-us">
            <h1 style="font-size: 3em; padding: 10px 30px; text-align: left; color: black;">Shop ISU Men's Water Polo</h1>
        </section>

        <section class="products-container">
            <div class="products">
<?php
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Remove the first "../" from the image path
        $imgPath = preg_replace('/^\.\.\//', '', htmlspecialchars($row["img_location"]));

        echo '<div class="product">';

        // Image container
        echo '<div class="image-container">';
        // Check if the image file exists
        if (file_exists($imgPath)) {
            echo '<img src="' . $imgPath . '" alt="' . htmlspecialchars($row["title"]) . '">';
        } else {
            echo '<div class="no-image">Picture not available</div>';
        }
        echo '</div>'; // Close image container

        // Title, description, and price
        echo '<div class="title" style="font-weight: bold;">' . htmlspecialchars($row["title"]) . '</div>';
        echo '<div class="description" style="font-style: italic;">' . htmlspecialchars($row["description"]) . '</div>';

        // Check if price exists and display it
        if (isset($row["price"]) && $row["price"] !== null) {
            echo '<div class="price">$' . htmlspecialchars($row["price"]) . '</div>';
        } else {
            echo '<div class="price">Price not available</div>';
        }

        echo '</div>'; // Close product
    }
} else {
    echo "<p>No products found.</p>";
}
$conn->close();
?>
            </div>
        </section>
    </main>
<?php include 'footer.html'; ?>
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
</body>	
</html>
