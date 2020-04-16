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
}

function play() {
  audiop.play();
  document
    .getElementsByClassName("music-player-image")[0]
    .classList.add("play");
  document.getElementById("play").style.display = "none";
  document.getElementById("stop").style.display = "block";

  for (let i = 0; i < songs.length; i++) {
    document
      .getElementsByClassName("songItem")
      [i].classList.remove("currentSong");

    if (i == currentSong) {
      document.getElementById("song-name").innerText = songs[i].name;
      document.getElementById("song-singer").innerText = songs[i].singer;
      document
        .getElementById("song-icon")
        .setAttribute("src", "./audio/" + songs[i].image);
      document
        .getElementById("song-background")
        .setAttribute("backgroundImage", "./audio/" + songs[i].image);
      document
        .getElementsByClassName("songItem")
        [i].classList.add("currentSong");
    }
  }
}
function pauseplay() {
  audiop.pause();
  document
    .getElementsByClassName("music-player-image")[0]
    .classList.remove("play");
  document.getElementById("play").style.display = "block";
  document.getElementById("stop").style.display = "none";
}

function mute() {
  lastVolume = audiop.volume;
  audiop.volume = 0;
  document.getElementById("sound").style.display = "none";
  document.getElementById("mute").style.display = "block";
  document.getElementById("volumeBar").style.width = "0%";
}
function sound() {
  audiop.volume = lastVolume;
  document.getElementById("sound").style.display = "block";
  document.getElementById("mute").style.display = "none";
  document.getElementById("volumeBar").style.width =
    parseFloat(lastVolume * 100, 10) + "%";
}
function volumeUp() {
  console.log(audiop.volume);
  if (audiop.volume < 1) {
    audiop.volume = audiop.volume + 0.1;
  }
  document.getElementById("volumeBar").style.width =
    parseFloat(audiop.volume * 100, 10) + "%";
}
function volumeDown() {
  console.log(audiop.volume);
  if (audiop.volume > 0) {
    audiop.volume = audiop.volume - 0.1;
  }
  document.getElementById("volumeBar").style.width =
    parseFloat(audiop.volume * 100, 10) + "%";
}

// Next song function
function next() {
  currentSong++;
  if (currentSong > songs.length - 1) {
    currentSong = 0;
  }
  audiop.setAttribute("src", "./audio/" + songs[currentSong].path);
  play();
}

// Previous song function
function previous() {
  currentSong--;
  if (currentSong < 0) {
    currentSong = songs.length - 1;
  }
  audiop.setAttribute("src", "./audio/" + songs[currentSong].path);
  play();
}

function updatePlayBar() {
  let minutes = (audiop.currentTime / 60).toFixed(0);
  let seconds = (audiop.currentTime % 60).toFixed(0);
  if (seconds < 10) {
    seconds = "0" + seconds;
  }
  document.getElementById("nowTime").innerText = minutes + ":" + seconds;

  if (!isNaN(audiop.duration)) {
    minutes = (audiop.duration / 60).toFixed(0);
    seconds = (audiop.duration % 60).toFixed(0);
    if (seconds < 10) {
      seconds = "0" + seconds;
    }
    document.getElementById("totalTime").innerText = minutes + ":" + seconds;
  }

  document.getElementById("bar").style.width =
    parseFloat((audiop.currentTime / audiop.duration) * 100, 10) + "%";
}

function playSong(id) {
  console.log(id);
  currentSong = id;
  audiop.setAttribute("src", "./audio/" + songs[currentSong].path);
  play();
}

function showImage(id) {
  currentImage = id;
  document
    .getElementById("zoomImage")
    .setAttribute("src", "./gallery/" + gallery[id]);
  document.getElementById("zoom").style.display = "block";
}
function closeImage() {
  document.getElementById("zoomImage").setAttribute("src", "");
  document.getElementById("zoom").style.display = "none";
}
function previousImage() {
  currentImage--;
  if (currentImage < 0) {
    currentImage = gallery.length - 1;
  }
  document
    .getElementById("zoomImage")
    .setAttribute("src", "./gallery/" + gallery[currentImage]);
}

function nextImage() {
  currentImage++;
  if (currentImage > gallery.length - 1) {
    currentImage = 0;
  }
  document
    .getElementById("zoomImage")
    .setAttribute("src", "./gallery/" + gallery[currentImage]);
}
