<?php

header("Content-Type: application/json");

require "db.php";

$user_id = $_GET['user_id'] ?? 1;

$stmt = $conn->prepare("
    SELECT *
    FROM loan_history
    WHERE user_id = ?
    ORDER BY transaction_date DESC
");

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()){

    $data[] = $row;

}

echo json_encode($data);