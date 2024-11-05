<?php
// Include the database connection file
include('../includes/dbconnection.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit; // Stop script execution
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = intval($_POST['comment_id']);
    $user_id = $_SESSION['user_id']; // Get user ID from session

    // Check if the user has already interacted with the comment
    $check_interaction_query = "SELECT * FROM user_interactions WHERE comment_id = '$comment_id' AND user_id = '$user_id'";
    $interaction_result = mysqli_query($con, $check_interaction_query);
    
    if (mysqli_num_rows($interaction_result) > 0) {
        $interaction_row = mysqli_fetch_assoc($interaction_result);
        
        if ($interaction_row['interaction_type'] === 'dislike') {
            // User already disliked, remove dislike
            $delete_query = "DELETE FROM user_interactions WHERE comment_id = '$comment_id' AND user_id = '$user_id'";
            mysqli_query($con, $delete_query);
            
            // Decrement the dislikes count in comments table
            mysqli_query($con, "UPDATE comments SET dislikes = dislikes - 1 WHERE comment_id = '$comment_id'");
            echo json_encode(['Removed']); // Notify the client that the dislike was removed
        } else {
            // User has liked before, switch to dislike
            $update_query = "UPDATE user_interactions SET interaction_type = 'dislike' WHERE comment_id = '$comment_id' AND user_id = '$user_id'";
            mysqli_query($con, $update_query);
            
            // Update counts in comments table
            mysqli_query($con, "UPDATE comments SET dislikes = dislikes + 1, likes = likes - 1 WHERE comment_id = '$comment_id'");
            echo json_encode(['Disliked']); // Notify the client that the dislike was added, and like was removed
        }
    } else {
        // User has not interacted with the comment yet, add dislike
        $insert_dislike_query = "INSERT INTO user_interactions (comment_id, user_id, interaction_type) VALUES ('$comment_id', '$user_id', 'dislike')";
        mysqli_query($con, $insert_dislike_query);
        
        // Increment the dislikes count in comments table
        mysqli_query($con, "UPDATE comments SET dislikes = dislikes + 1 WHERE comment_id = '$comment_id'");
        echo json_encode(['Disliked']); // Notify the client that the dislike was added
    }
}
?>
