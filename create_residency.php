<?php
session_start();
header('Content-Type: application/json'); 

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "splitwise_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve residency data from the session
    $residency_name = $_SESSION['residency_name'];
    $residency_address = $_SESSION['residency_address'];
    $residency_password = $_SESSION['residency_password'];
    $creator_id = $_SESSION['user_id'];

    // Retrieve payment method data
    $payment_method = $_POST['payment_method'];
    $payment_details = json_encode($_POST); // Store details in JSON format for flexibility

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert the residency
        $stmt = $conn->prepare("INSERT INTO residencies (residency_name, residency_address, residency_password, creator_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $residency_name, $residency_address, $residency_password, $creator_id);
        $stmt->execute();
        $residency_id = $stmt->insert_id;

        // Insert the payment method
        $stmt2 = $conn->prepare("INSERT INTO residency_payments (residency_id, payment_method, payment_details) VALUES (?, ?, ?)");
        $stmt2->bind_param("iss", $residency_id, $payment_method, $payment_details);
        $stmt2->execute();

        // Commit the transaction
        $conn->commit();

        // Return the success message and redirect URL as JSON
        echo json_encode([
            'success' => true,
            'message' => 'Residency created successfully',
            'redirect_url' => 'create_success.html?residence_id=' . $residency_id
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }

    $stmt->close();
    $stmt2->close();
    $conn->close();
}
?>
