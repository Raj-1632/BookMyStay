<?php
error_reporting(0);
session_start();
require 'includes/database.php'; // Assuming you have a separate file for database connection

// Load PHPMailer for email functionality
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST['send'])) {
    if (!isset($_SESSION['username'])) {
        $_SESSION['error'] = "<script> alert('Please log in to send a query.') </script>";
        header('Location: contact.php');
        exit();
    } else {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $subject = $conn->real_escape_string($_POST['subject']);
        $message = $conn->real_escape_string($_POST['message']);

        // Insert query
        $sql = "INSERT INTO user_queries (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";

        if ($conn->query($sql) === TRUE) {
            // Send email notification
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'bookmystayonline@gmail.com'; // Your email
                $mail->Password = 'sftm blky plvr lyvc'; // Your email password or App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Email content
                $mail->setFrom('bookmystayonline@gmail.com', 'Book My Stay');
                $mail->addAddress($email, $name); // Recipient email
                $mail->Subject = 'Thank You for Contacting Us';
                $mail->Body = "Dear $name,\n\nThank you for reaching out to us. We have received your message with the subject: \"$subject\".\n\nOur team will get back to you shortly.\n\nMessage Details:\n$message\n\nRegards,\nBook My Stay Team";

                // Send the email
                $mail->send();

                $_SESSION['success'] = "<script> alert('Thank you for contacting us. We will get back to you soon!') </script>";
            } catch (Exception $e) {
                $_SESSION['error'] = "Message sent but email could not be delivered. Error: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION['error'] = "Error: " . $conn->error;
        }
        header('Location: contact.php');
        exit();
    }
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - Book My Stay</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .section-contact {
      max-width: 800px;
      margin: 50px auto;
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    .section-contact h1 {
      font-size: 36px;
      color: #333;
    }
    .section-contact p {
      font-size: 18px;
      color: #555;
    }
    .contact-info {
      margin-top: 20px;
      padding: 20px;
      background: #f1f1f1;
      border-radius: 8px;
    }
    .contact-info p {
      margin: 10px 0;
      font-size: 16px;
      color: #333;
    }
    .social-icons a {
      display: inline-block;
      margin: 10px;
      font-size: 24px;
      color: #6C63FF;
      text-decoration: none;
    }
    .social-icons a:hover {
      color: #5a53d4;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    label {
      font-size: 16px;
      color: #333;
      text-align: left;
    }
    input, textarea {
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ddd;
      border-radius: 4px;
      width: 100%;
      box-sizing: border-box;
    }
    textarea {
      min-height: 120px;
      resize: vertical;
    }
    button {
      padding: 12px;
      font-size: 18px;
      background-color: #6C63FF;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    button:hover {
      background-color: #5a53d4;
    }
    @media (max-width: 768px) {
      .section-contact {
        margin: 20px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <?php include './includes/header.php'; ?>
  
  <section class="section-contact">
    <h1>Contact Us</h1>
    <p>If you have any questions, feel free to reach out!</p>
    
    <div class="contact-info">
      <p><strong>Email:</strong> bookmystayonline@gmail.com</p>
      <p><strong>Phone:</strong> +91 8238388031, +91 7041332005</p>
      <p><strong>Address:</strong> SSCCS, Bhavnagar, Gujrat, India</p>
    </div>
    
    <div class="social-icons">
      <a href="#"><i class="fab fa-facebook"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
    
    <form method="POST">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>
      <label for="subject">Subject:</label>
      <input type="text" id="subject" name="subject" required>
      <label for="message">Message:</label>
      <textarea id="message" name="message" required></textarea>
      <button type="submit" name="send">Send</button>
    </form>
  </section>
  
  <?php if (isset($_SESSION['success'])): ?>
    <p style="color: green; text-align: center;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
  <?php endif; ?>
  
  <?php if (isset($_SESSION['error'])): ?>
    <p style="color: red; text-align: center;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
  <?php endif; ?>
  
  <?php include './includes/footer.php'; ?>
</body>
</html>
