<?php
header("Content-Type: application/json");
require "db.php";

$sql = "
SELECT
    members.name AS member_name,
    books.title AS book_title,
    loans.borrow_date,
    loans.due_date,
    loans.return_date,
    loans.status,
    loans.fine
FROM loans
INNER JOIN members
    ON loans.member_id = members.id
INNER JOIN books
    ON loans.book_id = books.id
WHERE members.role = 'member'
ORDER BY loans.id DESC";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => $conn->error]);
    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);