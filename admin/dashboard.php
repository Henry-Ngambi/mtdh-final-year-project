<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['user_id'] == 0)) {
    header('location:logout.php');
} 
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>MTDH | Admin Dashboard</title>

    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <!-- font CSS -->
    <!-- font-awesome icons -->
    <link href="css/font-awesome.css" rel="stylesheet"> 
    <!-- //font-awesome icons -->
    <!-- js-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <!--webfonts-->
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <!--//webfonts--> 
    <!--animate-->
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
    <!--//end-animate-->
    <!-- chart -->
    <script src="js/Chart.js"></script>
    <!-- //chart -->
    <!--Calender-->
    <link rel="stylesheet" href="css/clndr.css" type="text/css" />
    <script src="js/underscore-min.js" type="text/javascript"></script>
    <script src="js/moment-2.2.1.js" type="text/javascript"></script>
    <script src="js/clndr.js"></script>
    <script src="js/site.js"></script>
    <!--End Calender-->
    <!-- Metis Menu -->
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <link href="css/custom.css" rel="stylesheet">
    <!--//Metis Menu -->
</head> 
<body class="cbp-spmenu-push">
<div class="main-content">

    <?php include_once('includes/sidebar.php'); ?>
    
    <?php include_once('includes/header.php'); ?>
    
    <!-- main content start-->
    <div id="page-wrapper" class="row calender widget-shadow">
        <div class="main-page">

            <!-- Row 1: Users -->
            <div class="row calender widget-shadow">
                <div class="row-one">
                    <div class="col-md-4 widget">
                        <?php 
                        // Fetch total users
                        $queryUsers = mysqli_query($con, "SELECT COUNT(user_id) AS total_users FROM users");
                        $userData = mysqli_fetch_array($queryUsers);
                        ?>
                        <a href="user_list.php" style="text-decoration: none; color: inherit;">
                            <div class="stats-left" style="text-align: center;">
                                <h5>Total Users</h5>
                                <h4><?php echo $userData['total_users']; ?></h4>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4 widget">
                        <?php 
                        // Fetch total content creators
                        $queryCreators = mysqli_query($con, "SELECT COUNT(user_id) AS total_creators FROM users WHERE role_id = (SELECT role_id FROM roles WHERE role_name = 'content_creator')");
                        $creatorData = mysqli_fetch_array($queryCreators);
                        ?>
                        <div class="stats-left" style="text-align: center;">
                            <h5>Total Content Creators</h5>
                            <h4><?php echo $creatorData['total_creators']; ?></h4>
                        </div>
                    </div>

                    <div class="col-md-4 widget">
                        <?php 
                        // Fetch total admins
                        $queryAdmins = mysqli_query($con, "SELECT COUNT(user_id) AS total_admins FROM users WHERE role_id = (SELECT role_id FROM roles WHERE role_name = 'admin')");
                        $adminData = mysqli_fetch_array($queryAdmins);
                        ?>
                        <div class="stats-left" style="text-align: center;">
                            <h5>Total Admins</h5>
                            <h4><?php echo $adminData['total_admins']; ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Events, Bookings, Subscribers -->
            <div class="row calender widget-shadow">
                <div class="row-one">
                    <div class="col-md-4 widget">
                        <?php 
                        // Fetch total events
                        $queryEvents = mysqli_query($con, "SELECT COUNT(event_id) AS total_events FROM events");
                        $eventData = mysqli_fetch_array($queryEvents);
                        ?>
                        <div class="stats-left" style="text-align: center;">
                            <h5>Total Events</h5>
                            <h4><?php echo $eventData['total_events']; ?></h4>
                        </div>
                    </div>

                    <div class="col-md-4 widget">
                        <?php 
                        // Fetch total bookings
                        $queryBookings = mysqli_query($con, "SELECT COUNT(booking_id) AS total_bookings FROM bookings");
                        $bookingData = mysqli_fetch_array($queryBookings);
                        ?>
                        <div class="stats-left" style="text-align: center;">
                            <h5>Total Bookings</h5>
                            <h4><?php echo $bookingData['total_bookings']; ?></h4>
                        </div>
                    </div>

                    <div class="col-md-4 widget">
                        <?php 
                        // Fetch total subscribers
                        $querySubscribers = mysqli_query($con, "SELECT COUNT(user_id) AS total_subscribers FROM users WHERE is_subscribed = 1");
                        $subscriberData = mysqli_fetch_array($querySubscribers);
                        ?>
                        <div class="stats-left" style="text-align: center;">
                            <h5>Total Subscribers</h5>
                            <h4><?php echo $subscriberData['total_subscribers']; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="clearfix"> </div>
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
<!--scrolling js-->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!--//scrolling js-->
</body>
</html>
