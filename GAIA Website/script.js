const container = document.querySelector(".container");
const signUpBtn = document.querySelector(".green-bg button");

signUpBtn.addEventListener("click", () => {
  container.classList.toggle("change");
});

function test() {
  window.open("http://localhost:8888/CS%20234/Repos/ehacks-project/login.php");
}