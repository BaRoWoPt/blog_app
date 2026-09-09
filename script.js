const avatarInput = document.getElementById('avatarInput');
const avatarBox = document.getElementById('avatarBox');
const avatarPreview = document.getElementById('avatarPreview');
const avatarText = document.getElementById('avatarText');

if (avatarInput) {
  avatarInput.addEventListener('change', function () {
    const file = this.files[0];

    if (file) {
      const reader = new FileReader();

      reader.onload = function (event) {
        avatarPreview.src = event.target.result;
        avatarPreview.style.display = 'block';
        avatarBox.classList.add('has-image');
        avatarText.style.display = 'none';
      };

      reader.readAsDataURL(file);
    }
  });
}
