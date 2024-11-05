<?php
session_start(); // Start the session to access user information
include '../includes/dbconnection.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to save videos.";
    exit;
}

// Get video ID from POST request
$video_id = isset($_POST['video_id']) ? intval($_POST['video_id']) : 0;
$user_id = $_SESSION['user_id'];

// Check if the video ID is valid
if ($video_id > 0) {
    // Check if the video is already saved by the user
    $check_query = mysqli_query($con, "SELECT * FROM saved_videos WHERE video_id = '$video_id' AND user_id = '$user_id'");
    
    if (mysqli_num_rows($check_query) > 0) {
        echo "This video is already saved in your favorites.";
    } else {
        // Insert the video into the saved_videos table
        $save_query = mysqli_query($con, "INSERT INTO saved_videos (video_id, user_id) VALUES ('$video_id', '$user_id')");

        if ($save_query) {
            echo "Video saved successfully!";
        } else {
            echo "Error saving the video. Please try again.";
        }
    }
} else {
    echo "Invalid video ID.";
}
?>
