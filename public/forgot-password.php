<?php
session_start();
require '../vendor/autoload.php'; 
require '../includes/dbconnection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    // Generate token
    $token = bin2hex(random_bytes(32));
    $query = "UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Send email with PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'henrypngambi@gmail.com'; // Replace with your Gmail
            $mail->Password = 'Juliet@hp'; // Replace with your Gmail password or app-specific password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('henrypngambi@gmail.com', 'MTDH Support');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = 'Click the following link to reset your password: <a href="http://localhost/mtdh/public/reset-password.php?token=' . $token . '">Reset Password</a>';

            $mail->send();
            $success = 'A password reset link has been sent to your email.';
        } catch (Exception $e) {
            $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = 'No account found with that email address.';
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Forgot Password</h2>
        <form method="POST" action="">
            <label>Enter your email</label>
            <input type="email" name="email" required>
            <button class="btn btn-primary" type="submit">Request Password Reset</button>
        </form>

        <!-- Display messages -->
        <?php if (!empty($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php elseif (!empty($success)): ?>
            <p class="success-message"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <!-- Back to login link -->
         <div class="footer-links">
         <p><a href="login.php">Back to Login</a></p>
         </div>
    </div>
</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4; /* Light background color */
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Full height of the viewport */
        margin: 0;
    }

    .form-container {
        background-color: #ffffff; /* White background for the card */
        border-radius: 10px; /* Rounded corners */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Soft shadow */
        padding: 20px; /* Padding inside the card */
        width: 300px; /* Width of the card */
        text-align: center; /* Center text */
        padding-right: 40px;
        padding-top: 0px;
        padding-bottom: 22px;
    }

    h2 {
        margin-bottom: 20px; /* Space below the heading */
    }

    label {
        display: block; /* Block display for the label */
        margin-bottom: 5px; /* Space below the label */
    }

    input[type="email"] {
        width: 100%; /* Full width */
        padding: 10px; /* Padding inside the input */
        border: 1px solid #ccc; /* Border color */
        border-radius: 5px; /* Rounded corners */
        margin-bottom: 21px; /* Space below the input */
    }

    .error-message {
        color: red; /* Red text for error messages */
    }

    .success-message {
        color: green; /* Green text for success messages */
        margin-top: 10px; /* Space above the success message */
    }

    .footer-links {
        margin-top: 0px; /* Space above the link */
    }
</style>
