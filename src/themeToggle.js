function applyTheme() {
  const savedTheme = localStorage.getItem("theme");
  const html = document.documentElement;
  const themeIcon = document.querySelector("#themeToggle i");

  if (savedTheme) {
    html.setAttribute("data-bs-theme", savedTheme);
    if (savedTheme === "dark") {
      themeIcon.classList.remove("fa-moon", "text-dark");
      themeIcon.classList.add("fa-sun");
    } else {
      themeIcon.classList.remove("fa-sun");
      themeIcon.classList.add("fa-moon", "text-dark");
    }
  }
}

// Call applyTheme when the page loads
document.addEventListener("DOMContentLoaded", applyTheme);

document.addEventListener("DOMContentLoaded", (event) => {
  const themeToggle = document.getElementById("themeToggle");
  const themeIcon = themeToggle.querySelector("i");
  const html = document.documentElement;

  // Function to set theme
  const setTheme = (theme) => {
    html.setAttribute("data-bs-theme", theme);
    localStorage.setItem("theme", theme);
    if (theme === "dark") {
      themeIcon.classList.remove("fa-moon", "text-dark");
      themeIcon.classList.add("fa-sun");
    } else {
      themeIcon.classList.remove("fa-sun");
      themeIcon.classList.add("fa-moon", "text-dark");
    }
  };

  // Check local storage for saved theme
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    setTheme(savedTheme);
  } else {
    setTheme("light"); // Default theme
  }

  themeToggle.addEventListener("click", () => {
    const currentTheme = html.getAttribute("data-bs-theme");
    setTheme(currentTheme === "dark" ? "light" : "dark");
  });
});
