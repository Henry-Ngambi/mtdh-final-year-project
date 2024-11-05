<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = mysqli_real_escape_string($con, $_POST['title']);
        $description = mysqli_real_escape_string($con, $_POST['description']);
        $date_time = mysqli_real_escape_string($con, $_POST['date']);

        // Generate a unique streaming URL
        $stream_url = "https://yourdomain.com/stream/" . urlencode($title) . "-" . time(); // Modify as needed

        // Insert into the database
        $query = "INSERT INTO live_streams (title, description, stream_url, date_time) VALUES ('$title', '$description', '$stream_url', '$date_time')";
        $result = mysqli_query($con, $query);

        if ($result) {
            echo "<script>alert('Live stream created successfully.'); window.location.href='manage-live-videos.php';</script>";
        } else {
            echo "<script>alert('Error creating live stream.'); window.location.href='manage-live-videos.php';</script>";
        }
    }
}
?>
