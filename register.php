<?php

header("Content-Type: application/json; charset=UTF-8");

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "db.php";

/* CHECK DB CONNECTION */
if (!isset($conn) || $conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

/* GET JSON INPUT */
$data = json_decode(file_get_contents("php://input"), true);

/* VALIDATE JSON */
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON input"]);
    exit;
}

/* VALIDATE FIELDS */
$name = trim($data["name"] ?? "");
$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

if ($name === "" || $email === "" || $password === "") {
    http_response_code(400);
    echo json_encode(["error" => "Missing fields"]);
    exit;
}

/* CHECK EMAIL EXISTS */
$check = $conn->prepare("SELECT id FROM members WHERE email = ? LIMIT 1");

if (!$check) {
    http_response_code(500);
    echo json_encode(["error" => "Prepare failed (check email)"]);
    exit;
}

$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["error" => "Email already exists"]);
    exit;
}

$check->close();

/* HASH PASSWORD */
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

/* INSERT USER (IMPORTANT: column is `password`, NOT password_hash) */
$insert = $conn->prepare("
    INSERT INTO members (name, email, password, role)
    VALUES (?, ?, ?, 'member')
");

if (!$insert) {
    http_response_code(500);
    echo json_encode(["error" => "Prepare failed (insert)"]);
    exit;
}

$insert->bind_param("sss", $name, $email, $hashedPassword);

/* EXECUTE */
if ($insert->execute()) {
    http_response_code(201);
    echo json_encode([
        "success" => true,
        "message" => "Registered successfully"
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "error" => "Insert failed",
        "details" => $insert->error
    ]);
}

$insert->close();
$conn->close();