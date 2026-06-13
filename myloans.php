<?php
header("Content-Type: application/json");
require "db.php";

$user_id = $_GET['user_id'];

$stmt = $conn->prepare("
    SELECT
        loans.book_id,
        books.title,
        COUNT(*) AS copies_borrowed,
        MIN(loans.borrow_date) AS borrow_date,
        MAX(loans.due_date) AS due_date
    FROM loans
    INNER JOIN books ON loans.book_id = books.id
    WHERE loans.member_id = ?
    AND loans.status = 'active'
    GROUP BY loans.book_id, books.title
    ORDER BY books.title
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$loans = [];

while ($row = $result->fetch_assoc()) {
    $loans[] = $row;
}

echo json_encode($loans);
?>