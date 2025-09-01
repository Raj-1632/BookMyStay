<?php session_start(); error_reporting(0); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Book My Stay</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .policy-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
        }
        p {
            color: #555;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <?php include './includes/header.php'; ?>

    <div class="policy-container">
        <h1>Privacy Policy</h1>
        <p>
            Welcome to Book My Stay. Your privacy is important to us, and we are committed to protecting your personal data. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website.
        </p>
        
        <h2>Information We Collect</h2>
        <p>We collect personal information such as your name, email, phone number, payment details, and booking history when you register or make a reservation. Additionally, we collect non-personal information like browser type, IP address, and device details.</p>
        
        <h2>How We Use Your Information</h2>
        <p>We use your information to process bookings, provide customer support, enhance user experience, improve security, and send important updates and promotional emails.</p>
        
        <h2>Data Sharing and Protection</h2>
        <p>We do not sell or rent your personal information. Data may be shared with trusted partners, such as payment processors and service providers, to facilitate transactions. We implement strict security measures to protect your data from unauthorized access.</p>
        
        <h2>Your Rights</h2>
        <p>You have the right to access, update, or delete your personal information. You may also opt out of promotional communications. For any requests, please contact us.</p>
        
        <h2>Changes to This Policy</h2>
        <p>We may update this Privacy Policy periodically. Any changes will be posted on this page.</p>
        
        <h2>Contact Us</h2>
        <p>If you have any questions about our Privacy Policy, please <a href="contact.php">contact us</a>.</p>
    </div>  

    <?php include './includes/footer.php'; ?>
</body>
</html>
