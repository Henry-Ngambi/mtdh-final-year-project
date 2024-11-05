<?php
session_start();
include('includes/dbconnection.php');

if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $action = $_GET['action'];
    $user_id = $_GET['user_id'];

    if ($action == "activate") {
        $query = "UPDATE users SET status = 'active' WHERE user_id = ?";
    } elseif ($action == "deactivate") {
        $query = "UPDATE users SET status = 'deactivated' WHERE user_id = ?";
    }

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $_SESSION['status'] = ($action == "activate") ? "User activated successfully." : "User deactivated successfully.";
}

header("Location: user-lists.php");
exit();
?>
