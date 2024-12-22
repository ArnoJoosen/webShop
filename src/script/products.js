function onSearch() {
  var search = document.getElementById("search").value;
  xhr = new XMLHttpRequest();
  if (xhr == null) {
    alert("Your browser does not support XMLHTTP!");
    return;
  }
  var url = "./ajax/products.php?search=" + search;
  xhr.onreadystatechange = showResult;
  xhr.open("GET", url, true);
  xhr.send();
}

function showResult() {
  if (xhr.readyState == 4 && xhr.status == 200) {
    var response = xhr.responseText;
    document.getElementById("ajax").innerHTML = response;
  }
}
