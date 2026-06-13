<?php

include "db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

/* =========================
   VALIDATION
========================= */
if (!isset($data['book_id']) || !isset($data['member_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing book_id or member_id"]);
    exit;
}

$book_id = (int)$data['book_id'];
$member_id = (int)$data['member_id'];

/* =========================
   FIND ACTIVE LOAN
========================= */
$stmt = $conn->prepare("
    SELECT id, due_date
    FROM loans
    WHERE book_id = ?
    AND member_id = ?
    AND status = 'active'
    LIMIT 1
");

$stmt->bind_param("ii", $book_id, $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "error" => "No active loan found"
    ]);
    exit;
}

$loan = $result->fetch_assoc();
$loan_id = $loan['id'];
$due_date = $loan['due_date'];

/* =========================
   CALCULATE FINE
========================= */
$return_date = date("Y-m-d");
$fine = 0;

if (strtotime($return_date) > strtotime($due_date)) {
    $daysLate = floor(
        (strtotime($return_date) - strtotime($due_date)) / 86400
    );

    $fine = $daysLate * 5;
}

/* =========================
   START UPDATE
========================= */
$conn->begin_transaction();

try {

    /* UPDATE LOAN */
    $updateLoan = $conn->prepare("
        UPDATE loans
        SET status = 'returned',
            return_date = ?,
            fine = ?
        WHERE id = ?
    ");

    $updateLoan->bind_param("sii", $return_date, $fine, $loan_id);
    $updateLoan->execute();

    /* RESTORE STOCK */
    $updateBook = $conn->prepare("
        UPDATE books
        SET available_copies = available_copies + 1
        WHERE id = ?
    ");

    $updateBook->bind_param("i", $book_id);
    $updateBook->execute();

    /* GET TITLE */
    $stmtBook = $conn->prepare("
        SELECT title FROM books WHERE id = ?
    ");

    $stmtBook->bind_param("i", $book_id);
    $stmtBook->execute();

    $book = $stmtBook->get_result()->fetch_assoc();
    $title = $book['title'] ?? "Unknown";

    /* SAVE HISTORY (FIXED TABLE NAME) */
    $history = $conn->prepare("
        INSERT INTO loan_transactions
        (member_id, book_id, book_title, action)
        VALUES (?, ?, ?, 'RETURN')
    ");

    $history->bind_param("iis", $member_id, $book_id, $title);
    $history->execute();

    $conn->commit();

    echo json_encode([
        "message" => "Book returned successfully",
        "fine" => $fine
    ]);

} catch (Exception $e) {

    $conn->rollback();

    http_response_code(500);
    echo json_encode([
        "error" => "Return failed",
        "details" => $e->getMessage()
    ]);
}