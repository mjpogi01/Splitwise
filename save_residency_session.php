<?php
session_start();

// Save residency form data in session
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['residency_name'] = $_POST['residency_name'];
    $_SESSION['residency_address'] = $_POST['residency_address'];
    $_SESSION['residency_password'] = password_hash($_POST['residency_password'], PASSWORD_DEFAULT);

    // Redirect to the payment page
    header("Location: payment_method.html");
    exit();
}
?>
