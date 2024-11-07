<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');  // Ensure JSON response header

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "splitwise_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Prepare the statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    if ($stmt === false) {
        echo json_encode(['error' => 'Failed to prepare statement.']);
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Verify the password hash
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;

            // Return JSON indicating success
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Invalid username or password!']);
        }
    } else {
        echo json_encode(['error' => 'Invalid username or password!']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid request method.']);
    exit();
}
?>
