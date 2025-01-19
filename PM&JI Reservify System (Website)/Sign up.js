document.addEventListener("DOMContentLoaded", function() {
  // Get the checkbox and the register button elements
  const termsCheckbox = document.getElementById('terms');
  const registerButton = document.getElementById('register-btn');

  // Function to enable/disable the button based on checkbox status
  termsCheckbox.addEventListener('change', function() {
      if (termsCheckbox.checked) {
          registerButton.disabled = false; // Enable button
      } else {
          registerButton.disabled = true; // Disable button
      }
  });


  // Password toggle functionality (existing code)
  const togglePassword = document.getElementById('toggle-password');
  const passwordInput = document.getElementById('password');
  const eyeIcon = document.getElementById('eye-icon');

  togglePassword.addEventListener('click', function () {
      if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          eyeIcon.classList.remove('fa-eye');
          eyeIcon.classList.add('fa-eye-slash'); // Update icon to eye-slash
      } else {
          passwordInput.type = 'password';
          eyeIcon.classList.remove('fa-eye-slash');
          eyeIcon.classList.add('fa-eye'); // Revert to eye icon
      }
  });
});





