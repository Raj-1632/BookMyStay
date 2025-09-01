<?php
$conn = new mysqli("localhost", "root", "", "bookmyroom");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
