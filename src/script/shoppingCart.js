function onDecrementClick(id, name) {
  xhr = new XMLHttpRequest(); // no var -> global variable
  if (xhr == null) {
    alert("Your browser does not support XMLHTTP!");
    return;
  }
  var formData = new FormData();
  formData.append("product_id", id);
  formData.append("action", "decrement");
  formData.append("name", name);
  xhr.onreadystatechange = showResult;
  xhr.open("POST", "ajax/shoppingCart.php", true);
  xhr.send(formData);
}

function onIncrementClick(id, name) {
  xhr = new XMLHttpRequest(); // no var -> global variable
  if (xhr == null) {
    alert("Your browser does not support XMLHTTP!");
    return;
  }
  var formData = new FormData();
  formData.append("product_id", id);
  formData.append("action", "increment");
  formData.append("name", name);
  xhr.onreadystatechange = showResult;
  xhr.open("POST", "ajax/shoppingCart.php", true);
  xhr.send(formData);
}

function onRemoveClick(id, name) {
  xhr = new XMLHttpRequest(); // no var -> global variable
  if (xhr == null) {
    alert("Your browser does not support XMLHTTP!");
    return;
  }
  var formData = new FormData();
  formData.append("product_id", id);
  formData.append("action", "remove");
  formData.append("name", name);
  xhr.onreadystatechange = showResult;
  xhr.open("POST", "ajax/shoppingCart.php", true);
  xhr.send(formData);
}

function showResult() {
  if (xhr.readyState == 4 && xhr.status == 200) {
    var response = xhr.responseText;
    if (response == "0") {
      document.getElementById("ajax").innerHTML = "Cart is empty";
    } else {
      document.getElementById("ajax").innerHTML = response;
    }
  }
}
