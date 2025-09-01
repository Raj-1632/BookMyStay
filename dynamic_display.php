<?php
include 'includes/database.php';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging: Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Fetch top 3 most booked hotels
$hotelQuery = "SELECT h.id, h.name, h.image_url, h.description 
               FROM hotels1 h
               JOIN (
                   SELECT hname, COUNT(*) AS booking_count 
                   FROM hotel_booking 
                   GROUP BY hname 
                   ORDER BY booking_count DESC 
                   LIMIT 3
               ) hb ON h.name = hb.hname
               ORDER BY booking_count DESC
               LIMIT 3";

$hotels = $conn->query($hotelQuery) or die("Hotel Query Failed: " . $conn->error);

// Fetch top 3 most booked resorts
$resortQuery = "SELECT r.id, r.name, r.image_url, r.description 
                FROM resorts1 r
                JOIN (
                    SELECT rname, COUNT(*) AS booking_count 
                    FROM resort_booking 
                    GROUP BY rname
                    ORDER BY booking_count DESC 
                    LIMIT 3
                ) rb ON r.name = rb.rname
                ORDER BY booking_count DESC
               LIMIT 3";

$resorts = $conn->query($resortQuery) or die("Resort Query Failed: " . $conn->error);

// Fetch top 3 most booked villas
$villaQuery = "SELECT v.id, v.name, v.image_url, v.description 
               FROM villas1 v
               JOIN (
                   SELECT vname, COUNT(*) AS booking_count 
                   FROM villa_booking 
                   GROUP BY vname
                   ORDER BY booking_count DESC 
                   LIMIT 3
               ) vb ON v.name = vb.vname
               ORDER BY booking_count DESC
               LIMIT 3";

$villas = $conn->query($villaQuery) or die("Villa Query Failed: " . $conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Hotels, Resorts & Villas</title>
    <style>
        .container { width: 90%; margin: auto; }
        .hotel-title, .resort-title, .villa-title { text-align: center; }
        .hotels-container, .resorts-container, .villas-container { display: flex; gap: 20px; justify-content: center; }
        .hotel-card, .resort-card, .villa-card { border: 1px solid #ddd; padding: 10px; width: 300px; text-align: center; }
        img { width: 100%; height: 200px; object-fit: cover; }
        .hotel-btn, .resort-btn, .villa-btn { background: blue; color: white; padding: 5px 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <!-- Hotels Section -->
    <h1 class="hotel-title">Popular Hotels</h1>
    <div class="hotels-container">
        <?php 
        if ($hotels->num_rows > 0) {
            while ($hotel = $hotels->fetch_assoc()): ?>
                <div class="hotel-card">
                    <img src="<?php echo htmlspecialchars($hotel['image_url']); ?>" alt="Hotel Image">
                    <div class="hotel-info">
                        <h2><?php echo htmlspecialchars($hotel['name']); ?></h2>
                        <p><?php echo htmlspecialchars($hotel['description']); ?></p>
                        <a href="hoteldetail.php?id=<?php echo htmlspecialchars($hotel['id']); ?>">
                            <button class="hotel-btn">View Details</button>
                        </a>
                    </div>
                </div>
        <?php endwhile; 
        } else {
            echo "<p>No hotels found</p>";
        } ?>
    </div>

    <!-- Resorts Section -->
    <h1 class="resort-title">Popular Resorts</h1>
    <div class="resorts-container">
        <?php 
        if ($resorts->num_rows > 0) {
            while ($resort = $resorts->fetch_assoc()): ?>
                <div class="resort-card">
                    <img src="<?php echo htmlspecialchars($resort['image_url']); ?>" alt="Resort Image">
                    <div class="resort-info">
                        <h2><?php echo htmlspecialchars($resort['name']); ?></h2>
                        <p><?php echo htmlspecialchars($resort['description']); ?></p>
                        <a href="resortdetail.php?id=<?php echo htmlspecialchars($resort['id']); ?>">
                            <button class="resort-btn">View Details</button>
                        </a>
                    </div>
                </div>
        <?php endwhile; 
        } else {
            echo "<p>No resorts found</p>";
        } ?>
    </div>

    <!-- Villas Section -->
    <h1 class="villa-title">Popular Villas</h1>
    <div class="villas-container">
        <?php 
        if ($villas->num_rows > 0) {
            while ($villa = $villas->fetch_assoc()): ?>
                <div class="villa-card">
                    <img src="<?php echo htmlspecialchars($villa['image_url']); ?>" alt="Villa Image">
                    <div class="villa-info">
                        <h2><?php echo htmlspecialchars($villa['name']); ?></h2>
                        <p><?php echo htmlspecialchars($villa['description']); ?></p>
                        <a href="villadetail.php?id=<?php echo htmlspecialchars($villa['id']); ?>">
                            <button class="villa-btn">View Details</button>
                        </a>
                    </div>
                </div>
        <?php endwhile; 
        } else {
            echo "<p>No villas found</p>";
        } ?>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
