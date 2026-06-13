<?php
session_start();
header("Content-Type: application/json");
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email'], $data['password'], $data['role'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing email, password, or role"]);
    exit;
}

$email = $data['email'];
$password = $data['password'];
$role = $data['role']; // IMPORTANT FIX

/* GET USER */
$stmt = $conn->prepare("SELECT id, name, role, password FROM members WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

/* CHECK EMAIL */
if (!$user) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid email or password"]);
    exit;
}

/* CHECK PASSWORD */
if (!password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid email or password"]);
    exit;
}

/* CHECK ROLE (🔥 IMPORTANT FIX) */
if ($user['role'] !== $role) {
    http_response_code(403);
    echo json_encode([
        "error" => "Wrong account role. You are registered as: " . $user['role']
    ]);
    exit;
}

/* SESSION LOGIN */
$_SESSION['user_id'] = $user['id'];
$_SESSION['name'] = $user['name'];
$_SESSION['role'] = $user['role'];

echo json_encode([
    "success" => true,
    "user" => [
        "id" => $user['id'],
        "name" => $user['name'],
        "role" => $user['role']
    ]
]);