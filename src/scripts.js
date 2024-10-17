function saveThemePreference(theme) {
  localStorage.setItem("theme", theme);
}

function loadThemePreference() {
  return localStorage.getItem("theme") || "light-theme";
}

function applyTheme(theme) {
  document.body.classList.toggle("dark-theme", theme === "dark-theme");
  document.body.classList.toggle("light-theme", theme === "light-theme");

  const navbar = document.querySelector(".navbar");
  navbar.classList.toggle("navbar-dark", theme === "dark-theme");
  navbar.classList.toggle("navbar-light", theme === "light-theme");
  navbar.classList.toggle("bg-dark", theme === "dark-theme");
  navbar.classList.toggle("bg-light", theme === "light-theme");

  const themeIcon = document.getElementById("theme-icon");
  themeIcon.classList.toggle("dark-theme-icon", theme === "dark-theme");
  themeIcon.classList.toggle("light-theme-icon", theme === "light-theme");

  const footer = document.querySelector(".footer");
  footer.classList.toggle("bg-dark", theme === "dark-theme");
  footer.classList.toggle("bg-light", theme === "light-theme");
}

document.addEventListener("DOMContentLoaded", function () {
  const theme = loadThemePreference();
  applyTheme(theme);
});

document.getElementById("theme-toggle").addEventListener("click", function () {
  const currentTheme = document.body.classList.contains("dark-theme")
    ? "light-theme"
    : "dark-theme";
  applyTheme(currentTheme);
  saveThemePreference(currentTheme);
});
