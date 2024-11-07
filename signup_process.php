<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "splitwise_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Log error to a file for security
    error_log("Database Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        header("Location: signup.html?password_mismatch=1");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check for existing email or username
    $check_query = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Flags for email/username existence
    $email_exists = false;
    $username_exists = false;

    // Check for existing email/username
    while ($row = $result->fetch_assoc()) {
        if ($row['email'] === $email) $email_exists = true;
        if ($row['username'] === $username) $username_exists = true;
    }

    if ($email_exists || $username_exists) {
        $redirect_url = "signup.html?";
        if ($email_exists) $redirect_url .= "email_exists=1&";
        if ($username_exists) $redirect_url .= "username_exists=1";
        header("Location: $redirect_url");
        exit();
    }

    // Insert user into the database
    $insert_query = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sss", $email, $username, $hashed_password);

    if ($stmt->execute()) {
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
