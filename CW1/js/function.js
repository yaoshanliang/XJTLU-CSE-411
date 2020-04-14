// calculate the left time
function countdown() {
  let time = "2020-05-01 18:00:00";
  let timestamp = new Date(time);
  timestamp = timestamp.getTime() / 1000;

  var curr_time = parseInt(Date.parse(new Date()) / 1000);
  var diff_time = parseInt(timestamp - curr_time); // 倒计时时间差
  var days = Math.floor(diff_time / (3600 * 24));
  var hours = Math.floor(diff_time / 3600) - days * 24;
  var minutes = Math.floor((diff_time / 60) % 60);
  var seconds = Math.floor(diff_time % 60);
  if (days < 10) {
    days = "0" + days;
  }
  if (hours < 10) {
    hours = "0" + hours;
  }
  if (minutes < 10) {
    minutes = "0" + minutes;
  }
  if (seconds < 10) {
    seconds = "0" + seconds;
  }

  document.getElementById("days-number").innerHTML = days;
  document.getElementById("hours-number").innerHTML = hours;
  document.getElementById("minutes-number").innerHTML = minutes;
  document.getElementById("seconds-number").innerHTML = seconds;

  //   $(".timer").html(h + "时" + m + "分" + s + "秒");
  //   if (diff_time <= 0) {
  //     $(".timer").html(0 + "时" + 0 + "分" + 0 + "秒");
  //   }
}
// var start_time = setInterval("countdown()", 1000);
