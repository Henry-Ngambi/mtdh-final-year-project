<?php
session_start();
include('includes/dbconnection.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "UPDATE live_streams SET is_active = 0 WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Stream ended successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to end stream.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid stream ID.']);
}
?>
