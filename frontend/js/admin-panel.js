function initAdminPanel() {
    const token = localStorage.getItem("user_token");
    const user = Utils.parseJwt(token)?.user;

    if (user?.role === "admin") {
        $("#admin-panel").show();

        RestClient.get("users", function (users) {
            $("#user-list").empty();
            users.forEach(u => {
                $("#user-list").append(`<li class="list-group-item bg-dark text-white">${u.name} (${u.role})</li>`);
            });
        });

        $.when(
            RestClient.get("users", () => { }),
            RestClient.get("cars", () => { }),
            RestClient.get("reviews", () => { })
        ).done((users, cars, reviews) => {
            const userCount = users[0]?.length || 0;
            const carCount = cars[0]?.length || 0;
            const reviewCount = reviews[0]?.length || 0;
            $("#stats-text").text(`Users: ${userCount}, Cars: ${carCount}, Reviews: ${reviewCount}`);
        });
    }
}
