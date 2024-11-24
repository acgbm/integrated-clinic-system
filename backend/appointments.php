<?php
require_once './database/connection.php';

header('Content-Type: application/json');

session_start();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $role = $_SESSION['role'];
    $user_id = $_SESSION['user_id'];

    if ($role === 'admin') {
        $query = "SELECT * FROM appointments";
    } else {
        $query = "SELECT * FROM appointments WHERE user_id = ?";
    }

    $stmt = $conn->prepare($query);

    if ($role !== 'admin') {
        $stmt->bind_param("i", $user_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    echo json_encode($appointments);
} elseif ($method === 'POST') {
    $user_id = $_SESSION['user_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    $query = "INSERT INTO appointments (user_id, appointment_date, appointment_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $appointment_date, $appointment_time);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Appointment booked successfully"]);
    } else {
        echo json_encode(["error" => "Error booking appointment: " . $stmt->error]);
    }
}
?>
