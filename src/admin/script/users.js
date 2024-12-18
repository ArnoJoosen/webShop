function viewOrders(userId) {
  fetch('users.php?user_id=' + userId)
    .then(response => response.json())
    .then(data => {
      if(data.success) {
        const tbody = document.querySelector('#ordersTableBody');
        tbody.innerHTML = data.content;
        const modal = new bootstrap.Modal(document.querySelector('#ordersModal'));
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
