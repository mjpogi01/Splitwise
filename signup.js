// Get URL parameters
const urlParams = new URLSearchParams(window.location.search);
const emailExists = urlParams.get('email_exists');
const usernameExists = urlParams.get('username_exists');
const errorMessage = document.getElementById('error-message');
errorMessage.style.marginTop = '0.5rem'; // Adjust the top margin
errorMessage.style.marginBottom = '0';  // Set bottom margin to zero


// Handle error messages
if (emailExists === '1' && usernameExists === '1') {
    errorMessage.textContent = 'Email and username are already taken.';
    errorMessage.style.display = 'block';
} else if (emailExists === '1') {
    errorMessage.textContent = 'Email is already in use.';
    errorMessage.style.display = 'block';
} else if (usernameExists === '1') {
    errorMessage.textContent = 'Username already exists.';
    errorMessage.style.display = 'block';
}

// Password validation
const form = document.getElementById('signupForm');
const password = document.getElementById('password');
const confirmPassword = document.getElementById('confirm_password');

form.addEventListener('submit', function(event) {
    if (password.value !== confirmPassword.value) {
        event.preventDefault();
        errorMessage.textContent = 'Passwords do not match!';
        errorMessage.style.display = 'block';
    }

});
