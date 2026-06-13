<?php
header("Content-Type: application/json");
require "db.php";

$sql = "
SELECT 
    loans.id,
    members.full_name AS member_name,
    books.title AS book_title,
    loans.borrow_date,
    loans.due_date,
    loans.return_date,
    loans.status,
    loans.fine
FROM loans
JOIN members ON loans.member_id = members.id
JOIN books ON loans.book_id = books.id
ORDER BY loans.id DESC
";

$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);