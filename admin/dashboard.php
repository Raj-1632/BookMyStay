<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$timeout_duration = 600; // 10 minutes

// Ensure user is logged in
if (isset($_SESSION['admin'])) {
    if (isset($_SESSION['last_activity'])) {
        $elapsed_time = time() - $_SESSION['last_activity'];
        
        if ($elapsed_time > $timeout_duration) {
            session_unset();
            session_destroy();
            echo "<script>alert('Session expired due to inactivity. Please log in again.'); window.location.href = 'index.php';</script>";
            exit();
        }
    }

    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
}


// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "bookmyroom") or die("Database & Server Error");

// Fetch data for dashboard analytics
$hotelBookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM hotel_booking"))['count'];
$villaBookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM villa_booking"))['count'];
$resortBookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM resort_booking"))['count'];
$totalBookings = $hotelBookings + $villaBookings + $resortBookings;

$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM user"))['count'];
$userQueriesCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM user_queries"))['count'];
$totalAdmin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM admin"))['count'];

$cabresort = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM cab_booking_resort"))['count'];
$cabhotel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM cab_booking_hotel"))['count'];
$totalcab = $cabhotel + $cabresort;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        .container {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr; /* Flexible columns */
        }
        /* For tablets and smaller screens */
        @media (max-width: 768px) {
        .sidebar {
             /* Hide sidebar on smaller screens */
        }
        }

        /* For mobile devices */
        @media (max-width: 480px) {
        body {
             font-size: 14px;
            }
        }
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
        }
        /* Sidebar */
        .sidebar {
            background-color: #333;
            color: #fff;
            padding: 20px;
            height: 100vh;
            position: sticky;
            top: 0;
            width: 210px;
        }
        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: block;
            color: #fff;
            padding: 10px;
            text-decoration: none;
            margin: 5px 0;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #575757;
        }
        .sidebar a, .dropdown-btn {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            outline: none;
        }
        .dropdown-btn::after {
            content: ' ▼';
            float: right;
        }
        .dropdown-container {
            display: none;
            padding-left: 15px;
            background: #444;
        }
        .sidebar a:hover, .dropdown-btn:hover {
            background: #555;
        }
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header .head {
            font-size: 24px;
            color: #333;
            text-decoration: none;
            position: relative;
        }
        .header a {
            text-decoration: none;
            color : black
        }
        .header .logout-btn {
            padding: 8px 16px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        /* Dashboard Cards */
        .card-container {
            display: grid;
            grid-template-columns: repeat(2,1fr);
            gap: 30px;
            margin-bottom: 30px;
        }
        .card {
            background-color: #fff;
            padding: 60px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card h3 {
            font-size: 25px;
            color: #333;
            margin-bottom: 10px;
        }
        .card h3 a{
            text-decoration: none;
            color: #333;
        }
        .card .count {
            font-size: 40px;
            font-weight: bold;
        }
        /* Colors for categories */
        .hotel-bookings { color: #007bff; }
        .villa-bookings { color: #28a745; }
        .resort-bookings { color: #ffc107; }
        .total-bookings { color: #17a2b8; }
        .total-users { color: #6c757d; }
        .user-queries { color: blue; }
        .total-cab{ color :rgb(29, 172, 117);}
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
    <h2>Book My Stay</h2>
    <a href="dashboard.php">Dashboard</a>

    <button class="dropdown-btn">Hotels</button>
    <div class="dropdown-container">
        <a href="dashboard.php?page=hotel.php">Hotel</a>
        <a href="dashboard.php?page=hotelrooms.php">Rooms</a>
        <a href="dashboard.php?page=hotelbooking.php">Bookings</a>
        <a href="dashboard.php?page=rpt.php">Booking Analysis</a>
        <a href="dashboard.php?page=cabhotel.php">Cab Booking</a>
        <a href="dashboard.php?page=hotel_review.php">Rating & Reviews</a>
        <a href="dashboard.php?page=nearbyhotel.php">Nearby Places</a>
    </div>

    <button class="dropdown-btn">Resorts</button>
    <div class="dropdown-container">
        <a href="dashboard.php?page=resort.php">Resort</a>
        <a href="dashboard.php?page=resortrooms.php">Rooms</a>
        <a href="dashboard.php?page=resortbooking.php">Booking</a>
        <a href="dashboard.php?page=rpt2.php">Booking Analysis</a>
        <a href="dashboard.php?page=cabresort.php">Cab Booking</a>
        <a href="dashboard.php?page=resort_review.php">Rating & Reviews</a>
        <a href="dashboard.php?page=nearbyresort.php">Nearby Places</a>
    </div>

    <button class="dropdown-btn">Villas</button>
    <div class="dropdown-container">
        <a href="dashboard.php?page=villa.php">Villa</a>
        <a href="dashboard.php?page=villabooking.php">Booking</a>
        <a href="dashboard.php?page=rpt3.php">Booking Analysis</a>
        <a href="dashboard.php?page=villa_review.php">Rating & Reviews</a>
        <a href="dashboard.php?page=nearbyvilla.php">Nearby Places</a>
    </div>

    <a href="dashboard.php?page=user.php">User</a>
    <a href="dashboard.php?page=userquery.php">User Queries</a>
    <a href="dashboard.php?page=manager.php">Manager</a>
    <a href="dashboard.php?page=team.php">Team</a>
    <a href="dashboard.php?page=admin.php">Admin</a>
</div>
    <script>
        document.querySelectorAll(".dropdown-btn").forEach(button => {
            button.addEventListener("click", function() {
                let dropdownContent = this.nextElementSibling;
                dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
            });
        });
    </script>
    <!-- Main Content -->
    <div class="main-content">
    <div class="header">
            <h1 class="head"><a href="dashboard.php">DASHBOARD</a></h1>
            <a href="logout.php" class="logout-btn">LOG OUT</a>
        </div>

        <?php
        // Load page dynamically based on 'page' parameter
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $allowedPages = ['dashboard.php', 'manager.php','hotel_review.php','resort_review.php','villa_review.php', 'rpt.php', 'rpt2.php','rpt3.php', 'carousel.php', 'hotel.php', 'hotelrooms.php', 'hotelbooking.php', 'resort.php', 'resortrooms.php', 'resortbooking.php', 'villa.php', 'villabooking.php', 'user.php', 'userquery.php', 'admin.php','nearbyhotel.php','nearbyresort.php','nearbyvilla.php','cabhotel.php','cabresort.php','team.php'];
            if (in_array($page, $allowedPages)) {
                include($page);
            } else {
                echo "<p>Invalid page selected.</p>";
            }
        } else {
        ?>
            <!-- Dashboard Cards -->
        <div class="card-container">
            <div class="card hotel-bookings">
                <h3><a href="dashboard.php?page=hotelbooking.php">Hotel Bookings</a></h3>
                <div class="count"><?php echo $hotelBookings; ?></div>
            </div>
            <div class="card villa-bookings">
                <h3><a href="dashboard.php?page=villabooking.php">Villa Bookings</a></h3>
                <div class="count"><?php echo $villaBookings; ?></div>
            </div>
            <div class="card resort-bookings">
                <h3><a href="dashboard.php?page=resortbooking.php">Resort Bookings</a></h3>
                <div class="count"><?php echo $resortBookings; ?></div>
            </div>
            <div class="card total-bookings">
                <h3>Total Bookings</h3>
                <div class="count"><?php echo $totalBookings; ?></div>
            </div>
            <div class="card total-cab">
                <h3>Total Cab Booking</h3>
                <div class="count"><?php echo $totalcab; ?></div>
            </div>
            <div class="card total-users">
                <h3><a href="dashboard.php?page=user.php">Users</a></h3>
                <div class="count"><?php echo $totalUsers; ?></div>
            </div>
            <div class="card user-queries">
                <h3><a href="dashboard.php?page=userquery.php">User Queries</a></h3>
                <div class="count"><?php echo $userQueriesCount; ?></div>
            </div>
            <div class="card total-users">
                <h3><a href="dashboard.php?page=admin.php">Admins</a></h3>
                <div class="count"><?php echo $totalAdmin; ?></div>
            </div>
            
        <?php } ?>
    </div>
</body>
</html>
