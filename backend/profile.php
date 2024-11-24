<?php
require_once './database/connection.php';

header('Content-Type: application/json');

session_start();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $user_id = $_SESSION['user_id'];

    $query = "SELECT id, username, medical_history, insurance_status FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "User not found"]);
    }
} elseif ($method === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = $_POST['username'];
    $medical_history = $_POST['medical_history'];
    $insurance_status = $_POST['insurance_status'];

    $query = "UPDATE users SET username = ?, medical_history = ?, insurance_status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $username, $medical_history, $insurance_status, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Profile updated successfully"]);
    } else {
        echo json_encode(["error" => "Error updating profile: " . $stmt->error]);
    }
}
?>
