<?php
require_once './database/connection.php';

header('Content-Type: application/json');

session_start();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $query = "SELECT * FROM billing WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $bills = [];
    while ($row = $result->fetch_assoc()) {
        $bills[] = $row;
    }

    echo json_encode($bills);
} elseif ($method === 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $amount = $_POST['amount'];

    $query = "INSERT INTO billing (appointment_id, user_id, amount) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $appointment_id, $_SESSION['user_id'], $amount);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Bill added successfully"]);
    } else {
        echo json_encode(["error" => "Error adding bill: " . $stmt->error]);
    }
}
?>
