function changeStatus(e, id) {
  e.preventDefault();
  var formData = new FormData(e.target);
  fetch('orders.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.json())
  .then(data => {
      if(data.success) {
        document.querySelector('#ajax').innerHTML = data.content;
        bootstrap.Modal.getInstance(document.querySelector('#editModal' + id)).hide();
      } else {
          showError(data.error);
      }
  })
  .catch(error => showError('Network error'));
  return false
}

function showOrderDetails(orderId) {
  fetch('orders.php?order_id=' + orderId)
    .then(response => response.json())
    .then(data => {
      if(data.success) {
        document.querySelector('#orderDetailsContent').innerHTML = data.content;
        const modal = new bootstrap.Modal(document.querySelector('#detailsModal'));
        modal.show();
      } else {
        showError(data.error);
      }
    })
    .catch(error => showError('Network error'));
}

function showError(message) {
    const errorMessage = document.querySelector('#errorMessage');
    const errorBox = document.querySelector('#errorBox');
    if(errorMessage && errorBox) {
        errorMessage.textContent = message;
        errorBox.style.display = 'block';
        errorBox.classList.add('show');
    }
}
