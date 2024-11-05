<?php  
session_start();  
error_reporting(E_ALL); // Show all errors for debugging  
ini_set('display_errors', 1);  
include('includes/dbconnection.php');  

if (strlen($_SESSION['user_id']) == 0) {  
    header('location:logout.php');  
    exit(); // Ensure script stops executing after redirection
}  

$msg = ""; // Initialize message variable

// Check if video_id is set in the URL
if (isset($_GET['id'])) { // Change 'video_id' to 'id' to match your edit button link
    $video_id = $_GET['id'];

    // Fetch the current video details from the database
    $query = mysqli_query($con, "SELECT * FROM videos WHERE video_id = '$video_id'");
    $video_details = mysqli_fetch_assoc($query);

    // Check if video exists
    if (!$video_details) {
        $msg = "Video not found.";
    }

    if (isset($_POST['submit'])) {  
        $title = $_POST['title'];  
        $description = $_POST['description'];  
        $category_id = $_POST['category_id'];  

        $maxsize = 2147483648; // 2GB  
        $video_url = $_FILES['video']['name'];  
        $thumbnail = $_FILES['thumbnail']['name'];  

        $video_temp = $_FILES['video']['tmp_name'];  
        $thumbnail_temp = $_FILES['thumbnail']['tmp_name'];  

        $folder_video = "../uploads/videos/" . basename($video_url);  
        $folder_thumbnail = "../uploads/thumbnails/" . basename($thumbnail);  

        // Initialize variable to keep track of the current video file
        $current_video = $video_details['url']; // Keep the existing video URL
        $current_thumbnail = $video_details['thumbnail']; // Keep the existing thumbnail URL

        // Validate video upload  
        if ($_FILES['video']['name']) {  
            $extension = strtolower(pathinfo($folder_video, PATHINFO_EXTENSION));  
            $extensions_arr = array("mp4", "avi", "3gp", "mov", "mkv", "mpeg");  

            if (in_array($extension, $extensions_arr)) {  
                if (($_FILES['video']['size'] >= $maxsize) || ($_FILES["video"]["size"] == 0)) {  
                    $msg = "Video file too large.";  
                } else {  
                    // Move uploaded video  
                    if (move_uploaded_file($video_temp, $folder_video)) {  
                        $current_video = $folder_video; // Update current video with the new file path
                    } else {  
                        $msg = "Failed to upload video.";  
                    }  
                }  
            } else {  
                $msg = "Invalid video file extension.";  
            }  
        } 

        // Validate thumbnail upload
        if ($_FILES['thumbnail']['name']) {  
            $thumbnail_extension = strtolower(pathinfo($folder_thumbnail, PATHINFO_EXTENSION));  
            $thumbnail_extensions_arr = array("jpg", "jpeg", "png", "gif");  

            if (in_array($thumbnail_extension, $thumbnail_extensions_arr)) {  
                // Move uploaded thumbnail  
                if (move_uploaded_file($thumbnail_temp, $folder_thumbnail)) {  
                    $current_thumbnail = $folder_thumbnail; // Update current thumbnail with the new file path
                } else {  
                    $msg = "Failed to upload thumbnail.";  
                }  
            } else {  
                $msg = "Invalid thumbnail file extension.";  
            }  
        }

        // Update the video details in the database
        $uploaded_by = $_SESSION['bpmsaid'];  
        $update_query = mysqli_query($con, "UPDATE videos SET title='$title', description='$description', category_id='$category_id', url='$current_video', thumbnail='$current_thumbnail', uploaded_by='$uploaded_by' WHERE video_id='$video_id'");  

        if ($update_query) {  
            $msg = "Video has been updated successfully.";  
        } else {  
            $msg = "Error in database query.";  
            error_log(mysqli_error($con)); // Log the error  
        }  
    }  
} else {
    $msg = "No video ID specified.";
}  
?>  

<!DOCTYPE HTML>  
<html>  
<head>  
    <title>MTDH | Edit Video</title>  
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />  
    <link href="css/style.css" rel='stylesheet' type='text/css' />  
    <link href="css/font-awesome.css" rel="stylesheet">   
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">  
    <link href="css/custom.css" rel="stylesheet">  
    <script src="js/jquery-1.11.1.min.js"></script>  
    <script src="js/modernizr.custom.js"></script>  
    <script src="js/wow.min.js"></script>  
    <script src="js/metisMenu.min.js"></script>  
    <script src="js/custom.js"></script>  
    <script src="js/classie.js"></script>  
    <script src="js/bootstrap.js"></script>  
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>  
    <script> new WOW().init(); </script>  
</head>   
<body class="cbp-spmenu-push">  
    <div class="main-content">  
        <?php include_once('includes/sidebar.php'); ?>  
        <?php include_once('includes/header.php'); ?>  

        <div id="page-wrapper">  
            <div class="main-page">  
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="videos.php" class="btn btn-primary">View Videos</a>
                    </div>
                </div>
                <div class="forms">  
                    <h3 class="title1">Edit Video</h3>  
                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">   
                        <div class="form-title">  
                            <h4>Video Details:</h4>  
                        </div>  
                        <div class="form-body">  
                            <form method="post" enctype="multipart/form-data">  
                                <p style="font-size:16px; color:red" align="center">  
                                    <?php echo $msg; ?>  
                                </p>  
                                <div class="form-group">  
                                    <label for="title">Title:</label>  
                                    <input type="text" name="title" class="form-control" value="<?php echo $video_details['title']; ?>" required />  
                                </div>  
                                <div class="form-group">  
                                    <label for="description">Description:</label>  
                                    <textarea name="description" class="form-control" required><?php echo $video_details['description']; ?></textarea>  
                                </div>  
                                <div class="form-group">  
                                    <label for="category">Category:</label>  
                                    <select name="category_id" class="form-control" required>  
                                        <option value="">Select Category</option>  
                                        <?php  
                                        // Fetch categories for the dropdown  
                                        $category_query = mysqli_query($con, "SELECT * FROM categories");  
                                        while ($category = mysqli_fetch_assoc($category_query)) {  
                                            $selected = ($video_details['category_id'] == $category['category_id']) ? 'selected' : '';  
                                            echo "<option value='{$category['category_id']}' $selected>{$category['category_name']}</option>";  
                                        }  
                                        ?>  
                                    </select>  
                                </div>  
                                <div class="form-group">  
                                    <label for="video">Video:</label>  
                                    <input type="file" name="video" class="form-control" />  
                                    <p class="help-block">Current Video: <a href="<?php echo $video_details['url']; ?>" target="_blank">Watch Video</a></p>  
                                </div>  
                                <div class="form-group">  
                                    <label for="thumbnail">Thumbnail:</label>  
                                    <input type="file" name="thumbnail" class="form-control" />  
                                    <p class="help-block">Current Thumbnail: <img src="<?php echo $video_details['thumbnail']; ?>" width="100" /></p>  
                                </div>  
                                <button type="submit" name="submit" class="btn btn-primary">Update Video</button>  
                            </form>  
                        </div>  
                    </div>  
                </div>  
            </div>  
        </div>  
        <div class="footer">  
            <p>© 2024 Malawi Traditional Dances Hub. All Rights Reserved | Design by MTDH</p>  
        </div>  
    </div>  
</body>  
</html>
