const container = document.querySelector(".container");
const loginBtn = document.querySelector(".green-bg button");

loginBtn.addEventListener("click", () => {
  container.classList.toggle("change");
});

function test() {
  window.open("http://localhost:8888/CS%20234/Repos/ehacks-project/layout.php");
}