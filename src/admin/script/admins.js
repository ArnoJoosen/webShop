function submitForm(event) {
  event.preventDefault();

    var formData = new FormData();
    formData.append("firstName", document.getElementById("firstName").value);
    formData.append("lastName", document.getElementById("lastName").value);
    formData.append("username", document.getElementById("username").value);
    formData.append("password", document.getElementById("password").value);
    formData.append("superAdmin", document.getElementById("superAdmin").checked);
    formData.append("action", "add");

    xhr = new XMLHttpRequest();
    if (xhr == null) {
      alert("Your browser does not support XMLHTTP!");
      return;
    }

    xhr.onreadystatechange = showResult;
    xhr.open("POST", "admins.php", true);
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
  xhr.open("POST", "admins.php", true);
  xhr.send(formData);
}

function showResult() {
  if (xhr.readyState == 4 && xhr.status == 200) {
    var response = xhr.responseText;
    document.getElementById("ajax").innerHTML = response;
  }
}
