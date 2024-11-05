<?php
session_start();
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
    exit;
}

// Fetch current About Us content from the database
$result = mysqli_query($con, "SELECT * FROM about_us WHERE id=1");
$row = mysqli_fetch_assoc($result);

if ($row) {
    echo json_encode(['status' => 'success', 'mission' => $row['mission'], 'what_we_do' => $row['what_we_do'], 'our_team' => $row['our_team']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No content found for About Us.']);
}
?>
