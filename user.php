<?php
session_start();
error_reporting(0);

// Include necessary files
include 'includes/database.php';
include 'includes/header.php';

// Load PHPMailer for email functionality
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Validate if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch user data securely using prepared statements
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "<p>User not found.</p>";
    exit();
}

// Handle password change logic
$password_error = "";
$password_success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $password_error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $password_error = "New password and confirm password do not match.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
        $password_error = "Password must be at least 8 characters long, include an uppercase, lowercase, number, and special character.";
    } elseif (password_verify($new_password, $row['password'])) {
        $password_error = "New password cannot be the same as the old password.";
    } else {
        // Verify current password
        if (password_verify($current_password, $row['password'])) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update_stmt = $conn->prepare("UPDATE user SET password = ? WHERE username = ?");
            $update_stmt->bind_param("ss", $hashed_password, $username);

            if ($update_stmt->execute()) {
                $password_success = "Password changed successfully.";

                // Send email notification using PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'bookmystayonline@gmail.com';
                    $mail->Password = 'sftm blky plvr lyvc';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('bookmystayonline@gmail.com', 'Book My Stay');
                    $mail->addAddress($row['email']);
                    $mail->Subject = 'Password Changed Successfully';
                    $mail->Body = "Dear " . htmlspecialchars($row['fname']) . ",\n\nYour password has been successfully changed.\n\nIf you did not make this change, please contact our support team immediately.\n\nRegards,\nBook My Stay Team";

                    $mail->send();
                    echo "<script>alert('Password changed successfully. Notification email sent.');</script>";
                } catch (Exception $e) {
                    echo "<script>alert('Password changed, but email could not be sent. Error: {$mail->ErrorInfo}');</script>";
                }
            } else {
                $password_error = "Error updating password. Please try again later.";
            }
        } else {
            $password_error = "Current password is incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="styles.css">    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            margin-top: 100px;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;

        }
        .ui{
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .btn {
            display: inline-block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
        form {
            margin-top: 30px;
            display: none;
        }
        form.active {
            display: block;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>

    <script>
        function togglePasswordForm() {
            let form = document.querySelector("form");
            form.classList.toggle("active");
        }

        function validatePassword() {
            let newPassword = document.getElementById("new_password").value;
            let confirmPassword = document.getElementById("confirm_password").value;
            let errorMsg = document.getElementById("password_error");

            let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!passwordRegex.test(newPassword)) {
                errorMsg.innerHTML = "Password must be at least 8 characters, include uppercase, lowercase, a number, and a special character.";
                return false;
            }
            if (newPassword !== confirmPassword) {
                errorMsg.innerHTML = "Passwords do not match.";
                return false;
            }
            errorMsg.innerHTML = "";
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
    <table>
            <tr>
                <th colspan="2" class="ui">User Information</th>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
            </tr>
            <tr>
                <td>First Name</td>
                <td><?php echo htmlspecialchars($row['fname']); ?></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><?php echo htmlspecialchars($row['lname']); ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
            </tr>
            <tr>
                <td>Phone</td>
                <td><?php echo htmlspecialchars($row['phno']); ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
            </tr>
        </table>


        <h2>Change Password</h2>

        <button class="btn" type="button" onclick="togglePasswordForm()">Change Password</button>

        <p id="password_error" class="error">
            <?php if ($password_error) echo $password_error; ?>
        </p>
        <p class="success">
            <?php if ($password_success) echo $password_success; ?>
        </p>
        <form method="POST" onsubmit="return validatePassword()">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <input type="submit" name="change_password" value="Change Password">
        </form>
    </div>
</body>
</html>
<?php
    include 'includes/footer.php';
?>