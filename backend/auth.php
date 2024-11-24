<?php
require_once './database/connection.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $action = $_POST['action'];

    if ($action === 'signup') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];
        $medical_history = $_POST['medical_history'];

        $query = "INSERT INTO users (username, password, role, medical_history) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $username, $password, $role, $medical_history);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Signup successful"]);
        } else {
            echo json_encode(["error" => "Error signing up: " . $stmt->error]);
        }
    } elseif ($action === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT id, password, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                echo json_encode(["message" => "Login successful", "role" => $user['role']]);
            } else {
                echo json_encode(["error" => "Invalid password"]);
            }
        } else {
            echo json_encode(["error" => "User not found"]);
        }
    }
}
?>
