function showOrderDetails(orderId) {
  fetch('./orders.php?order_id=' + orderId)
    .then(response => response.json())
    .then(data => {
      if(data.success) {
        document.querySelector('#orderDetailsContent').innerHTML = data.content;
        document.querySelector('#orderTotal').textContent = data.total + '€';
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
