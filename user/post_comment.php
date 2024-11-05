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
    $comment_text = mysqli_real_escape_string($con, $_POST['comment']);
    $video_id = intval($_POST['video_id']);
    $user_id = $_SESSION['user_id'];  // Get user ID from session

    // Validate comment text
    if (!empty($comment_text)) {
        // Insert comment into database
        $query = "INSERT INTO comments (video_id, user_id, comment, created_at) VALUES ('$video_id', '$user_id', '$comment_text', NOW())";
        if (mysqli_query($con, $query)) {
            $comment_id = mysqli_insert_id($con); // Get the ID of the inserted comment

            // Fetch user's info for displaying the comment
            $user_query = mysqli_query($con, "SELECT full_name, profile_image FROM users WHERE user_id = '$user_id'");
            $user_row = mysqli_fetch_assoc($user_query);
            $user_name = htmlspecialchars($user_row['full_name']);
            $profile_image = $user_row['profile_image'] ?: '../public/images/profile.jpeg';

            // Generate the HTML for the new comment
            echo "
            <div class='comment'>
                <div class='comment-header'>
                    <img src='$profile_image' alt='' class='comment-avatar'>
                    <strong>$user_name</strong>
                </div>
                <p class='comment-text'>" . htmlspecialchars($comment_text) . "</p>
                <div class='comment-actions'>
                    <span class='like' data-id='$comment_id'>👍 0</span>
                    <span class='dislike' data-id='$comment_id'>👎 0</span>
                    <a href='#' class='reply'>Reply</a>
                </div>
            </div>
            ";
        } else {
            echo json_encode(['error' => 'Could not post comment.']);
        }
    } else {
        echo json_encode(['error' => 'Comment cannot be empty.']);
    }
}
?>
