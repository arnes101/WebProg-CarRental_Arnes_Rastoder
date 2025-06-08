function initAdminPanel() {
    // Load and display all users
    RestClient.get("users", function (users) {
        $("#user-list").empty();
        users.forEach(user => {
            const userHtml = `
                <li class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${user.name}</strong> (${user.role}) - ${user.email}
                    </div>
                    <div>
                        <button class="btn btn-sm btn-light me-2 edit-user" data-id="${user.id}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-user" data-id="${user.id}">Delete</button>
                    </div>
                </li>`;
            $("#user-list").append(userHtml);
        });
    });

    // Load and display cars
    RestClient.get("cars", function (cars) {
        $("#car-list").empty();
        cars.forEach(car => {
            const carHtml = `
                <li class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${car.name}</strong> - $${car.price_per_day}/day - ${car.kilometers}km
                    </div>
                    <div>
                        <button class="btn btn-sm btn-light me-2 edit-car" data-id="${car.id}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-car" data-id="${car.id}">Delete</button>
                    </div>
                </li>`;
            $("#car-list").append(carHtml);
        });
    });

    // Edit user (role and name)
    $(document).on("click", ".edit-user", function () {
        const id = $(this).data("id");
        const newName = prompt("Enter new name:");
        const newRole = prompt("Enter new role (admin or client):");

        if (newName && newRole) {
            RestClient.put(`users/${id}`, { name: newName, role: newRole }, function () {
                alert("✅ User updated!");
                initAdminPanel(); // Reload
            });
        }
    });

    // Delete user
    $(document).on("click", ".delete-user", function () {
        const id = $(this).data("id");
        if (confirm("Are you sure you want to delete this user?")) {
            RestClient.delete(`users/${id}`, {}, function () {
                alert("✅ User deleted!");
                initAdminPanel(); // Reload
            });
        }
    });

    // Edit car
    $(document).on("click", ".edit-car", function () {
        const id = $(this).data("id");
        const newName = prompt("Enter new car name:");
        const newPrice = prompt("Enter new price per day:");
        const newKm = prompt("Enter new kilometers:");
        const newFuel = prompt("Enter new fuel consumption:");
        const newCategoryId = prompt("Enter new category id:");
        const newImageUrl = prompt("Enter new image URL:");

        if (newName && newPrice && newKm && newFuel && newCategoryId && newImageUrl) {
            RestClient.put(`cars/${id}`, {
                name: newName,
                price_per_day: parseFloat(newPrice),
                kilometers: parseInt(newKm),
                fuel_consumption: newFuel,
                category_id: parseInt(newCategoryId),
                image_url: newImageUrl
            }, function () {
                alert("✅ Car updated!");
                initAdminPanel(); // Reload
            });
        }
    });

    // Delete car
    $(document).on("click", ".delete-car", function () {
        const id = $(this).data("id");
        if (confirm("Are you sure you want to delete this car?")) {
            RestClient.delete(`cars/${id}`, {}, function () {
                alert("✅ Car deleted!");
                initAdminPanel(); // Reload
            });
        }
    });

    // Create car (via form)
    $("#add-car-form").on("submit", function (e) {
        e.preventDefault();
        const data = {
            name: $("#carName").val(),
            price_per_day: parseFloat($("#carPrice").val()),
            kilometers: parseInt($("#carKm").val()),
            fuel_consumption: $("#carFuel").val(),
            category_id: parseInt($("#carCategory").val()),
            image_url: $("#carImage").val()
        };

        RestClient.post("cars", data, function () {
            alert("✅ Car added!");
            $("#add-car-form")[0].reset();
            initAdminPanel(); // Reload
        });
    });
}
