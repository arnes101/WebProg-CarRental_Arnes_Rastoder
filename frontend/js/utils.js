let Utils = {
  parseJwt: function (token) {
    if (!token) return null;
    try {
      const payload = token.split('.')[1];
      const decoded = atob(payload);
      return JSON.parse(decoded);
    } catch (e) {
      console.error("Invalid JWT token", e);
      return null;
    }
  }
};
function updateNavbar() {
  const token = localStorage.getItem("user_token");
  const user = Utils.parseJwt(token)?.user;

  if (user) {
    $("#logout-nav").show();
    $(".nav-link[href*='login'], .nav-link[href*='signup']").hide();

    if (user.role === "admin") {
      $("#admin-panel").show();
    } else {
      $("#admin-panel").hide();
    }
  } else {
    $("#logout-nav").hide();
    $(".nav-link[href*='login'], .nav-link[href*='signup']").show();
    $("#admin-panel").hide();
  }
}


$(document).on("click", "#logout-link", function () {
  localStorage.removeItem("user_token");
  updateNavbar();
  window.location.hash = "#home";
});
