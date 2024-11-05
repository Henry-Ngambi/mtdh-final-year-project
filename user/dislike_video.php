<?php 
// Start the session
session_start();
// Include the database connection file
include('../includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit; // Stop script execution
}

// Get the video ID from the POST request and ensure it's a valid integer
$video_id = isset($_POST['video_id']) ? intval($_POST['video_id']) : 0;
$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Validate that the video ID is greater than zero
if ($video_id <= 0) {
    echo json_encode(['error' => 'Invalid video ID']);
    exit; // Stop script execution
}

// Check if the interaction already exists
$interaction_query = mysqli_query($con, "SELECT * FROM video_interactions WHERE video_id = '$video_id' AND user_id = '$user_id'");

if (!$interaction_query) {
    echo json_encode(['error' => 'Database query failed']);
    exit; // Stop script execution
}

if (mysqli_num_rows($interaction_query) > 0) {
    // Update the existing interaction
    $interaction_row = mysqli_fetch_assoc($interaction_query);
    
    if ($interaction_row['interaction_type'] === 'dislike') {
        // If already disliked, remove dislike
        $delete_query = "DELETE FROM video_interactions WHERE interaction_id = {$interaction_row['interaction_id']}";
        mysqli_query($con, $delete_query);
    } else {
        // Change like to dislike
        $update_query = "UPDATE video_interactions SET interaction_type = 'dislike' WHERE interaction_id = {$interaction_row['interaction_id']}";
        mysqli_query($con, $update_query);
    }
} else {
    // Add a new dislike interaction
    $insert_query = "INSERT INTO video_interactions (video_id, user_id, interaction_type) VALUES ('$video_id', '$user_id', 'dislike')";
    mysqli_query($con, $insert_query);
}

// Count dislikes
$dislikes_count_query = mysqli_query($con, "SELECT COUNT(*) AS dislikes FROM video_interactions WHERE video_id = '$video_id' AND interaction_type = 'dislike'");
if (!$dislikes_count_query) {
    echo json_encode(['error' => 'Database query failed']);
    exit; // Stop script execution
}

$dislikes_count = mysqli_fetch_assoc($dislikes_count_query)['dislikes'];

// Return the dislikes count as a JSON response
echo json_encode(['dislikes' => $dislikes_count]);
?>
