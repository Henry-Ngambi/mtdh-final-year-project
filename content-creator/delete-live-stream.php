<?php
session_start();
include('includes/dbconnection.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM live_streams WHERE id = $id";
    $result = mysqli_query($con, $query);

    if ($result) {
        header("Location: manage-live-stream.php?message=Live Stream Deleted");
    } else {
        header("Location: manage-live-stream.php?error=Failed to delete live stream");
    }
} else {
    header("Location: manage-live-stream.php");
}
?>
