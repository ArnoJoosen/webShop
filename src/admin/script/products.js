function addItem(e) {
    e.preventDefault();

    var xhr = new XMLHttpRequest();
    var formData = new FormData(e.target);

    xhr.open('POST', '/admin/products.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.querySelector('#ajax').innerHTML = response.tableContent;
                    e.target.reset();
                } else {
                    document.querySelector('#errorMessage').textContent = response.error;
                    document.querySelector('#errorBox').style.display = 'block';
                    document.querySelector('#errorBox').classList.add('show');
                }
            } catch(e) {
                console.error('JSON parsing failed:', e);
                document.querySelector('#errorMessage').textContent = 'Invalid server response';
                document.querySelector('#errorBox').style.display = 'block';
                document.querySelector('#errorBox').classList.add('show');
            }
        }
    };

    xhr.send(formData);
    return false;
}

function editProduct(e) {
    e.preventDefault();

    var xhr = new XMLHttpRequest();
    var formData = new FormData(e.target);
    formData.append('action', 'edit');

    xhr.open('POST', '/admin/products.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.querySelector('#ajax').innerHTML = response.tableContent;
                    var modal = bootstrap.Modal.getInstance(e.target.closest('.modal'));
                    modal.hide();
                } else {
                    document.querySelector('#errorMessage').textContent = response.error;
                    document.querySelector('#errorBox').style.display = 'block';
                    document.querySelector('#errorBox').classList.add('show');
                }
            } catch(e) {
                console.error('JSON parsing failed:', e);
                document.querySelector('#errorMessage').textContent = 'Invalid server response';
                document.querySelector('#errorBox').style.display = 'block';
                document.querySelector('#errorBox').classList.add('show');
            }
        }
    };

    xhr.send(formData);
    return false;
}

function toggleAvailability(id) {
  var xhr = new XMLHttpRequest();
  var formData = new FormData();
  formData.append('action', 'toggleAvailability');
  formData.append('id', id);

  xhr.open('POST', '/admin/products.php', true);

  xhr.onload = function() {
      if (xhr.status === 200) {
          try {
              var response = JSON.parse(xhr.responseText);
              if (response.success) {
                  document.querySelector('#ajax').innerHTML = response.tableContent;
              } else {
                  document.querySelector('#errorMessage').textContent = response.error;
                  document.querySelector('#errorBox').style.display = 'block';
                  document.querySelector('#errorBox').classList.add('show');
              }
          } catch(e) {
              console.error('JSON parsing failed:', e);
              document.querySelector('#errorMessage').textContent = 'Invalid server response';
              document.querySelector('#errorBox').style.display = 'block';
              document.querySelector('#errorBox').classList.add('show');
          }
      }
  };

  xhr.send(formData);
  return false;
}
