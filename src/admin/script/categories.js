 function addCategory(e) {
     e.preventDefault();
     var formData = new FormData(e.target);
     formData.append('action', 'add');

     fetch('categories.php', {
         method: 'POST',
         body: formData
     })
     .then(response => response.json())
     .then(data => {
         if(data.success) {
             document.querySelector('#categoryList').innerHTML = data.content;
             e.target.reset();
         } else {
             showError(data.error);
         }
     })
     .catch(error => showError('Network error'));
     return false;
 }

 function editCategory(e, id) {
     e.preventDefault();
     var formData = new FormData(e.target);
     formData.append('action', 'edit');

     fetch('categories.php', {
         method: 'POST',
         body: formData
     })
     .then(response => response.json())
     .then(data => {
         if(data.success) {
             document.querySelector('#categoryList').innerHTML = data.content;
             bootstrap.Modal.getInstance(document.querySelector('#editModal' + id)).hide();
         } else {
             showError(data.error);
         }
     })
     .catch(error => showError('Network error'));
     return false;
 }

 function deleteCategory(id) {
     if(!confirm('Are you sure you want to delete this category?')) return;

     var formData = new FormData();
     formData.append('action', 'delete');
     formData.append('id', id);

     fetch('categories.php', {
         method: 'POST',
         body: formData
     })
     .then(response => response.json())
     .then(data => {
         if(data.success) {
             document.querySelector('#categoryList').innerHTML = data.content;
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
