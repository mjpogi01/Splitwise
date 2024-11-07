document.addEventListener("DOMContentLoaded", function () {
    const paymentMethod = document.getElementById("payment_method");
    const paymentDetails = document.getElementById("paymentDetails");
    const paymentForm = document.getElementById("paymentForm");
    const errorMessage = document.getElementById("error-message");

    // Update payment details fields based on selected method
    paymentMethod.addEventListener("change", function () {
        const method = paymentMethod.value;
        paymentDetails.innerHTML = ""; // Clear existing fields
        errorMessage.style.display = "none"; // Hide error message

        if (method === "card") {
            paymentDetails.innerHTML = `
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" required>
                <label for="expiry_date">Expiry Date:</label>
                <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>
            `;
        } else if (method === "gcash" || method === "paymaya") {
            paymentDetails.innerHTML = `
                <label for="mobile_number">Mobile Number:</label>
                <input type="text" id="mobile_number" name="mobile_number" required>
            `;
        } else if (method === "paypal") {
            paymentDetails.innerHTML = `
                <label for="paypal_email">PayPal Email:</label>
                <input type="email" id="paypal_email" name="paypal_email" required>
            `;
        }
    });

    // Handle form submission with validation
    paymentForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission
        errorMessage.style.display = "none"; // Hide previous error messages

        // Check if a payment method is selected
        if (!paymentMethod.value) {
            errorMessage.textContent = "Please select a payment method.";
            errorMessage.style.display = "block";
            return;
        }

        // Validate required fields based on the selected payment method
        const method = paymentMethod.value;
        let isValid = true;

        if (method === "card") {
            isValid = document.getElementById("card_number").value &&
                      document.getElementById("expiry_date").value &&
                      document.getElementById("cvv").value;
        } else if (method === "gcash" || method === "paymaya") {
            isValid = document.getElementById("mobile_number").value;
        } else if (method === "paypal") {
            isValid = document.getElementById("paypal_email").value;
        }

        // If any required field is missing, show an error message
        if (!isValid) {
            errorMessage.textContent = "Please fill in all required payment details.";
            errorMessage.style.display = "block";
            return;
        }

        // If all validations pass, submit the form via AJAX
        const formData = new FormData(paymentForm);
        
        fetch('create_residency.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.redirect_url) {
                // Redirect to success page with residence ID
                window.location.href = data.redirect_url;
            } else {
                // Display an error message if creation failed
                errorMessage.textContent = data.message || 'An error occurred';
                errorMessage.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorMessage.textContent = 'An unexpected error occurred';
            errorMessage.style.display = 'block';
        });
    });
});
