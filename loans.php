<?php
session_start();
header("Content-Type: application/json");
require "db.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];


// ======================
// BORROW BOOK
// ======================
if ($method === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['book_id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing book_id"]);
        exit;
    }

    $book_id = $data['book_id'];
    $member_id = $_SESSION['user_id'];

    // CHECK BOOK
    $check = $conn->prepare("SELECT * FROM books WHERE id=?");
    $check->bind_param("i", $book_id);
    $check->execute();
    $book = $check->get_result()->fetch_assoc();

    if (!$book) {
        http_response_code(404);
        echo json_encode(["error" => "Book not found"]);
        exit;
    }

    if ($book['available_copies'] <= 0) {
        http_response_code(409);
        echo json_encode(["error" => "No copies available"]);
        exit;
    }

    $conn->begin_transaction();

    try {

        // decrease stock
        $update = $conn->prepare("
            UPDATE books
            SET available_copies = available_copies - 1
            WHERE id=?
        ");
        $update->bind_param("i", $book_id);
        $update->execute();

        // dates
        $borrow_date = date("Y-m-d");
        $due_date = date("Y-m-d", strtotime("+7 days"));

        // insert loan
        $insert = $conn->prepare("
            INSERT INTO loans
            (book_id, member_id, borrow_date, due_date, status)
            VALUES (?, ?, ?, ?, 'active')
        ");

        $insert->bind_param(
            "iiss",
            $book_id,
            $member_id,
            $borrow_date,
            $due_date
        );

        $insert->execute();

        // GET TITLE (FIXED)
        $stmtBook = $conn->prepare("SELECT title FROM books WHERE id=?");
        $stmtBook->bind_param("i", $book_id);
        $stmtBook->execute();
        $title = $stmtBook->get_result()->fetch_assoc()['title'];

        // LOG TRANSACTION (FIXED)
        $log = $conn->prepare("
            INSERT INTO loan_transactions
            (member_id, book_id, book_title, action)
            VALUES (?, ?, ?, 'BORROW')
        ");

        $log->bind_param("iis", $member_id, $book_id, $title);
        $log->execute();

        $conn->commit();

        echo json_encode([
            "message" => "Borrow successful",
            "due_date" => $due_date
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }

    exit;
}


// ======================
// RETURN BOOK
// ======================
if ($method === "PUT") {

    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(["error" => "Missing loan id"]);
        exit;
    }

    $loan = $conn->prepare("SELECT * FROM loans WHERE id=?");
    $loan->bind_param("i", $id);
    $loan->execute();
    $data = $loan->get_result()->fetch_assoc();

    if (!$data) {
        http_response_code(404);
        echo json_encode(["error" => "Loan not found"]);
        exit;
    }

    $return_date = date("Y-m-d");

    // FINE ₱5 per day
    $daysLate = max(0, floor((strtotime($return_date) - strtotime($data['due_date'])) / 86400));
    $fine = $daysLate * 5;

    // update loan
    $update = $conn->prepare("
        UPDATE loans
        SET return_date=?, status='returned', fine=?
        WHERE id=?
    ");
    $update->bind_param("sdi", $return_date, $fine, $id);
    $update->execute();

    // restore stock
    $restore = $conn->prepare("
        UPDATE books
        SET available_copies = available_copies + 1
        WHERE id=?
    ");
    $restore->bind_param("i", $data['book_id']);
    $restore->execute();

    // GET TITLE
    $stmtBook = $conn->prepare("SELECT title FROM books WHERE id=?");
    $stmtBook->bind_param("i", $data['book_id']);
    $stmtBook->execute();
    $title = $stmtBook->get_result()->fetch_assoc()['title'];

    // LOG RETURN
    $log = $conn->prepare("
        INSERT INTO loan_transactions
        (member_id, book_id, book_title, action)
        VALUES (?, ?, ?, 'RETURN')
    ");

    $log->bind_param("iis", $data['member_id'], $data['book_id'], $title);
    $log->execute();

    echo json_encode([
        "message" => "Returned successfully",
        "fine" => $fine
    ]);

    exit;
}


// ======================
// GET LOANS
// ======================
if ($method === "GET") {

    $member_id = $_GET['member_id'] ?? null;

    $sql = "
        SELECT 
            loans.id,
            books.title,
            loans.borrow_date,
            loans.due_date,
            loans.return_date,
            loans.status,
            loans.fine
        FROM loans
        JOIN books ON loans.book_id = books.id
    ";

    if ($member_id) {
        $sql .= " WHERE loans.member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Invalid request"]);