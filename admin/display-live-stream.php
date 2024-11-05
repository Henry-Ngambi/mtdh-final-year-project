<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
} else {
    // Check if the stream ID is provided
    if (isset($_GET['id'])) {
        $stream_id = mysqli_real_escape_string($con, $_GET['id']);
        
        // Fetch the live stream details from the database
        $query = "SELECT * FROM live_streams WHERE id = '$stream_id'";
        $result = mysqli_query($con, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        } else {
            echo "<script>alert('Live stream not found.'); window.location.href='manage-live-videos.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid request.'); window.location.href='manage-live-videos.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>MTDH | Live Stream - <?php echo $row['title']; ?></title>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet"> 
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.js"> </script>
</head>
<body>
    <!-- Header and Sidebar -->
    <?php include_once('includes/header.php'); ?>
    <?php include_once('includes/sidebar.php'); ?>

    <div class="main-content">
        <h2><?php echo $row['title']; ?></h2>
        <p><?php echo $row['description']; ?></p>
        
        <!-- Video Embed -->
        <div class="video-responsive">
            <iframe src="<?php echo $row['stream_url']; ?>" frameborder="0" allowfullscreen></iframe>
        </div>
        
        <p><strong>Date/Time:</strong> <?php echo $row['date_time']; ?></p>
    </div>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>
</body>
</html>
