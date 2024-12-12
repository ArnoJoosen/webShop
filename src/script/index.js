function onCategoryClick(url) {
  var ajaxUrl = "ajax/categorys.php" + url;
  xhr = new XMLHttpRequest();
  if (xhr == null) {
    alert("Your browser does not support XMLHTTP!");
    return;
  }
  xhr.onreadystatechange = showResult;
  xhr.open("GET", ajaxUrl, true);
  xhr.send();
  // Add a new entry to the browser's history
  history.pushState({}, '', url);
}

function showResult() {
  if (xhr.readyState == 4 && xhr.status == 200) {
    var response = xhr.responseText;
    document.getElementById("ajax").innerHTML = response;
  }
}

// Handle the browser's back and forward buttons
window.onpopstate = function() {
  var currentUrl = location.search; // Get the current URL previous page is already popped from the stack
  var ajaxUrl = "ajax/categorys.php" + currentUrl; // Create the URL for the AJAX request based on the current URL
  xhr = new XMLHttpRequest();
  if (xhr == null) {
    alert("Your browser does not support XMLHTTP!");
    return;
  }
  xhr.onreadystatechange = showResult;
  xhr.open("GET", ajaxUrl, true);
  xhr.send();
};
