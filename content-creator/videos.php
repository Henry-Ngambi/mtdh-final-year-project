<?php  
session_start();  
error_reporting(E_ALL);  
ini_set('display_errors', 1);  
include('includes/dbconnection.php');  

if (strlen($_SESSION['user_id']) == 0) {  
    header('location:logout.php');  
} else {  
    // Fetch videos and join with users for uploaded_by name
    $query = mysqli_query($con, "SELECT v.*, u.full_name AS uploaded_by_name FROM videos v 
                                 LEFT JOIN users u ON v.uploaded_by = u.user_id");  
?>  

<!DOCTYPE HTML>  
<html>  
<head>  
    <title>MTDH | Videos</title>  
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
                    <h3 class="title1">Manage Videos</h3>  
                    
                    <?php if (isset($_GET['msg'])): ?>  <!-- Display message if set -->
                        <p style="font-size:16px; color:red" align="center"><?php echo htmlspecialchars($_GET['msg']); ?></p>
                    <?php endif; ?>

                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">   
                        <div class="form-title">  
                            <h4>Videos List:</h4>  
                        </div>  
                        <div class="form-body">  
                            <table class="table table-bordered">  
                                <thead>  
                                    <tr>  
                                        <th>Title</th>  
                                        <th>Description</th>  
                                        <th>Category</th>  
                                        <th>Video</th>  
                                        <th>Thumbnail</th>  
                                        <th>Uploaded By</th>  
                                        <th>Actions</th>  
                                    </tr>  
                                </thead>  
                                <tbody>  
                                    <?php  
                                    while ($row = mysqli_fetch_assoc($query)) {  
                                        $category_id = $row['category_id'];  
                                        
                                        // Fetch category name  
                                        $category_query = mysqli_query($con, "SELECT category_name FROM categories WHERE category_id = '$category_id'");  
                                        $category_row = mysqli_fetch_assoc($category_query);  
                                        $category_name = $category_row['category_name'] ?? 'Unknown'; // Fallback if not found  
                                        
                                        echo "<tr>";  
                                        echo "<td>{$row['title']}</td>";  
                                        echo "<td>{$row['description']}</td>";  
                                        echo "<td>{$category_name}</td>";  
                                        echo "<td>  
                                                <video width='200' controls class='video-player'>  
                                                    <source src='{$row['url']}' type='video/mp4'>  
                                                    Your browser does not support the video tag.  
                                                </video>  
                                                <p>Video URL: {$row['url']}</p>  
                                              </td>";  
                                        echo "<td><img src='{$row['thumbnail']}' width='100' /></td>";  
                                        echo "<td>{$row['uploaded_by_name']}</td>";  
                                        echo "<td>  
                                                <a href='edit-video.php?id={$row['video_id']}' class='btn btn-primary'>Edit</a>  
                                                <a href='delete-video.php?id={$row['video_id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this video?\")'>Delete</a>  
                                              </td>";  
                                        echo "</tr>";  
                                    }  
                                    ?>  
                                </tbody>  
                            </table>  
                        </div>  
                    </div>  
                </div>  
            </div>  
        </div>  
        <?php include_once('includes/footer.php'); ?>  
    </div>  

    <script>  
        // Pause all other videos when one is playing  
        $(document).ready(function() {  
            $('.video-player').on('play', function() {  
                $('.video-player').each(function() {  
                    if (this !== document.activeElement && !this.paused) {  
                        this.pause(); // Pause other videos  
                    }  
                });  
            });  
        });  
    </script>  

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
            if(button !== 'showLeftPush') {  
                classie.toggle(showLeftPush, 'disabled');  
            }  
        }  
    </script>  
</body>  
</html>  

<?php } ?>
