// =====================================================================
// script.js  -  The only JavaScript we need: the hamburger menu
// =====================================================================

// Find the hamburger button and the menu list on the page
var burger = document.getElementById('burger');
var links  = document.getElementById('links');

// Only run if they exist (the admin page has no hamburger)
if (burger && links) {

    // When the hamburger is clicked...
    burger.addEventListener('click', function () {
        burger.classList.toggle('open');   // turn the 3 lines into an X (and back)
        links.classList.toggle('open');    // show or hide the menu
    });

    // When any menu link is clicked, close the menu again
    links.addEventListener('click', function () {
        burger.classList.remove('open');
        links.classList.remove('open');
    });
}


