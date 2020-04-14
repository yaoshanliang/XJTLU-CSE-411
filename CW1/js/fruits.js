let fruits = [
  {
    name: "Apple",
    image: "apple.jpg",
    desc: "Green	Medium	Crispy"
  },
  {
    name: "Orange",
    image: "orange.jpg",
    desc: "Orange	Large	Sweet"
  },
  {
    name: "Lemon",
    image: "lemon.jpg",
    desc: "Yellow	Small	Tangy"
  },
  {
    name: "Banana",
    image: "banana.jpg",
    desc: "Yellow	Large	Soft"
  },
  {
    name: "More",
    image: "more.jpg",
    desc: "Colorful"
  }
];

function menu() {
  let menu = "";
  let html = "";

  fruits.map(item => {
    console.log(item);
    menu +=
      "<li>" +
      '<a href="./' +
      item.name +
      '.html">' +
      item.name +
      "</a>" +
      "</li>";

    html +=
      '<div class="col-md-20">' +
      '<div class="thumbnail">' +
      '<div class="image view view-first">' +
      '<a href="./' +
      item.name +
      '.html"> <img class="image-item" src=./images/' +
      item.image +
      ' alt="' +
      item.name +
      '"></a>' +
      '<div class="mask">' +
      "<p>Your Text</p>" +
      '<div class="tools tools-bottom">' +
      "</div>" +
      "</div>" +
      "</div >" +
      '<div class="caption">' +
      "<p>" +
      item.desc +
      "</p>" +
      "</div>" +
      "</div >" +
      "</div >";
  });
  document.getElementById("menu").insertAdjacentHTML("afterend", menu);
  document.getElementById("list").innerHTML = html;
}

let backgroundColor = "#FFF";
function changeBackgroundColor() {
  var ele = document.querySelector("body");
  console.log(ele.style.backgroundColor)
  if (ele.style.backgroundColor == '' || ele.style.backgroundColor == "rgb(255, 255, 255)") {
    ele.style.backgroundColor = "#E8F5E9";
  } else {
    ele.style.backgroundColor = "#FFF";
  }
}
