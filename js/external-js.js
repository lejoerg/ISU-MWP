document.addEventListener('DOMContentLoaded', function () {
    const currentPath = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll('.o-nav-main__link');

    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
});


let slideIndex = 0; // Start at the first slide
let slides = document.getElementsByClassName("gallery-slide");

function showSlides() {
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none"; // Hide all slides
    }
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1; // Reset to the first slide
    }
    slides[slideIndex - 1].style.display = "block"; // Show the current slide
    setTimeout(showSlides, 3000); // Change slide every 3 seconds
}

function changeSlide(n) {
    slideIndex += n; // Change the slide index
    if (slideIndex > slides.length) {
        slideIndex = 1; // Reset to the first slide if exceeded
    } else if (slideIndex < 1) {
        slideIndex = slides.length; // Go to the last slide if below 1
    }
    showSlidesManually(); // Update the displayed slide
}

function showSlidesManually() {
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none"; // Hide all slides
    }
    slides[slideIndex - 1].style.display = "block"; // Show the selected slide
}

// Initialize the slideshow
showSlides();
