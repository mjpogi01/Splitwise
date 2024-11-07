document.getElementById("joinForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
        const response = await fetch('join_residency_process.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            // If success, display the success message
            document.getElementById("message").textContent = result.message;
            document.getElementById("message").style.display = 'block';  // Ensure it's visible
        } else {
            // Display the error message
            document.getElementById("error-message").textContent = result.message;
            document.getElementById("error-message").style.display = 'block';
        }
    } catch (error) {
        console.error("Error:", error);
        document.getElementById("error-message").textContent = "An error occurred. Please try again later.";
        document.getElementById("error-message").style.display = 'block';
    }
});
