function validateForm() {
  var password = document.getElementById("password").value;
  if (password.length < 8) {
    alert("Password must be at least 8 characters long");
    return false;
  }
  return true;
}
