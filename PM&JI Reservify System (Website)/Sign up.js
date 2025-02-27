document.addEventListener("DOMContentLoaded", function() {
  // TERMS & PASSWORD TOGGLE FUNCTIONALITY
  const termsCheckbox = document.getElementById('terms');
  const registerButton = document.getElementById('register-btn');

  termsCheckbox.addEventListener('change', function() {
    registerButton.disabled = !termsCheckbox.checked;
  });

  const togglePassword = document.getElementById('toggle-password');
  const passwordInput = document.getElementById('password');
  const eyeIcon = document.getElementById('eye-icon');

  togglePassword.addEventListener('click', function () {
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcon.classList.remove('fa-eye');
      eyeIcon.classList.add('fa-eye-slash');
    } else {
      passwordInput.type = 'password';
      eyeIcon.classList.remove('fa-eye-slash');
      eyeIcon.classList.add('fa-eye');
    }
  });
});
