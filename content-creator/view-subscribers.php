<?php  
session_start();  
error_reporting(E_ALL); // Show all errors for debugging  
ini_set('display_errors', 1);  
include('includes/dbconnection.php');  

// Check if the user is logged in
if (strlen($_SESSION['user_id']) == 0) {  
    header('location:logout.php');  
    exit();
} else {  
    // Fetch all subscribers with their subscription plans
    $subscribers = mysqli_query($con, "
        SELECT u.user_id, u.full_name, u.email, u.phone_number, sp.plan_name, sp.price, u.created_at
        FROM users u
        JOIN subscription_plan sp ON u.is_subscribed = 1 AND u.subscription_status = sp.plan_id
        ORDER BY u.created_at DESC
    ");  
?>  

<!DOCTYPE HTML>  
<html>  
<head>  
    <title>MTDH | Subscribers</title>  
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
                <div class="tables">  
                    <h3 class="title1">List of Subscribers</h3>  

                    <div class="table-responsive bs-example widget-shadow">  
                        <h4>Subscribers of the Platform:</h4>  
                        <table class="table table-bordered">  
                            <thead>  
                                <tr>  
                                    <th>#</th>  
                                    <th>Full Name</th>  
                                    <th>Email</th>  
                                    <th>Phone Number</th>  
                                    <th>Subscription Plan</th>  
                                    <th>Price ($)</th>  
                                    <th>Subscribed At</th>  
                                </tr>  
                            </thead>  
                            <tbody>  
                                <?php  
                                $cnt = 1;  
                                while ($row = mysqli_fetch_array($subscribers)) {  
                                ?>  
                                    <tr>  
                                        <th scope="row"><?php echo $cnt; ?></th>  
                                        <td><?php echo $row['full_name']; ?></td>  
                                        <td><?php echo $row['email']; ?></td>  
                                        <td><?php echo $row['phone_number']; ?></td>  
                                        <td><?php echo $row['plan_name']; ?></td>  
                                        <td><?php echo $row['price']; ?></td>  
                                        <td><?php echo $row['created_at']; ?></td>  
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
