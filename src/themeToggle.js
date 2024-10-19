function applyTheme() {
  const savedTheme = localStorage.getItem("theme");
  const html = document.documentElement;
  const themeIcon = document.querySelector("#themeToggle i");

  if (savedTheme) {
    html.setAttribute("data-bs-theme", savedTheme);
    themeIcon.classList.toggle("fa-moon", savedTheme === "dark");
    themeIcon.classList.toggle("fa-sun", savedTheme !== "dark");
  }
}

// Call applyTheme when the page loads
document.addEventListener("DOMContentLoaded", applyTheme);

document.addEventListener("DOMContentLoaded", () => {
  const themeToggle = document.getElementById("themeToggle");
  const themeIcon = themeToggle.querySelector("i");
  const html = document.documentElement;

  // Function to set theme
  const setTheme = (theme) => {
    html.setAttribute("data-bs-theme", theme);
    localStorage.setItem("theme", theme);
    themeIcon.classList.toggle("fa-moon", theme === "dark");
    themeIcon.classList.toggle("fa-sun", theme !== "dark");
  };

  // Check local storage for saved theme
  const savedTheme = localStorage.getItem("theme");
  setTheme(savedTheme || "light"); // Default theme

  themeToggle.addEventListener("click", () => {
    const currentTheme = html.getAttribute("data-bs-theme");
    setTheme(currentTheme === "dark" ? "light" : "dark");
  });
});
