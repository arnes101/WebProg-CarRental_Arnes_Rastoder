function loadPage(page) {
    fetch(`views/${page}.html`)
        .then(response => {
            if (!response.ok) throw new Error(`Page not found: ${page}`);
            return response.text();
        })
        .then(html => {
            document.getElementById("content").innerHTML = html;

            document.body.className = (page === "login" || page === "signup") ? "auth-page" : "main-page";

            // Call init functions if category page is loaded
            if (page.toLowerCase() === "catagori") {
                if (typeof initCategoryFilter === "function") initCategoryFilter();
                if (typeof openPopup === "function") console.log("Popup function ready.");
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
