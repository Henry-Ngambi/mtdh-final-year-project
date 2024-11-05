<?php
session_start();
require '../includes/dbconnection.php';

$error = '';
$success = '';
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = md5(trim($_POST['password']));

    // Validate token and expiration
    $stmt = $con->prepare("SELECT user_id FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Update password
        $stmt = $con->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE user_id = ?");
        $stmt->bind_param("si", $newPassword, $user['user_id']);
        $stmt->execute();

        $success = "Your password has been updated. You can now <a href='login.php'>login</a>.";
    } else {
        $error = "Invalid or expired token!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MTDH |Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <?php if ($success) echo "<p class='success'>$success</p>"; ?>
    <?php if (!$success) { ?>
        <form method="POST" action="">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <input type="password" name="password" placeholder="Enter new password" required>
            <button type="submit">Reset Password</button>
        </form>
    <?php } ?>
</body>
</html>
