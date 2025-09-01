<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "bookmyroom");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch images
$sql = "SELECT image_url FROM carousel_images";
$result = $conn->query($sql);

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row['image_url'];
}

echo json_encode($images);

$conn->close();
?>
