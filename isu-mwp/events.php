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
    <main style="padding: 30px;">
<section class="about-us">
    <h1 style="text-align: left; color: black;">Here's a look at our upcoming schedule!</h1>
</section>
<div class="event-container">
    <div class="event-section time">Time: 3:00 PM</div>
    <div class="event-section date">Date: September 30, 2024</div>
    <div class="event-section location">Location: ISU Sports Complex</div>
    <div class="event-section picture">
        <img src="event-image.jpg" alt="Event Image">
    </div>
    <div class="event-section description">Description: Join us for an exciting day of water polo!</div>
</div>
    </main>
<?php include 'footer.html'; ?>
<script src="../js/external-js.js></script>
</body>	
</html>

Link db to internal php variables
Have # of records where date - currentDate > 0
loop that # of times, printing event container
sort by date
when events are clicked, have a new layout