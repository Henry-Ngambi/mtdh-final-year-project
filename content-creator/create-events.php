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
        $location = $_POST['location'];  
        $event_date = $_POST['event_date'];  
        $created_by = $_SESSION['user_id']; // Assuming session holds the user ID  
        $ticket_price = $_POST['ticket_price'];  
        $status = $_POST['status']; // Status could be active, pending, etc.

        // Handle image upload  
        $image = $_FILES['image']['name'];  
        $image_temp = $_FILES['image']['tmp_name'];  
        $folder_image = "../uploads/event_images/" . basename($image);  

        // Validate image upload  
        if ($_FILES['image']['name']) {  
            $image_extension = strtolower(pathinfo($folder_image, PATHINFO_EXTENSION));  
            $image_extensions_arr = array("jpg", "jpeg", "png", "gif");  

            if (in_array($image_extension, $image_extensions_arr)) {  
                // Move uploaded image  
                if (move_uploaded_file($image_temp, $folder_image)) {  
                    // Insert into the events table  
                    $query = mysqli_query($con, "INSERT INTO events (title, description, location, event_date, created_by, ticket_price, image, status, created_at, updated_at)   
                                                VALUES ('$title', '$description', '$location', '$event_date', '$created_by', '$ticket_price', '$folder_image', '$status', NOW(), NOW())");  
                    
                    if ($query) {  
                        $msg = "Event has been created successfully.";  
                    } else {  
                        $msg = "Error in database query.";  
                        error_log(mysqli_error($con)); // Log the error  
                    }  
                } else {  
                    $msg = "Failed to upload image.";  
                }  
            } else {  
                $msg = "Invalid image file extension.";  
            }  
        } else {  
            $msg = "Please select an image to upload.";  
        }  
    }  
?>  

<!DOCTYPE HTML>  
<html>  
<head>  
    <title>MTDH | Create Event</title>  
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
                    <h3 class="title1">Create Event</h3>  
                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">   
                        <div class="form-title">  
                            <h4>Event Details:</h4>  
                        </div>  
                        <div class="form-body">  
                            <form method="post" enctype="multipart/form-data">  
                                <p style="font-size:16px; color:red" align="center">   
                                    <?php if ($msg) { echo $msg; } ?>  
                                </p>  
                                <div class="form-group">   
                                    <label for="title">Event Title</label>   
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Event Title" required="true">   
                                </div>  
                                <div class="form-group">   
                                    <label for="description">Event Description</label>   
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter Event Description"></textarea>  
                                </div>  
                                <div class="form-group">   
                                    <label for="location">Event Location</label>   
                                    <input type="text" class="form-control" id="location" name="location" placeholder="Enter Event Location" required="true">   
                                </div>  
                                <div class="form-group">   
                                    <label for="event_date">Event Date</label>  
                                    <input type="date" class="form-control" id="event_date" name="event_date" required="true">  
                                </div>  
                                <div class="form-group">   
                                    <label for="ticket_price">Ticket Price</label>  
                                    <input type="text" class="form-control" id="ticket_price" name="ticket_price" required="true">  
                                </div>  
                                <div class="form-group">   
                                    <label for="status">Event Status</label>  
                                    <select class="form-control" id="status" name="status" required="true">  
                                        <option value="">Select Status</option>  
                                        <option value="upcoming">Upcoming</option>
                                        <option value="live">Live</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option> 
                                    </select>  
                                </div>  
                                <div class="form-group">   
                                    <label for="image">Upload Image</label>  
                                    <input type="file" class="form-control" id="image" name="image" required="true">  
                                </div>  
                                
                                <button type="submit" name="submit" class="btn btn-primary">Create Event</button>   
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
