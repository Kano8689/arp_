// JS for Sidebar Toggle
document.getElementById("menuBtn").addEventListener("click", function () {
    document.getElementById("sidebar").classList.toggle("active");
});


// Highlight current page in sidebar
document.addEventListener("DOMContentLoaded", () => {
    const links = document.querySelectorAll(".sidebar a");
    const current = window.location.pathname.split("/").pop(); // current file
    links.forEach(link => {
        if (link.getAttribute("href") === current) {
            link.classList.add("active");
        }
    });
});

