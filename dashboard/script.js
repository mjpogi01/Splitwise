// script.js

// Optional: Close the menu when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.querySelector('.menu');
    const toggle = document.getElementById('menu-toggle');
    const isClickInside = menu.contains(event.target) || document.querySelector('.menu-icon').contains(event.target);

    if (!isClickInside && toggle.checked) {
        toggle.checked = false;
    }
});
