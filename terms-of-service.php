<?php session_start();error_reporting(0); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Book My Stay</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .terms-container {
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

    <div class="terms-container">
        <h1>Terms & Conditions</h1>
        <p>
            Welcome to Book My Stay. By using our website, you acknowledge that you have read, understood, and agreed to be bound by these Terms & Conditions. If you do not agree, please refrain from using our services.
        </p>

        <h2>1. User Responsibilities</h2>
        <p>Users must provide accurate and complete information when registering and booking accommodations. Any fraudulent activity or misuse of the platform may result in account suspension or legal action.</p>
        
        <h2>2. Booking and Payments</h2>
        <p>All bookings are subject to availability and confirmation from the accommodation provider. A valid payment method is required, and a confirmation email will be sent upon successful booking.</p>
        
        <h2>3. Cancellations and Refunds</h2>
        <p>Cancellation policies vary depending on the accommodation provider. Please check the booking confirmation for details. If a hotel booking is canceled, any related cab booking will also be canceled automatically.</p>
        
        <h2>4. Platform Usage</h2>
        <p>Users must not engage in unlawful activities, including but not limited to fraudulent transactions, hacking, or misrepresentation. We reserve the right to suspend accounts violating our policies.</p>
        
        <h2>5. Limitation of Liability</h2>
        <p>Book My Stay acts as an intermediary between users and accommodation providers. We are not liable for any disputes, losses, or damages incurred during your stay or while using our services.</p>
        
        <h2>6. Changes to Terms</h2>
        <p>We reserve the right to update these Terms & Conditions at any time. Continued use of our platform after updates constitutes acceptance of the revised terms.</p>
        
        <h2>7. Contact Us</h2>
        <p>If you have any questions or concerns about these Terms & Conditions, please <a href="contact.php">contact us</a>.</p>
    </div>

    <?php include './includes/footer.php'; ?>
</body>
</html>
