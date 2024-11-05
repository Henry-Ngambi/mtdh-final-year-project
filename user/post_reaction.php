<?php
session_start();
include('../includes/dbconnection.php');

// Check if the user is logged in and reaction data is set
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['reaction_type'], $data['video_id']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $video_id = $data['video_id'];
    $reaction_type = $data['reaction_type'];

    $con->query("INSERT INTO reactions (user_id, video_id, reaction_type, reaction_date) VALUES ($user_id, $video_id, '$reaction_type', NOW())");

    echo json_encode(['message' => 'Reaction added successfully']);
    exit();
} else {
    echo json_encode(['message' => 'Error: Reaction failed']);
    exit();
}
?>
