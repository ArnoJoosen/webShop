function addAddress(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    fetch('/ajax/addAddress.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.querySelector('#addressList').innerHTML = data.content;
            document.querySelector('#addressForm').reset();
            const modal = document.querySelector('#addAddressModal');
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            bootstrapModal.hide();
        } else {
            showError(data.error);
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        showError('Network error');
    });
    return false;
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
