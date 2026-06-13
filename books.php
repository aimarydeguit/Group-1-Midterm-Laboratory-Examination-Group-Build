<?php
header("Content-Type: application/json");
require "db.php";

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

/* =========================
   GET ALL BOOKS
========================= */
if ($method === "GET" && !$id) {
    $result = $conn->query("SELECT * FROM books");
    $books = [];

    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    echo json_encode($books);
    exit;
}

/* =========================
   GET SINGLE BOOK
========================= */
if ($method === "GET" && $id) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book) {
        echo json_encode($book);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Book not found"]);
    }
    exit;
}

/* =========================
   CREATE BOOK (POST)
========================= */
if ($method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conn->prepare("
        INSERT INTO books (title, author, category, total_copies, available_copies)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssii",
        $data['title'],
        $data['author'],
        $data['category'],
        $data['total_copies'],
        $data['available_copies']
    );

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Book created"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Insert failed"]);
    }
    exit;
}

/* =========================
   UPDATE BOOK (PUT)
========================= */
if ($method === "PUT" && $id) {
    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conn->prepare("
        UPDATE books 
        SET title=?, author=?, category=?, total_copies=?, available_copies=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "sssiii",
        $data['title'],
        $data['author'],
        $data['category'],
        $data['total_copies'],
        $data['available_copies'],
        $id
    );

    if ($stmt->execute()) {
        echo json_encode(["message" => "Book updated"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Update failed"]);
    }
    exit;
}

/* =========================
   DELETE BOOK
========================= */
if ($method === "DELETE" && $id) {
    $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        http_response_code(204);
        echo json_encode(["message" => "Deleted"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Delete failed"]);
    }
    exit;
}

/* =========================
   INVALID REQUEST
========================= */
http_response_code(405);
echo json_encode(["error" => "Method not allowed"]);
?>