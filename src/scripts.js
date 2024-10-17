document.getElementById("theme-toggle").addEventListener("click", function () {
  document.body.classList.toggle("dark-theme");
  document.body.classList.toggle("light-theme");
  document.querySelector(".navbar").classList.toggle("navbar-dark");
  document.querySelector(".navbar").classList.toggle("navbar-light");
  document.querySelector(".navbar").classList.toggle("bg-dark");
  document.querySelector(".navbar").classList.toggle("bg-light");
  document.getElementById("theme-icon").classList.toggle("light-theme-icon");
  document.getElementById("theme-icon").classList.toggle("dark-theme-icon");
  document.querySelector(".footer").classList.toggle("bg-dark");
  document.querySelector(".footer").classList.toggle("bg-light");
});
