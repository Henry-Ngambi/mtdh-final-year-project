<?php
session_start();
require '../includes/dbconnection.php'; // Ensure the path is correct

// Initialize error variable
$error = '';

// Process the form when it's submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and trim input values
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = md5(trim($_POST['password'])); // Hash the password using MD5

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Prepare the SQL query
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Check if user exists, verify password, and check status
        if ($user && $user['password'] === $password) {
            if ($user['status'] === 'active') {
                // Store user data in session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'] ?? '';

                // Role-based redirection
                switch ($user['role_id']) {
                    case 3: // Admin
                        header('Location: ../admin/dashboard.php');
                        break;
                    case 2: // Content Creator
                        header('Location: ../content-creator/dashboard.php');
                        break;
                    case 1: // Regular User
                        header('Location: ../user/index.php');
                        break;
                    default:
                        $error = "Invalid role detected!";
                }
                exit;
            } else {
                $error = "Your account is inactive. Please contact support.";
            }
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>

<!-- Login HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../user/bootstrap.css">
    <title>MTDH | Login</title>
</head>
<body>
    <div class="form-container">
        <h2>Sign in to MTDH Account</h2>
        <form method="post" action="">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn btn-primary" name="login">Sign in</button>

            <!-- Display error message if exists -->
            <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>

            <div class="footer-links">
                <p><a href="forgot-password.php">Forgot your password?</a></p>
                <p>New to MTDH? <a href="register.php">Create Account</a></p>
            </div>
        </form>
    </div>
</body>
</html>
