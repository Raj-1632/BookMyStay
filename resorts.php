<?php
session_start();
error_reporting(0);
require 'includes/database.php';


$filter_location = $_GET['location'] ?? '';
$filter_sort_price = $_GET['sort_price'] ?? '';
$filter_checkin = $_GET['checkin'] ?? '';
$filter_checkout = $_GET['checkout'] ?? '';


$query = "SELECT h.id, h.name, h.location, h.image_url, h.price 
          FROM resorts1 h
          WHERE 1=1";
$params = [];

// Location filter
if (!empty($filter_location)) {
    $query .= " AND h.location LIKE ?";
    $params[] = "%$filter_location%";
}

// Check availability filter
if (!empty($filter_checkin) && !empty($filter_checkout)) {
  $query .= " AND NOT EXISTS (
    SELECT 1 FROM resort_booking b
    WHERE b.rname = h.name
    AND b.checkin < ? AND b.checkout > ?
    GROUP BY b.room_id
    HAVING COUNT(*) >= 5
    )";
    $params[] = $filter_checkout;
    $params[] = $filter_checkin;
}

// Sorting by price
if ($filter_sort_price === "low_to_high") {
    $query .= " ORDER BY h.price ASC";
} elseif ($filter_sort_price === "high_to_low") {
    $query .= " ORDER BY h.price DESC";
}

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}

if (!$stmt->execute()) {
    die("Execution Error: " . $stmt->error);
}

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resorts - Book My Stay</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include './includes/header.php'; ?>

  <section class="section-hotels">
    <h1>Resorts</h1>
    <div class="main">
      <!-- Search Form -->
      <div class="search-bar-container1">
        <form action="resorts.php" method="GET">
          <div class="loca">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" placeholder="Enter location" value="<?= htmlspecialchars($filter_location) ?>">
          </div>
          <div>
            <label for="checkin">Check-in:</label>
            <input type="date" id="check_in_date" name="checkin" value="<?= htmlspecialchars($filter_checkin) ?>" min="<?= date('Y-m-d'); ?>">
            <br>
            <label for="checkout">Check-out:</label>
            <input type="date" id="check_out_date" name="checkout" value="<?= htmlspecialchars($filter_checkout) ?>" min="<?= date('Y-m-d'); ?>">
          </div>
          <script>
            // Prevent checkout date earlier than check-in date
            document.getElementById('check_in_date').addEventListener('change', function () {
                const checkInDate = this.value;
                document.getElementById('check_out_date').setAttribute('min', checkInDate);
            });
          </script>
          <div>
            <label>Sort By Price:</label>
            <div>
              <input type="radio" id="low_to_high" name="sort_price" value="low_to_high" <?= $filter_sort_price === "low_to_high" ? 'checked' : '' ?>>
              <label for="low_to_high">Low to High</label>
            </div>
            <div>
              <input type="radio" id="high_to_low" name="sort_price" value="high_to_low" <?= $filter_sort_price === "high_to_low" ? 'checked' : '' ?>>
              <label for="high_to_low">High to Low</label>
            </div>
          </div>
          <button type="submit" class="btn">Search</button>
          <a href="resorts.php" class="clear-btn">Clear</a>
        </form>
      </div>

      <!-- Resort List -->
      <div class="hotel-list">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($resort = $result->fetch_assoc()) {
                echo "
                <div class='hotel-item'>
                  <img src='" . htmlspecialchars($resort['image_url']) . "' alt='" . htmlspecialchars($resort['name']) . "'>
                  <h2>" . htmlspecialchars($resort['name']) . "</h2>
                  <p>" . htmlspecialchars($resort['location']) . "</p>
                  <a href='resortdetail.php?id=" . urlencode($resort['id']) . "'><button>View</button></a>
                </div>";
            }
        } elseif ($result) {
            echo "<p>No resorts available for the selected criteria.</p>";
        } else {
            echo "<p>Something went wrong. Please try again later.</p>";
        }
        ?>
      </div>
    </div>
  </section>

  <?php include './includes/footer.php'; ?>
</body>
</html>
