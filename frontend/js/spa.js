function loadPage(page) {
    $("#content").load(`views/${page}.html`, function () {
        if (page === "login") initLogin();
        if (page === "signup") initSignup();
        if (page === "admin-panel") initAdminPanel();
        if (page === "Catagori") initCategoryFilter();
        updateNavbar();
    });
}

function updateNavbar() {
    const token = localStorage.getItem("user_token");
    const user = Utils.parseJwt(token)?.user;

    if (user) {
        $("#login-nav, #signup-nav").hide();
        $("#logout-nav").removeClass("d-none");
        if (page === "admin-panel") initAdminPanel();

        if (user.role === "admin") {
            $("#admin-panel").removeClass("d-none").show();
        } else {
            $("#admin-panel").hide();
        }
    } else {
        $("#login-nav, #signup-nav").show();
        $("#logout-nav").addClass("d-none");
        $("#admin-panel").hide();
    }
}


function openCarModal(name, km, fuel, imageUrl) {
    document.getElementById("carModalTitle").textContent = name;
    document.getElementById("carName").textContent = name;
    document.getElementById("carKm").textContent = km;
    document.getElementById("carFuel").textContent = fuel;

    const carImage = document.getElementById("carImage");
    carImage.src = imageUrl;
    carImage.alt = name;

    const modal = new bootstrap.Modal(document.getElementById('carModal'));
    modal.show();
}

// Handle Rent button
document.addEventListener("DOMContentLoaded", () => {
    const rentBtn = document.getElementById("rentNowBtn");
    if (rentBtn) {
        rentBtn.addEventListener("click", () => {
            const date = document.getElementById("rentalDate").value;
            const phone = document.getElementById("phoneNumber").value;
            const review = document.getElementById("review").value;

            if (!date || !phone) {
                alert("Please fill in all required fields.");
                return;
            }

            alert(`✅ Rental confirmed!\nDate: ${date}\nPhone: ${phone}\nReview: ${review}`);

            const modal = bootstrap.Modal.getInstance(document.getElementById('carModal'));
            modal.hide();
        });
    }
});
function initCategoryFilter() {
    $('#car-category').on('change', function () {
        const selected = $(this).val();

        $('.property-item').each(function () {
            const category = $(this).data('category');
            if (selected === 'all' || selected === category) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Trigger it initially
    $('#car-category').trigger('change');
}

let lastScrollTop = 0;

window.addEventListener("scroll", function () {
    const navbar = document.getElementById("mainNavbar");
    const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    if (currentScroll > lastScrollTop) {
        // Scroll down → hide navbar
        navbar.style.top = "-100px";
    } else {
        // Scroll up → show navbar
        navbar.style.top = "0";
    }

    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
});


