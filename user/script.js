/* MTDH System/user/script.js */

var menuIcon = document.querySelector(".menu-icon");
var sidebar = document.querySelector(".sidebar");
var container = document.querySelector(".container");

menuIcon.onclick = function(){
    sidebar.classList.toggle("small-sidebar");
    container.classList.toggle("large-container");
}

// New code to close sidebar when a link is clicked
var sidebarLinks = document.querySelectorAll(".sidebar a");

sidebarLinks.forEach(link => {
    link.onclick = function(){
        sidebar.classList.add("small-sidebar"); // Close the sidebar
        container.classList.add("large-container"); // Expand the container
    };
});
