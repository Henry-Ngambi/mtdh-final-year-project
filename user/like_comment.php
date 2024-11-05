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
        
        if ($interaction_row['interaction_type'] === 'like') {
            // User already liked, remove like
            $delete_query = "DELETE FROM user_interactions WHERE comment_id = '$comment_id' AND user_id = '$user_id'";
            mysqli_query($con, $delete_query);
            
            // Decrement the likes count in comments table
            mysqli_query($con, "UPDATE comments SET likes = likes - 1 WHERE comment_id = '$comment_id'");
            echo json_encode(['Removed']); // Notify the client that the like was removed
        } else {
            // User has disliked before, switch to like
            $update_query = "UPDATE user_interactions SET interaction_type = 'like' WHERE comment_id = '$comment_id' AND user_id = '$user_id'";
            mysqli_query($con, $update_query);
            
            // Update counts in comments table
            mysqli_query($con, "UPDATE comments SET likes = likes + 1, dislikes = dislikes - 1 WHERE comment_id = '$comment_id'");
            echo json_encode(['Liked']); // Notify the client that the like was added, and dislike was removed
        }
    } else {
        // User has not interacted with the comment yet, add like
        $insert_like_query = "INSERT INTO user_interactions (comment_id, user_id, interaction_type) VALUES ('$comment_id', '$user_id', 'like')";
        mysqli_query($con, $insert_like_query);
        
        // Increment the likes count in comments table
        mysqli_query($con, "UPDATE comments SET likes = likes + 1 WHERE comment_id = '$comment_id'");
        echo json_encode(['Liked']); // Notify the client that the like was added
    }
}
?>
