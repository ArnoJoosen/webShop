function submitForm(event) {
  event.preventDefault();

  // validate password
  // Password requirements
  const password = document.getElementById("password").value;
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

  var formData = new FormData();
  formData.append("firstName", document.getElementById("firstName").value);
  formData.append("lastName", document.getElementById("lastName").value);
  formData.append("username", document.getElementById("username").value);
  formData.append("password", password);
  formData.append("superAdmin", document.getElementById("superAdmin").checked);
  formData.append("action", "add");

  xhr = new XMLHttpRequest();
  if (xhr == null) {
    alert("Your browser does not support XMLHTTP!");
    return;
  }

  xhr.onreadystatechange = showResult;
  xhr.open("POST", "./admins.php", true);
  xhr.send(formData);

  // Clear form
  document.getElementById("firstName").value = "";
  document.getElementById("lastName").value = "";
  document.getElementById("username").value = "";
  document.getElementById("password").value = "";
  document.getElementById("superAdmin").checked = false;
}

function onDelete(id) {
  xhr = new XMLHttpRequest();
  if (xhr == null) {
    alert("Your browser does not support XMLHTTP!");
    return;
  }
  var formData = new FormData();
  formData.append("action", "delete");
  formData.append("id", id);
  xhr.onreadystatechange = showResult;
  xhr.open("POST", "./admins.php", true);
  xhr.send(formData);
}

function showResult() {
  if (xhr.readyState == 4 && xhr.status == 200) {
    const data = JSON.parse(xhr.responseText);
    if (data.success) {
      document.querySelector("#ajax").innerHTML = data.content;
    } else {
      showError(data.error);
    }
  }
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
