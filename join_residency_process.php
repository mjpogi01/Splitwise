<?php
session_start();
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "splitwise_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $residency_id = $_POST['residency_id'];
    $password = $_POST['residency_password'];
    $user_id = $_SESSION['user_id'];  // Assuming user is logged in and their ID is in session

    // Check if the residency exists
    $stmt = $conn->prepare("SELECT * FROM residencies WHERE residency_id = ?");
    $stmt->bind_param("i", $residency_id);
    $stmt->execute();
    $residency = $stmt->get_result()->fetch_assoc();

    if (!$residency) {
        echo json_encode(['success' => false, 'message' => 'No residency exists with this ID']);
        exit();
    }

    // Verify the password
    if (!password_verify($password, $residency['residency_password'])) {
        echo json_encode(['success' => false, 'message' => 'Incorrect password']);
        exit();
    }

    // Add the user to residency_members table
    $stmt = $conn->prepare("INSERT INTO residency_members (residency_id, user_id, role) VALUES (?, ?, 'member')");
    $stmt->bind_param("ii", $residency_id, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Joined residency successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error occurred. Please try again later.']);
    }

    $stmt->close();
    $conn->close();
}
?>
