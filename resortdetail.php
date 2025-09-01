<?php
session_start();
error_reporting(0);
require 'includes/database.php';
$property_id = $_GET['id'];

if (isset($_GET['id'])) {
    $property_id = $_GET['id'];
    $property_type = "resort";

    // Fetch reviews from the respective table
    $query = "SELECT * FROM resort_reviews WHERE property_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
} else {
    die("Property ID or type is missing.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resorts - Book My Stay</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" rel="stylesheet">
</head>
<body>
  <?php include './includes/header.php'; ?>

    <?php
    if (isset($_GET['id'])) {
        $hotelId = $_GET['id'];
        // Connect to the database and fetch details
        $query = "SELECT * FROM resorts1 WHERE id = $hotelId";
        $result = mysqli_query($conn, $query);
        $hotel = mysqli_fetch_assoc($result);

        $sql = "SELECT * FROM nearby_resort WHERE resort_id = $hotelId";
        $result = mysqli_query($conn, $sql);
        $nearby_hotel = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if ($hotel) {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title><?php echo htmlspecialchars($hotel['name']); ?></title>
                <link rel="stylesheet" href="styles1.css">
            </head>
            <body>
                
                    <div class="hotel-details">
                        <div class="hotel-image">
                            <img src="<?php echo htmlspecialchars($hotel['image_url']); ?>" alt="<?php echo htmlspecialchars($hotel['name']); ?>">
                            </div>
                        <div class="hotel-name">
                            <h1><?php echo htmlspecialchars($hotel['name']); ?></h1>
                        </div>
                        <div class="book-now-btn">
                                <a href="rooms1.php?id=<?php echo $hotelId; ?>"><button>View Rooms</button></a>
                            </div>
                        
                        <div class="hotel-info">
                            <h2>Starting ₹<?php echo number_format($hotel['price'], 2); ?> per night</h2>
                            <ul>
                                <li><strong>Features:</strong> <?php echo htmlspecialchars($hotel['features']); ?></li>
                                <li><strong>Facilities:</strong> <?php echo htmlspecialchars($hotel['facilities']); ?></li>
                                <li><strong>Guests:</strong> <?php echo htmlspecialchars($hotel['guest_capacity']); ?></li>
                                <li><strong>Area:</strong> <?php echo htmlspecialchars($hotel['area']); ?> sq. ft.</li>
                            </ul>
                            
                        </div>
                        <div class="hotel-description">
                            <h3>Description</h3>
                            <p><?php echo htmlspecialchars($hotel['description']); ?></p>
                        </div>
                        <div class="map">
                            <iframe 
                            src="<?php echo htmlspecialchars($hotel['iframe']); ?>" 
                            allowfullscreen="" loading="lazy">
                            </iframe>
                        </div>
                    </div>
                    <div class="container">
                        <h2>Reviews</h2>

                        <?php if (!empty($reviews)) : ?>
                            <?php foreach ($reviews as $review) : ?>
                                <div class="review">
                                    <strong><?= htmlspecialchars($review['username']); ?></strong> - ⭐<?= $review['rating']; ?>
                                    <p><?= htmlspecialchars($review['review']); ?></p>
                                    <small><?= $review['created_at']; ?></small>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No reviews yet.</p>
                        <?php endif; ?>
                    </div>
                    <div class="nearby-hotels">
                        <h3>Nearby Places</h3>
                        <ul>
                            <?php foreach ($nearby_hotel as $nearby) { ?>
                                <li>
                                    <img src="<?php echo htmlspecialchars($nearby['image_url']); ?>" alt="<?php echo htmlspecialchars($nearby['name']); ?>">
                                    <span>
                                        <strong><?php echo htmlspecialchars($nearby['name']); ?></strong>
                                        <div class="description"><?php echo htmlspecialchars($nearby['description']); ?></div>
                                        <div class="location">(<?php echo htmlspecialchars($nearby['location']); ?>)</div>
                                    </span>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

            </body>
            </html>
            <?php
                } else {
                    echo "<p>Resort details not found.</p>";
                }
            } else {
                echo "<p>Invalid resort ID.</p>";
            }
            mysqli_close($conn);
            ?>

  <?php include './includes/footer.php'; ?>
</body>
</html>