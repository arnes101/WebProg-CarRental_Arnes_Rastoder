let RestClient = {
  request: function (url, method, data, callback, error_callback) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + url,
      type: method,
      contentType: "application/json",
      data: JSON.stringify(data),
      beforeSend: function (xhr) {
        const token = localStorage.getItem("user_token");
        if (token) xhr.setRequestHeader("Authorization", "Bearer " + token);
      }
    })
      .done(callback)
      .fail(error_callback || function (jqXHR) {
        console.error("❌ Request failed", jqXHR.responseJSON);
        alert(jqXHR?.responseJSON?.error || "Request failed");
      });
  },
  get: function (url, cb, err) { RestClient.request(url, "GET", {}, cb, err); },
  post: function (url, data, cb, err) { RestClient.request(url, "POST", data, cb, err); },
  put: function (url, data, cb, err) { RestClient.request(url, "PUT", data, cb, err); },
  delete: function (url, data, cb, err) { RestClient.request(url, "DELETE", data, cb, err); }
};
