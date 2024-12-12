function onSearch() {
  var search = document.getElementById("search").value;
  if (search.length < 2) return; // Don't search for less than 2 characters to avoid too many requests to the server
  xhr = new XMLHttpRequest();
  if (xhr == null) {
    alert("Your browser does not support XMLHTTP!");
    return;
  }
  var url = "ajax/products.php?search=" + search;
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
