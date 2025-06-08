let page = "";



// Load partials and initialize as before
function loadPage(newPage) {
    // Correct fallback condition
    if (!newPage || newPage === "undefined" || newPage === undefined) {
        newPage = "home";
    }

    page = newPage;
    $("#content").load(`views/${newPage}.html`, function () {
        if (newPage === "login") initLogin();
        if (newPage === "chat") initChat();
        if (newPage === "signup") initSignup();
        if (newPage === "admin-panel") initAdminPanel();
        if (newPage === "Catagori") initCategoryFilter();
        if (newPage === "chat" && typeof initChat === "function") initChat();

        updateNavbar();

        // 🟦 Attach "Rent Now" button click event handler AFTER loading HTML
        const rentBtn = document.getElementById("rentNowBtn");
        if (rentBtn) {
            rentBtn.addEventListener("click", () => {
                console.log("Rent button clicked from catagori.html"); // For debugging
                const token = localStorage.getItem("user_token");
                const user = Utils.parseJwt(token)?.user;

                if (!user) {
                    return alert("❌ You must be logged in to rent a car.");
                }

                const date = document.getElementById("rentalDate").value;
                const phone = document.getElementById("phoneNumber").value;
                const pickupLocation = document.getElementById("pickupLocation")?.value || "";
                const review = document.getElementById("carReview").value;

                if (!date || !phone || !pickupLocation) {
                    return alert("Please fill in all required fields.");
                }

                const carId = document.getElementById("carModal").getAttribute("data-car-id");

                RestClient.post("bookings", {
                    user_id: user.id,
                    car_id: carId,
                    rental_date: date,
                    pickup_location: pickupLocation,
                    phone_number: phone,
                }, function (response) {
                    alert("✅ Rental confirmed!");
                    bootstrap.Modal.getInstance(document.getElementById('carModal')).hide();
                }, function (xhr) {
                    alert("❌ Failed to rent the car. Please try again.");
                    console.error(xhr);
                });
            });
        }

        // Submit review button
        const reviewBtn = document.getElementById("submitReviewBtn");
        if (reviewBtn) {
            reviewBtn.addEventListener("click", () => {
                const token = localStorage.getItem("user_token");
                if (!token) {
                    alert("❌ You must be logged in to leave a review.");
                    return;
                }

                const user = Utils.parseJwt(token)?.user;
                if (!user) {
                    alert("❌ Invalid user. Please log in again.");
                    return;
                }

                const carId = document.getElementById("carModal").getAttribute("data-car-id");
                const reviewText = document.getElementById("carReview").value.trim();

                if (!reviewText || reviewText.length < 5) {
                    alert("Please enter a review with at least 5 characters.");
                    return;
                }

                RestClient.post("reviews", {
                    user_id: user.id,
                    car_id: carId,
                    content: reviewText
                }, function () {
                    alert("✅ Review submitted!");
                    document.getElementById("carReview").value = "";
                    fetchReviewsForCar(carId);
                }, function (xhr) {
                    alert("❌ Failed to submit review.");
                    console.error(xhr);
                });
            });
        }
    });
}

function updateNavbar() {
    const token = localStorage.getItem("user_token");
    const user = Utils.parseJwt(token)?.user;

    if (user) {
        $("#login-nav, #signup-nav").hide();
        $("#logout-nav").removeClass("d-none");

        if (user.role === "admin") {
            $("#admin-dropdown").removeClass("d-none").show();
            // ✅ Only admins get admin panel loaded!
            if (typeof initAdminPanel === "function") initAdminPanel();
        } else {
            $("#admin-dropdown").hide();
        }
    } else {
        $("#login-nav, #signup-nav").show();
        $("#logout-nav").addClass("d-none");
        $("#admin-dropdown").hide();
    }
}


function openCarModal(carId, name, km, fuel, imageUrl) {
    document.getElementById("carModalLabel").textContent = name; // Fixed this line
    document.getElementById("carName").textContent = name;
    document.getElementById("carKm").textContent = km;
    document.getElementById("carFuel").textContent = fuel;

    const carImage = document.getElementById("carImage");
    carImage.src = imageUrl;
    carImage.alt = name;

    // Store car ID in modal dataset
    document.getElementById("carModal").setAttribute("data-car-id", carId);

    const modalInstance = new bootstrap.Modal(document.getElementById('carModal'));
    modalInstance.show();
}
function fetchReviewsForCar(carId) {
    RestClient.get("reviews", function (data) {
        const carReviews = data.filter(r => r.car_id == carId);
        displayReviews(carReviews);
    }, function (xhr) {
        console.error("❌ Failed to load reviews.", xhr);
    });
}
function displayReviews(reviewsArray) {
    const reviewsList = document.getElementById("reviewsList");
    reviewsList.innerHTML = "";

    if (reviewsArray.length > 0) {
        reviewsArray.forEach((review, index) => {
            const li = document.createElement("li");
            li.textContent = `${index + 1}. ${review.content}`;
            reviewsList.appendChild(li);
        });
    } else {
        const li = document.createElement("li");
        li.textContent = "No reviews yet.";
        reviewsList.appendChild(li);
    }
}


function initCategoryFilter() {
    $('#car-category').on('change', function () {
        const selected = $(this).val();
        $('.property-item').each(function () {
            const category = $(this).data('category');
            selected === 'all' || selected === category ? $(this).show() : $(this).hide();
        });
    });
    $('#car-category').trigger('change');
}

let lastScrollTop = 0;
window.addEventListener("scroll", function () {
    const navbar = document.getElementById("mainNavbar");
    const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
    navbar.style.top = currentScroll > lastScrollTop ? "-100px" : "0";
    lastScrollTop = Math.max(currentScroll, 0);
});

// Hash-based navigation
window.addEventListener("hashchange", () => {
    const pageName = location.hash.replace("#", "") || "home";
    loadPage(pageName);
});

// Initial load
const initialPage = location.hash.replace("#", "") || "home";
loadPage(initialPage);
updateNavbar();

