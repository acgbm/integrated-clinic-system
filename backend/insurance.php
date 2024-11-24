<?php
require_once './database/connection.php';

header('Content-Type: application/json');

session_start();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $query = "SELECT * FROM insurance WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $insurance = [];
    while ($row = $result->fetch_assoc()) {
        $insurance[] = $row;
    }

    echo json_encode($insurance);
} elseif ($method === 'POST') {
    $policy_number = $_POST['policy_number'];
    $coverage = $_POST['coverage'];

    $query = "INSERT INTO insurance (user_id, policy_number, coverage) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $_SESSION['user_id'], $policy_number, $coverage);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Insurance added successfully"]);
    } else {
        echo json_encode(["error" => "Error adding insurance: " . $stmt->error]);
    }
}
?>
