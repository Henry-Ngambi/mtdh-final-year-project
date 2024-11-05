<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the session is active
if (strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>MTDH | Book Event</title>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
</head> 
<body class="cbp-spmenu-push">
    <div class="main-content">
        <!--left-fixed -navigation-->
        <?php include_once('includes/sidebar.php'); ?>
        <!--left-fixed -navigation-->
        <!-- header-starts -->
        <?php include_once('includes/header.php'); ?>
        <!-- //header-ends -->
        <!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Available Events and Bookings</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Event Details with Bookings:</h4>
                        <table class="table table-bordered"> 
                            <thead> 
                                <tr> 
                                    <th>#</th> 
                                    <th>Event Title</th> 
                                    <th>Description</th>
                                    <th>Event Date</th>
                                    <th>Location</th>
                                    <th>Ticket Price</th>
                                    <th>Bookings</th>
                                    <th>Action</th>
                                </tr> 
                            </thead> 
                            <tbody>
<?php
// SQL query to fetch event details along with the count of bookings
$query = "
    SELECT 
        e.event_id, e.title, e.description, e.event_date, e.location, e.ticket_price,
        COUNT(b.booking_id) AS booking_count
    FROM 
        events e
    LEFT JOIN 
        bookings b ON e.event_id = b.event_id
    GROUP BY 
        e.event_id
    ORDER BY 
        e.event_date ASC
";

$result = mysqli_query($con, $query);
$cnt = 1;
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
                                <tr> 
                                    <th scope="row"><?php echo $cnt; ?></th> 
                                    <td><?php echo $row['title']; ?></td> 
                                    <td><?php echo $row['description']; ?></td>
                                    <td><?php echo $row['event_date']; ?></td>
                                    <td><?php echo $row['location']; ?></td>
                                    <td><?php echo $row['ticket_price']; ?></td>
                                    <td><?php echo $row['booking_count']; ?> Bookings</td>
                                    <td><a href="view-bookings.php?event_id=<?php echo $row['event_id']; ?>" class="btn btn-primary btn-sm">View</a></td>
                                </tr>
<?php
        $cnt++;
    }
} else {
    echo "<tr><td colspan='8'>No events or bookings found.</td></tr>";
}
?>
                            </tbody> 
                        </table> 
                    </div>
                </div>
            </div>
        </div>
        <!--footer-->
        <?php include_once('includes/footer.php'); ?>
        <!--//footer-->
    </div>
    <!-- Classie -->
    <script src="js/classie.js"></script>
    <script>
        var menuLeft = document.getElementById('cbp-spmenu-s1'),
            showLeftPush = document.getElementById('showLeftPush'),
            body = document.body;
            
        showLeftPush.onclick = function () {
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
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.js"> </script>
</body>
</html>
<?php } ?>
