<?php
    require 'includes/database.php';
    session_start();
    error_reporting(0);
    date_default_timezone_set("Asia/Kolkata");

    $timeout_duration = 600; // 10 minutes

// Ensure user is logged in
if (isset($_SESSION['username'])) {
    if (isset($_SESSION['last_activity'])) {
        $elapsed_time = time() - $_SESSION['last_activity'];
        
        if ($elapsed_time > $timeout_duration) {
            session_unset();
            session_destroy();
            echo "<script>alert('Session expired due to inactivity. Please log in again.'); window.location.href = 'login.php';</script>";
            exit();
        }
    }

    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book My Stay</title>
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="assets/js/email_loader.js"></script>
</head>
<body>

<header>
<div id="loading-animation">
  <div class="spinner"></div>
</div>
  <nav>
  <div class="logo" onclick="window.location.href='index.php'">BookMyStay</div>
        <ul id="menuList">
            <li><a href="index.php">Home</a></li>
            <li><a href="hotels.php">Hotel</a></li>
            <li><a href="resorts.php">Resort</a></li>
            <li><a href="villas.php">Villa</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="about.php">About Us</a></li>
            <?php if(isset($_SESSION['username'])): ?>
              <li><a href="mybooking.php">Bookings</a></li>
              <li><a href="user.php"><?php echo $_SESSION['username']; ?></a></li>
              <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
              <li><a href="login.php">Log In</a></li>
              <li><a href="signup.php">Sign Up</a></li>
          <?php endif; ?>
        </ul>
        <div class="menu-icon">
            <i class="fa-solid fa-bars" onclick="toggleMenu()"></i>
        </div>
    </nav>
</header>

<script>
        let menuList = document.getElementById("menuList")
        menuList.style.maxHeight = "0px";

        function toggleMenu(){
            if(menuList.style.maxHeight == "0px")
            {
                menuList.style.maxHeight = "500px";
            }
            else{
                menuList.style.maxHeight = "0px";
            }
        }
        
    </script>
<script src="https://kit.fontawesome.com/f8e1a90484.js" crossorigin="anonymous"></script>
<script src="assets/js/load.js"></script>
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-6b347386-f91e-443b-8bc5-efc5366a2725" data-elfsight-app-lazy></div>

</body>
</html>
