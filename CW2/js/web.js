$(function () {
  var url = window.location.href;
  var li = $(".nav-menus>li");
  li.removeClass("current");
  if (url.indexOf("/1930954.php") >= 0) {
    li.eq(0).find("a").addClass("current");
  }
  if (url.indexOf("/sports.php") >= 0) {
    li.eq(1).find("a").addClass("current");
  }
  if (url.indexOf("/statistics.php") >= 0) {
    li.eq(2).find("a").addClass("current");
  }
  if (url.indexOf("/user") >= 0) {
    li.eq(3).find("a").addClass("current");
  }
});

// Make changes to jquery ajax
function ajax(url, method, data, successCallback, errorCallback) {
  $.ajax({
    url: url,
    type: method,
    dataType: "json",
    data: data,

    success: function (data) {
      if (data["code"] === 0) {
        if ("function" == typeof successCallback) {
          successCallback(data["data"]);
        }
      } else {
        if ("function" == typeof errorCallback) {
          errorCallback(data["data"]);
        }
      }
    },
    error: function (data) {
      console.log(data);
    },
  });
}
