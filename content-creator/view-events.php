<?php  
session_start();  
error_reporting(E_ALL);  
ini_set('display_errors', 1);  
include('includes/dbconnection.php');  

if (strlen($_SESSION['user_id'] == 0)) {  
    header('location:logout.php');  
} else {  
    $msg = "";  

    // Handle deletion of events  
    if (isset($_GET['delid'])) {  
        $event_id = $_GET['delid'];  
        $stmt = $con->prepare("DELETE FROM events WHERE event_id = ?");  
        $stmt->bind_param("i", $event_id); // assuming event_id is an integer
        if ($stmt->execute()) {  
            $msg = "Event deleted successfully.";  
        } else {  
            $msg = "Error deleting event: " . $stmt->error;  
            error_log($stmt->error);  
        }  
        $stmt->close();  
    }  
?>  

<!DOCTYPE HTML>  
<html>  
<head>  
    <title>MTDH | View Events</title>  
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
    <style>  
        .page-header {  
            display: flex;  
            justify-content: space-between;  
            align-items: center;  
        }  
        .btn-create-event {  
            margin-top: 10px;  
        }  
        img.event-image {  
            width: 100px;  
            height: 100px;  
            object-fit: cover;  
            border-radius: 5px;  
        }  
        .table th, .table td {  
            text-align: center;  
            vertical-align: middle;  
        }  
        .btn {  
            margin-right: 5px;  
        }  
        .alert {  
            margin: 10px 0;  
        }  
    </style>  
</head>   
<body class="cbp-spmenu-push">  
    <div class="main-content">  
        <?php include_once('includes/sidebar.php'); ?>  
        <?php include_once('includes/header.php'); ?>  

        <div id="page-wrapper">  
            <div class="main-page">  
                <div class="tables">  
                    <div class="page-header">  
                        <h3 class="title1">View Events</h3>  
                        <a href="create-events.php" class="btn btn-primary btn-create-event">Create New Event</a>  
                    </div>  
                    
                    <!-- Display success or error message -->
                    <?php if ($msg): ?>
                        <div class="alert alert-info">
                            <?php echo htmlspecialchars($msg); ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive bs-example widget-shadow">  
                        <h4>All Events:</h4>  
                        <table class="table table-bordered table-striped">  
                            <thead>  
                                <tr>  
                                    <th>#</th>  
                                    <th>Event Title</th>  
                                    <th>Description</th>  
                                    <th>Date</th>  
                                    <th>Location</th>  
                                    <th>Ticket Price</th>  
                                    <th>Image</th>  
                                    <th>Status</th>  
                                    <th>Action</th>  
                                </tr>  
                            </thead>  
                            <tbody>  
<?php  
$ret = mysqli_query($con, "SELECT * FROM events ORDER BY created_at DESC");  
$cnt = 1;  
while ($row = mysqli_fetch_array($ret)) {  
?>  
                                <tr>  
                                    <th scope="row"><?php echo $cnt; ?></th>  
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>  
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>  
                                    <td><?php echo htmlspecialchars($row['event_date']); ?></td>  
                                    <td><?php echo htmlspecialchars($row['location']); ?></td>  
                                    <td>$<?php echo htmlspecialchars($row['ticket_price']); ?></td>  
                                    <td><img src="<?php echo htmlspecialchars($row['image']); ?>" class="event-image" alt="Event Image"></td>  
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>  
                                    <td>  
                                        <a href="edit-event.php?event_id=<?php echo $row['event_id']; ?>" class="btn btn-primary">Edit</a>  
                                        <a href="view-events.php?delid=<?php echo $row['event_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>  
                                    </td>  
                                </tr>  
<?php  
    $cnt++;  
}  
?>  
                            </tbody>  
                        </table>  
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
