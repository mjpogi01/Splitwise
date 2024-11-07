document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('loginForm');
    const loginError = document.getElementById('loginError');

    form.addEventListener('submit', function (event) {
        event.preventDefault();  // Prevent default form submission

        const formData = new FormData(form);

        fetch('login_process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');  // Handle non-200 status codes
            }
            return response.text();  // Get the raw response
        })
        .then(text => {
            console.log('Raw response:', text);  // Log for debugging

            let data;
            try {
                data = JSON.parse(text);  // Parse JSON
            } catch (error) {
                console.error('Error parsing JSON:', error);
                loginError.textContent = 'An error occurred. Please try again later.';
                loginError.style.display = 'block';
                return;
            }

            if (data.error) {
                loginError.textContent = data.error;
                loginError.style.display = 'block';
            } else if (data.success) {
                window.location.href = 'residency.html';  // Redirect on success
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            loginError.textContent = 'An error occurred. Please try again later.';
            loginError.style.display = 'block';
        });
    });
});
