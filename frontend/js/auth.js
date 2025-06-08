function initLogin() {
    $("#login-form").validate({
        rules: {
            loginEmail: { required: true, email: true },
            loginPassword: { required: true }
        },
        messages: {
            loginEmail: "Please enter a valid email",
            loginPassword: "Please enter your password"
        },
        submitHandler: function (form) {
            const email = $("#login-email").val().trim();
            const password = $("#login-password").val().trim();

            // Block the UI
            $.blockUI({ message: "<h3>Logging in...</h3>" });

            RestClient.post("auth/login", { email, password }, function (response) {
                $.unblockUI();
                localStorage.setItem("user_token", response.token);
                const user = Utils.parseJwt(response.token)?.user;
                $("#login-message").html(`<span class="text-success">✅ Welcome, ${user.name}</span>`);
                updateNavbar();

                if (typeof initAdminPanel === "function") initAdminPanel();

                window.location.hash = "#home";
                if (location.hash === "#home") {
                    loadPage("home");
                }
            }, function () {
                $.unblockUI();
                $("#login-message").text("❌ Invalid email or password.");
            });
        }
    });
}


function initSignup() {
    $("#signup-form").validate({
        rules: {
            signupName: { required: true },
            signupUsername: { required: true },
            signupEmail: { required: true, email: true },
            signupPhone: { required: true, minlength: 12 }, // e.g., +387601234567
            signupPassword: { required: true, minlength: 6 }
        },
        messages: {
            signupName: "Please enter your name",
            signupUsername: "Please enter a username",
            signupEmail: "Please enter a valid email address",
            signupPhone: "Please enter a valid phone number (e.g., +387601234567)",
            signupPassword: "Password must be at least 6 characters"
        },
        submitHandler: function (form) {
            // Block the UI
            $.blockUI({ message: "<h3>Signing up...</h3>" });

            // Prepare data
            const data = {
                name: $("#signup-name").val().trim(),
                username: $("#signup-username").val().trim(),
                email: $("#signup-email").val().trim().toLowerCase(),
                password: $("#signup-password").val(),
                phone_number: $("#signup-phone").val().trim(),
                role: "client"
            };

            // Submit data via AJAX
            RestClient.post("auth/register", data, function () {
                $.unblockUI();
                $("#signup-message").text("✅ Signup successful. Redirecting...");
                setTimeout(() => window.location.hash = "#login", 1500);
            }, function (xhr) {
                $.unblockUI();
                const errorText = xhr?.responseJSON?.error || "❌ Signup failed. Try again.";
                $("#signup-message").text(errorText);
            });
        }
    });
}


function logout() {
    localStorage.removeItem("user_token");
    updateNavbar();
    window.location.hash = "#home";
}
