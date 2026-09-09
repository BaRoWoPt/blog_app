const form = document.getElementById('profileForm');
const submitBtn = document.getElementById('submitBtn');
const errorMessage = document.getElementById('checkboxError');
const checkboxes = document.querySelectorAll('#profileForm input[type="checkbox"]');
const countryInput = document.getElementById('country');
const genderInputs = document.querySelectorAll('#profileForm input[name="gender"]');
const avatarInput = document.getElementById('avatarUpload');
const fileError = document.getElementById('fileError');
const avatarPreviewBox = document.getElementById('avatarPreviewBox');
const avatarInitials = document.getElementById('avatarInitials');

function validateForm() {
  const checkedCount = [...checkboxes].filter((checkbox) => checkbox.checked).length;
  const selectedGender = [...genderInputs].some((radio) => radio.checked);
  const selectedCountry = countryInput.value !== '';
  const isValid = selectedCountry && selectedGender && checkedCount > 0;

  if (isValid) {
    submitBtn.disabled = false;
    errorMessage.textContent = '✅ Thông tin hợp lệ.';
    errorMessage.style.color = '#2ecc71';
  } else {
    submitBtn.disabled = true;
    errorMessage.textContent = '❌ Vui lòng chọn quốc gia, giới tính và ít nhất một checkbox.';
    errorMessage.style.color = '#d63031';
  }
}

document.querySelectorAll('#profileForm select, #profileForm input').forEach((input) => {
  input.addEventListener('change', validateForm);
});

form.addEventListener('submit', function (event) {
  const checkedCount = [...checkboxes].filter((checkbox) => checkbox.checked).length;
  const selectedGender = [...genderInputs].some((radio) => radio.checked);
  const selectedCountry = countryInput.value !== '';

  if (!selectedCountry || !selectedGender || checkedCount === 0) {
    event.preventDefault();
    errorMessage.textContent = '❌ Vui lòng hoàn thành tất cả các trường trước khi submit.';
    errorMessage.style.color = '#d63031';
    submitBtn.disabled = true;
    return;
  }

  alert('✅ Form đã được submit thành công!');
});

if (avatarInput) {
  const dropZone = document.getElementById('dropZone');

  function demoAjaxUpload(file) {
    const formData = new FormData();
    formData.append('avatar', file);
    fileError.textContent = '⏳ Đang upload demo...';

    setTimeout(() => {
      fileError.textContent = '✅ Upload demo thành công! Ảnh chưa được lưu lên server.';
    }, 1000);
  }

  function validateAndPreview(file) {
    if (!file) {
      return;
    }

    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    const maxSize = 5 * 1024 * 1024;

    if (!allowedTypes.includes(file.type)) {
      fileError.textContent = '❌ Chỉ chấp nhận file JPG, JPEG hoặc PNG.';
      avatarPreviewBox.innerHTML = '<span id="avatarInitials">DB</span>';
      return;
    }

    if (file.size > maxSize) {
      fileError.textContent = '❌ Dung lượng file phải dưới 5MB.';
      avatarPreviewBox.innerHTML = '<span id="avatarInitials">DB</span>';
      return;
    }

    const reader = new FileReader();

    reader.onload = function (event) {
      avatarPreviewBox.innerHTML = `<img src="${event.target.result}" alt="Avatar preview">`;
      demoAjaxUpload(file);
    };

    reader.readAsDataURL(file);
  }

  avatarInput.addEventListener('change', function () {
    validateAndPreview(this.files[0]);
  });

  ['dragenter', 'dragover'].forEach((eventName) => {
    dropZone.addEventListener(eventName, function (event) {
      event.preventDefault();
      dropZone.classList.add('dragover');
    });
  });

  ['dragleave', 'drop'].forEach((eventName) => {
    dropZone.addEventListener(eventName, function (event) {
      event.preventDefault();
      dropZone.classList.remove('dragover');
    });
  });

  dropZone.addEventListener('drop', function (event) {
    event.preventDefault();
    const file = event.dataTransfer.files[0];
    validateAndPreview(file);
  });
}

validateForm();
