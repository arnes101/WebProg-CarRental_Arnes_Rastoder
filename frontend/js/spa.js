function loadPage(page) {
    fetch(`../frontend/views/${page}.html`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Page not found: ${page}`);
            }
            return response.text();
        })
        .then(html => {
            document.getElementById("content").innerHTML = html;

            // Manage body classes based on page
            document.body.className = ""; // Reset previous classes
            if (page === "login" || page === "signup") {
                document.body.classList.add("auth-page");
            } else {
                document.body.classList.add("main-page");
            }
        })
        .catch(error => {
            console.error("Error loading page:", error);
            document.getElementById("content").innerHTML = "<h2>Page not found.</h2>";
        });
}

// Load correct page on initial load
window.onload = function () {
    let page = window.location.hash.substring(1);
    if (!page) {
        page = "home";
    }
    loadPage(page);
};

// Optional: Load correct view when hash changes
window.onhashchange = function () {
    let page = window.location.hash.substring(1);
    if (!page) {
        page = "home";
    }
    loadPage(page);
};
