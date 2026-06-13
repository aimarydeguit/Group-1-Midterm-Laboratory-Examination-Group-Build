<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "bookhive"
);

if ($conn->connect_error) {
    die("Database error: " . $conn->connect_error);
}

?>