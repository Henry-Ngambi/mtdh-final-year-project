<?php  
session_start();  
error_reporting(E_ALL); // Show all errors for debugging  
ini_set('display_errors', 1);  
include('includes/dbconnection.php');  

if (strlen($_SESSION['user_id'] == 0)) {  
    header('location:logout.php');  
} else {  
    $msg = ""; // Initialize the $msg variable  

    // Handle form submission for updating the event
    if (isset($_POST['update'])) {  
        $event_id = $_POST['event_id'];  
        $title = $_POST['title'];  
        $description = $_POST['description'];  
        $event_date = $_POST['event_date'];  
        $location = $_POST['location'];  
        $ticket_price = $_POST['ticket_price'];  
        $status = $_POST['status'];  

        // Handle image upload
        $image = $_FILES['image']['name'];  
        $image_temp = $_FILES['image']['tmp_name'];  
        $folder_image = "../uploads/event_images/" . basename($image);  

        if ($image) {  
            // Validate image upload  
            $image_extension = strtolower(pathinfo($folder_image, PATHINFO_EXTENSION));  
            $image_extensions_arr = array("jpg", "jpeg", "png", "gif");  

            if (in_array($image_extension, $image_extensions_arr)) {  
                // Move uploaded image  
                if (move_uploaded_file($image_temp, $folder_image)) {  
                    // Update the event in the database
                    $query = mysqli_query($con, "UPDATE events SET title='$title', description='$description', event_date='$event_date', location='$location', ticket_price='$ticket_price', status='$status', image='$folder_image' WHERE event_id='$event_id'");
                } else {  
                    $msg = "Failed to upload image.";  
                }  
            } else {  
                $msg = "Invalid image file extension.";  
            }  
        } else {  
            // If no new image is uploaded, just update other fields
            $query = mysqli_query($con, "UPDATE events SET title='$title', description='$description', event_date='$event_date', location='$location', ticket_price='$ticket_price', status='$status' WHERE event_id='$event_id'");
        }

        if ($query) {  
            $msg = "Event updated successfully.";  
            // Redirect after a short delay
            header("Refresh:2; url=view-events.php");  
        } else {  
            $msg = "Error updating event.";  
        }  
    }

    // Fetch the current event details
    if (isset($_GET['event_id'])) {  
        $event_id = $_GET['event_id'];  
        $result = mysqli_query($con, "SELECT * FROM events WHERE event_id='$event_id'");
        $event = mysqli_fetch_array($result);  
    }  
?>

<!DOCTYPE HTML>  
<html>  
<head>  
    <title>MTDH | Edit Event</title>  
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />  
    <link href="css/style.css" rel='stylesheet' type='text/css' />  
    <link href="css/font-awesome.css" rel="stylesheet">   
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">  
    <link href="css/custom.css" rel="stylesheet">  
    <script src="js/jquery-1.11.1.min.js"></script>  
    <script src="js/bootstrap.js"></script>  
</head>  
<body class="cbp-spmenu-push">  
    <div class="main-content">  
        <?php include_once('includes/sidebar.php'); ?>  
        <?php include_once('includes/header.php'); ?>  

        <div id="page-wrapper">  
            <div class="main-page">  
                <div class="forms">  
                    <h3 class="title1">Edit Event</h3>  
                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">   
                        <div class="form-title">  
                            <h4>Event Details:</h4>  
                        </div>  
                        <div class="form-body">  
                            <form method="post" enctype="multipart/form-data">  
                                <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">  
                                <p style="font-size:16px; color:red" align="center">   
                                    <?php if ($msg) { echo $msg; } ?>  
                                </p>  
                                <div class="form-group">   
                                    <label for="title">Event Title</label>   
                                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" placeholder="Enter Event Title" required="true">   
                                </div>  
                                <div class="form-group">   
                                    <label for="description">Event Description</label>   
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter Event Description" required><?php echo htmlspecialchars($event['description']); ?></textarea>  
                                </div>  
                                <div class="form-group">   
                                    <label for="location">Event Location</label>   
                                    <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" placeholder="Enter Event Location" required="true">   
                                </div>  
                                <div class="form-group">   
                                    <label for="event_date">Event Date</label>  
                                    <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event['event_date']); ?>" required="true">  
                                </div>  
                                <div class="form-group">   
                                    <label for="ticket_price">Ticket Price</label>  
                                    <input type="text" class="form-control" id="ticket_price" name="ticket_price" value="<?php echo htmlspecialchars($event['ticket_price']); ?>" required="true">  
                                </div>  
                                <div class="form-group">   
                                    <label for="status">Event Status</label>  
                                    <select class="form-control" id="status" name="status" required="true">  
                                        <option value="upcoming" <?php if ($event['status'] == 'upcoming') echo 'selected'; ?>>Upcoming</option>
                                        <option value="live" <?php if ($event['status'] == 'live') echo 'selected'; ?>>Live</option>
                                        <option value="completed" <?php if ($event['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                        <option value="cancelled" <?php if ($event['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option> 
                                    </select>  
                                </div>  
                                <div class="form-group">   
                                    <label for="image">Upload New Image (Leave blank if no change)</label>  
                                    <input type="file" class="form-control" id="image" name="image">  
                                </div>  
                                <button type="submit" name="update" class="btn btn-default">Update Event</button>   
                                <p style="font-size:16px; color:green" align="center">   
                                    <?php if ($msg == "Event updated successfully.") { echo $msg; } ?>  
                                </p>  
                            </form>   
                        </div>  
                    </div>  
                </div>  
            </div>  
        </div>  
        <?php include_once('includes/footer.php'); ?>  
    </div>  
</body>  
</html>  

<?php } ?>
