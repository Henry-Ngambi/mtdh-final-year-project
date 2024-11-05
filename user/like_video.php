<?php
session_start();
include('../includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Get the video ID from the POST request
$video_id = isset($_POST['video_id']) ? intval($_POST['video_id']) : 0;
$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Check if the interaction already exists
$interaction_query = mysqli_query($con, "SELECT * FROM video_interactions WHERE video_id = '$video_id' AND user_id = '$user_id'");

if (mysqli_num_rows($interaction_query) > 0) {
    // Update the existing interaction
    $interaction_row = mysqli_fetch_assoc($interaction_query);
    
    if ($interaction_row['interaction_type'] === 'like') {
        // If already liked, remove like
        mysqli_query($con, "DELETE FROM video_interactions WHERE interaction_id = {$interaction_row['interaction_id']}");
    } else {
        // Change dislike to like
        mysqli_query($con, "UPDATE video_interactions SET interaction_type = 'like' WHERE interaction_id = {$interaction_row['interaction_id']}");
    }
} else {
    // Add a new like interaction
    mysqli_query($con, "INSERT INTO video_interactions (video_id, user_id, interaction_type) VALUES ('$video_id', '$user_id', 'like')");
}

// Count likes
$likes_count_query = mysqli_query($con, "SELECT COUNT(*) AS likes FROM video_interactions WHERE video_id = '$video_id' AND interaction_type = 'like'");
$likes_count = mysqli_fetch_assoc($likes_count_query)['likes'];

echo json_encode($likes_count);
?>
