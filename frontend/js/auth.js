function initLogin() {
    $("#login-form").on("submit", function (e) {
        e.preventDefault();
        const email = $("#login-email").val();
        const password = $("#login-password").val();

        RestClient.post("auth/login", { email, password }, function (response) {
            localStorage.setItem("user_token", response.token);
            const user = Utils.parseJwt(response.token)?.user;
            $("#login-message").html(`<span class="text-success">✅ Welcome, ${user.name}</span>`);
            updateNavbar();

            // ✅ Ensure admin panel logic is refreshed
            if (typeof initAdminPanel === "function") {
                initAdminPanel();
            }

            setTimeout(() => window.location.hash = "#home", 1000);
        }, function () {
            $("#login-message").text("❌ Invalid email or password.");
        });
    });
}
function initSignup() {
    $("#signup-form").on("submit", function (e) {
        e.preventDefault();
        const name = $("#signup-name").val().trim();
        const email = $("#signup-email").val().trim().toLowerCase();
        const password = $("#signup-password").val();

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            $("#signup-message").text("❌ Please enter a valid email.");
            return;
        }
        if (password.length < 6) {
            $("#signup-message").text("❌ Password must be at least 6 characters.");
            return;
        }

        RestClient.post("auth/register", { name, email, password, role: "client" }, function () {
            $("#signup-message").text("✅ Signup successful. Redirecting...");
            setTimeout(() => window.location.hash = "#login", 1500);
        }, function (xhr) {
            const errorText = xhr?.responseJSON?.error || "❌ Signup failed. Try again.";
            $("#signup-message").text(errorText);
        });
    });
}

function logout() {
    localStorage.removeItem("user_token");
    updateNavbar();
    window.location.hash = "#home";
}
