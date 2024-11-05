<?php  
session_start();  
include('includes/dbconnection.php');  

if (strlen($_SESSION['user_id'] == 0)) {  
    header('location:logout.php');  
    exit();
}  

if (isset($_GET['id'])) { // Check if video_id is set in the URL
    $video_id = $_GET['id'];

    // Perform deletion
    $delete_query = mysqli_query($con, "DELETE FROM videos WHERE video_id = '$video_id'");
    if ($delete_query) {
        header("location: videos.php?msg=Video has been deleted successfully.");  
    } else {
        header("location: videos.php?msg=Error in deleting video. Please try again.");  
    }
} else {
    header("location: videos.php?msg=No video ID specified.");  
}  
?>
