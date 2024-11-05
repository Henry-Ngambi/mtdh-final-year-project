<?php
include('../includes/dbconnection.php');

$video_id = $_GET['video_id'];

// Fetch comments for the specified video
$query = "SELECT c.comment, u.username FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.video_id = ? ORDER BY c.comment_date DESC";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $video_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

$stmt->close();
echo json_encode(['comments' => $comments]);
?>
