<html>
<head>
    <meta charset="utf-8">
    <title>Illinois State University - Men's Water Polo RSO</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <link href="../styles/easy-responsive-tabs.css" rel="stylesheet">
	<link href="../styles/additional-styles.css" rel="stylesheet">
	<?php include 'navbar.html'; ?>
	    <style>
.container {
	width: 100%; /* Main container width */
	padding: 40px;
        }
.section {
	margin-bottom: 30px; /* Space between sections */
	display: flex; /* Use flexbox to align items */
	align-items: center; /* Center items vertically */
	justify-content: space-between; /* Space between text and image */
        }
.section img {
	width: 80%; /* Adjust width of the image */
}
.text-container {
	width: 100%; /* Width of the text container */
}
.gallery-container {
    max-width: 1000px; /* Adjust width as needed */
    margin: 0 auto; /* Center the container */
    position: relative; /* Enable absolute positioning for buttons */
}

.gallery {
    overflow: hidden; /* Hide overflow to only show one image */
}

.gallery-item {
    display: none; /* Hide all gallery items by default */
    justify-content: center; /* Center the image */
}

.gallery-item.active {
    display: flex; /* Show only the active image */
}

.gallery-item img {
    width: 100%; /* Make the image take the full width */
    height: auto; /* Maintain aspect ratio */
}

button,
.prev,
.next {
    position: absolute; /* Position buttons on top of the gallery */
    top: 50%;
    transform: translateY(-50%); /* Center vertically */
    background-color: rgba(0, 0, 0, 0.5); /* Dark semi-transparent background */
    border: none; /* Remove border */
    padding: 12px 16px; /* Padding around the button */
    cursor: pointer; /* Pointer cursor on hover */
    font-size: 24px; /* Increase font size for better visibility */
    color: white; /* White text color */
    border-radius: 5px; /* Rounded corners */
    transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transitions */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); /* Subtle shadow for depth */
}

.prev {
    left: 10px; /* Position for previous button */
}

.next {
    right: 10px; /* Position for next button */
}

/* Hover effect for buttons */
button:hover,
.prev:hover,
.next:hover {
    background-color: rgba(0, 0, 0, 0.8); /* Darker background on hover */
}

    </style>
</head>
<body>
    <main>
	<div class="image-container">
  <img src="../img/pool-cropped.jpg" alt="Background Image" class="background-image">
  <div class="overlay">
    <h1 style="color: white; text-align: left; padding: 0px 0px 30px 30px; font-size: 3.5em;">Welcome to the Team!</h1>
  </div>
	</div>
	
        <div class="container">
            <!-- About Section -->
            <div class="section">
                <div class="text-container">
                    <h2>About the ISU Water Polo Club</h2>
                    <p>The <strong>Illinois State University Men's Water Polo Club</strong> has a long and rich history of success and camaraderie. Founded in </strong>(find year)</strong>, the club has grown from a small group of enthusiasts to a <strong>competitive</strong> team. Over the years, we’ve competed in regional tournaments, built a strong community of players, and represented <strong>ISU</strong> with <strong>pride</strong>.</p>
                    <p>Our club welcomes players of all skill levels, from beginners to seasoned athletes. Whether you’re looking to learn how to play water polo, stay active, or compete at a high level, the ISU Men's Water Polo Club has something for <strong>everyone</strong>.</p>
					<p>We encourage you to explore our <strong>community</strong> further! Check out our photo gallery below to relive our exciting moments, learn the rules of the game to see how you can jump in, and don’t hesitate to reach out if you have any questions or want to join the team. Your journey with the ISU Men's Water Polo Club starts <strong>here</strong>!</p>
					<p><a href="contact-us.php" class="contact-button">Contact Us</a></p>
                </div>
                <div class="image-container">
                    <img src="../img/team-photo.jpg" alt="ISU Water Polo Club History" />
                </div>
            </div>
		    </div>
<div class="divider" style="width: 90%; height: 1px; background-color: black; margin: 30px auto;"></div>

			
		<!-- Gallery Section -->
			<h2>Photo Gallery</h2>
			<div class="gallery-container">
				<div class="gallery">
					<div class="gallery-item">
						<img src="../img/team2.jpg" alt="Gallery Image 2" />
					</div>
					<div class="gallery-item">
						<img src="../img/team3.jpg" alt="Gallery Image 3" />
					</div>
					<div class="gallery-item">
						<img src="../img/team-photo.jpg" alt="Gallery Image 1" />
					</div>
					<!-- Add more images as needed -->
				</div>
				<div>
					<button class="prev" onclick="changeSlide(-1)">&#10094;</button> <!-- Previous Button -->
					<button class="next" onclick="changeSlide(1)">&#10095;</button> <!-- Next Button -->
				</div>
			</div>

<div class="divider" style="width: 90%; height: 1px; background-color: black; margin: 50px auto;"></div>


<div class="container">
    <!-- How to Play Section -->
    <div class="section" style="display: flex; align-items: center; margin-bottom: 30px;">
        <div class="image-container" style="flex: 0 0 45%; margin-left: 20px;">
            <iframe width="90%" height="500" src="https://www.youtube.com/embed/g63LpPuDaxE?si=xKdNzsrO6-leqZOt" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
		<div class="text-container" style="flex: 0 0 5%;"></div>
        <div class="text-container" style="flex: 0 0 45%;">
            <h2>How to Play Water Polo</h2>
<p><strong>Water polo</strong> is an exciting team sport that blends swimming, soccer, and basketball. Understanding the rules is key to enhancing your gameplay experience. Each team has seven players plus a goalkeeper, aiming to 
	score by throwing the ball into the opposing team's net. Players can pass, swim, and shoot while treading water. The game is played in four quarters, with teams employing strategies akin to those in other sports, including offensive and defensive formations. 
	For a <strong>deeper dive</strong> into the rules, follow the <strong>video</strong> on the left!</p>
	<p>Or <a href="https://collegiatewaterpolo.org/wp-content/uploads/2022/08/NCAARulesBook_20222024-1.pdf" target="_blank">click here</a> for the Official Rulebook.</p>

		</div>
		<div class="text-container" style="flex: 0 0 5%;"></div>
    </div>
</div>

<div class="divider" style="width: 90%; height: 1px; background-color: black; margin: 30px auto;"></div>

<div class="container">
    <!-- Details Section -->
    <div class="section">
	    <div class="text-container" style="width: 5%;"></div>
        <div class="text-container" style="width: 50%;">
            <h2>When and Where?</h2>
			<p>Our practice sessions are held at <strong>Horton Field House</strong>, located at <strong>180 N Adelaide St, Normal, IL 61761</strong>.</p>
			<p>We practice on <strong>Monday</strong> and <strong>Wednesday</strong> evenings at <strong>9:00 PM</strong>. Come prepared to work hard, have fun, and be part of our growing community!</p>
			<p>During the fall semester, our season kicks off, and you can expect to travel to different universities and pools to compete against other competitive teams. Some of the frequent places we go include <strong>Notre Dame</strong>, <strong>Washington University</strong>, and the <strong>University of Illinois in Urbana-Champaign</strong>.</p>
			<p>In the spring semester, we will participate in <strong>2-4 tournaments</strong>, and everyone is welcome to join!</p>
			<p>For the most up to date event schedule, click the following link: <a href="#">Full Event Schedule</a>.</p>

        </div>
			    <div class="text-container" style="width: 5%;"></div>
        <div class="image-container" style="width: 40%;">
			<div id="map" style="width: 100%; height: 400px;"></div>
        </div>
		<div class="text-container" style="width: 5%;"></div>
       </div>
</div>

<script>
    // Google Maps API script
    function initMap() {
        const location = { lat: 40.515280, lng: -88.996120 }; // Coordinates for Horton Field House
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location,
			mapTypeId: 'satellite'
        });
        const marker = new google.maps.Marker({
            position: location,
            map: map,
        });
    }
</script>

<!-- Include the Google Maps API with your API key -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfqmDcBeywXjukaO0sxml7B-RcTSnpqwI&callback=initMap" async defer></script>
	
<?php
/*
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
	while($row = $result->fetch_assoc())
	{
		echo "<tr><td>".$row["first_name"]."</td></tr>";
	}
	echo "</table>";
}
*/
?>	
<?php include 'footer.html'; ?>
<script src="../js/external-js.js"></script>
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


let currentIndex = 0; // Index of the current image
const slides = document.querySelectorAll('.gallery-item');

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.remove('active'); // Hide all slides
        if (i === index) {
            slide.classList.add('active'); // Show the current slide
        }
    });
}

function changeSlide(direction) {
    currentIndex += direction;
    if (currentIndex < 0) {
        currentIndex = slides.length - 1; // Go to last slide if at first
    } else if (currentIndex >= slides.length) {
        currentIndex = 0; // Go to first slide if at last
    }
    showSlide(currentIndex); // Show the new slide
}

// Initialize the first slide
showSlide(currentIndex);
</script>
</body>
</html>
