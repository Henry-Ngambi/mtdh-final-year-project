<?php  
session_start();  
error_reporting(E_ALL); // Show all errors for debugging  
ini_set('display_errors', 1);  
include('includes/dbconnection.php');  

if (strlen($_SESSION['user_id'] == 0)) {  
    header('location:logout.php');  
} else {  
    $msg = ""; // Initialize the $msg variable  

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
                        // Validate thumbnail upload  
                        if ($_FILES['thumbnail']['name']) {  
                            $thumbnail_extension = strtolower(pathinfo($folder_thumbnail, PATHINFO_EXTENSION));  
                            $thumbnail_extensions_arr = array("jpg", "jpeg", "png", "gif");  

                            // Check thumbnail extension  
                            if (in_array($thumbnail_extension, $thumbnail_extensions_arr)) {  
                                // Move uploaded thumbnail  
                                if (move_uploaded_file($thumbnail_temp, $folder_thumbnail)) {  
                                    // Insert into the videos table  
                                    $uploaded_by = $_SESSION['bpmsaid'];  // Assuming session holds the user ID  
                                    $query = mysqli_query($con, "INSERT INTO videos (title, description, category_id, url, thumbnail, uploaded_by)   
                                                                VALUES ('$title', '$description', '$category_id', '$folder_video', '$folder_thumbnail', '$uploaded_by')");  
                                    
                                    if ($query) {  
                                        $msg = "Video has been uploaded successfully.";  
                                        echo "<script>window.location.href = 'upload-videos.php'</script>";  
                                    } else {  
                                        $msg = "Error in database query.";  
                                        error_log(mysqli_error($con)); // Log the error  
                                    }  
                                } else {  
                                    $msg = "Failed to upload thumbnail.";  
                                }  
                            } else {  
                                $msg = "Invalid thumbnail file extension.";  
                            }  
                        }  
                    } else {  
                        $msg = "Failed to upload video.";  
                    }  
                }  
            } else {  
                $msg = "Invalid video file extension.";  
            }  
        } else {  
            $msg = "Please select a video to upload.";  
        }  
    }  
?>  

<!DOCTYPE HTML>  
<html>  
<head>  
    <title>MTDH | Upload Videos</title>  
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
                <div class="forms">  
                    <h3 class="title1">Upload Videos 
                        <a href="videos.php" class="btn btn-primary" style="float: right; margin-top: -10px;">View Videos</a>
                    </h3>  
                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">   
                        <div class="form-title">  
                            <h4>Video Details:</h4>  
                        </div>  
                        <div class="form-body">  
                            <p style="font-size:16px; color:red" align="center">   
                                <?php if ($msg) { echo $msg; } ?>  
                            </p>  
                            <form method="post" enctype="multipart/form-data">  
                                <div class="form-group">   
                                    <label for="title">Video Title</label>   
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Video Title" required="true">   
                                </div>  
                                <div class="form-group">   
                                    <label for="description">Video Description</label>   
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter Video Description"></textarea>  
                                </div>  
                                <div class="form-group">   
                                    <label for="category_id">Category</label>  
                                    <select class="form-control" id="category_id" name="category_id" required="true">  
                                        <option value="">Select Category</option>  
                                        <option value="1">Northern Malawi</option>  
                                        <option value="2">Central Malawi</option>  
                                        <option value="3">Southern Malawi</option>  
                                    </select>  
                                </div>  
                                <div class="form-group">   
                                    <label for="video">Upload Video</label>  
                                    <input type="file" class="form-control" id="video" name="video" required="true">  
                                </div>  
                                <div class="form-group">   
                                    <label for="thumbnail">Upload Thumbnail</label>  
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail" required="true">  
                                </div>  
                                  
                                <button type="submit" name="submit" class="btn btn-primary">Upload</button>   
                            </form>   
                        </div>  
                    </div>  
                </div>  
            </div>  
        </div>  
        <?php include_once('includes/footer.php'); ?>  
    </div>  

    <script>  
        var menuLeft = document.getElementById('cbp-spmenu-s1'),  
            showLeftPush = document.getElementById('showLeftPush'),  
            body = document.body;  
            
        showLeftPush.onclick = function() {  
            classie.toggle(this, 'active');  
            classie.toggle(body, 'cbp-spmenu-push-toright');  
            classie.toggle(menuLeft, 'cbp-spmenu-open');  
            disableOther('showLeftPush');  
        };  
        
        function disableOther(button) {  
            if (button !== 'showLeftPush') {  
                classie.toggle(showLeftPush, 'disabled');  
            }  
        }  
    </script>  
</body>  
</html>  

<?php } ?>
