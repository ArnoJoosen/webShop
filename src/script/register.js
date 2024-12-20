function validatePassword() {
  const password = document.getElementById("password").value;

  // Password requirements
  const minLength = 8;
  const hasNumber = /\d/.test(password);
  const hasUpperCase = /[A-Z]/.test(password);

  if (password.length < minLength) {
    showError("Password must be at least 8 characters long");
    return false;
  }

  if (!hasNumber) {
    showError("Password must contain at least one number");
    return false;
  }

  if (!hasUpperCase) {
    showError("Password must contain at least one uppercase letter");
    return false;
  }
  closeError();
  return true;
}

function showError(message) {
  const errorMessage = document.querySelector("#errorMessage");
  const errorBox = document.querySelector("#errorBox");
  if (errorMessage && errorBox) {
    errorMessage.textContent = message;
    errorBox.style.display = "block";
    errorBox.classList.add("show");
  }
}

function closeError() {
  const errorBox = document.querySelector("#errorBox");
  if (errorBox) {
    errorBox.style.display = "none";
    errorBox.classList.remove("show");
  }
}
