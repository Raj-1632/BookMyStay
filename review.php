<?php
session_start();
error_reporting(0);
include 'includes/database.php'; 
include 'includes/header.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required POST fields exist
    if (!isset($_POST['booking_id'], $_POST['booking_type'])) {
        die("Error: Missing required fields.");
    }

    // Retrieve and sanitize input values
    $booking_id = htmlspecialchars($_POST['booking_id']);
    $booking_type = htmlspecialchars($_POST['booking_type']);
    $username = $_SESSION['username'];

    // Determine the table names
    $booking_table = '';
    $property_table = '';
    $review_table = '';
    $name = '';

    if ($booking_type === 'Hotel') {
        $booking_table = 'hotel_booking';
        $property_table = 'hotels1';
        $review_table = 'hotel_reviews';
        $name = 'hname';
    } elseif ($booking_type === 'Resort') {
        $booking_table = 'resort_booking';
        $property_table = 'resorts1';
        $review_table = 'resort_reviews';
        $name = 'rname';
    } elseif ($booking_type === 'Villa') {
        $booking_table = 'villa_booking';
        $property_table = 'villas1';
        $review_table = 'villa_reviews';
        $name = 'vname';
    } else {
        die("Error: Invalid booking type.");
    }

    // Fetch user ID using username
    $stmt = $conn->prepare("SELECT user_id FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if (!$user_id) {
        die("Error: User not found.");
    }

    // Fetch property name using booking_id
    $stmt = $conn->prepare("SELECT $name FROM $booking_table WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->bind_result($property_name);
    $stmt->fetch();
    $stmt->close();

    if (!$property_name) {
        die("Error: Property not found for this booking.");
    }

    // Fetch property_id using property name
    $stmt1 = $conn->prepare("SELECT id FROM $property_table WHERE name = ?");
    $stmt1->bind_param("s", $property_name);
    $stmt1->execute();
    $stmt1->bind_result($property_id);
    $stmt1->fetch();
    $stmt1->close();

    if (!$property_id) {
        die("Error: Property ID not found.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Your Review</title>
    <link rel="stylesheet" href="review.css"> <!-- External CSS file -->
</head>
<body>

<div class="review-container">
    <h2>Submit Your Review</h2>
    <form method="POST" action="submit_review.php">
        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($_POST['booking_id'] ?? ''); ?>">
        <input type="hidden" name="booking_type" value="<?php echo htmlspecialchars($_POST['booking_type'] ?? ''); ?>">

        <label class="form-label">Rating:</label>
        <select name="rating" class="form-input" required>
            <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
            <option value="4">⭐⭐⭐⭐ - Good</option>
            <option value="3">⭐⭐⭐ - Average</option>
            <option value="2">⭐⭐ - Poor</option>
            <option value="1">⭐ - Terrible</option>
        </select>

        <label class="form-label">Your Review:</label>
        <textarea name="review" class="form-input" rows="4" placeholder="Write your review here..." required></textarea>

        <button type="submit" class="btn-submit">Submit Review</button>
    </form>
</div>

</body>
</html>

<?php include 'includes/footer.php'; ?>
