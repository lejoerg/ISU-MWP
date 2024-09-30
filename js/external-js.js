document.addEventListener('DOMContentLoaded', function () {
    const currentPath = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll('.o-nav-main__link');

    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
});
