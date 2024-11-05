<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the session is active
if (strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
} else {
    $event_id = intval($_GET['event_id']); // Sanitize event_id
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>MTDH | View Bookings</title>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
</head> 
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>
        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Booking Details for Event ID: <?php echo htmlspecialchars($event_id); ?></h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Booking Details:</h4>
                        <table class="table table-bordered"> 
                            <thead> 
                                <tr> 
                                    <th>#</th> 
                                    <th>User Name</th> 
                                    <th>Booking Date</th>
                                    <th>Payment Status</th>
                                    <th>Total Amount</th>
                                </tr> 
                            </thead> 
                            <tbody>
<?php
$query = "
    SELECT 
        u.full_name, b.booking_date, b.payment_status, b.total_amount
    FROM 
        bookings b
    INNER JOIN 
        users u ON b.user_id = u.user_id
    WHERE 
        b.event_id = ?
";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $event_id); // Bind event_id as an integer
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$cnt = 1;
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
                                <tr> 
                                    <th scope="row"><?php echo $cnt; ?></th> 
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td> 
                                    <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                                </tr>
<?php
        $cnt++;
    }
} else {
    echo "<tr><td colspan='5'>No bookings found for this event.</td></tr>";
}
mysqli_stmt_close($stmt); // Close the statement
?>
                            </tbody> 
                        </table> 
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</body>
</html>
<?php } ?>
