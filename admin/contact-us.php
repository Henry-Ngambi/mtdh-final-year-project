<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
    exit;
}

// Update Contact Us content when the form is submitted
if (isset($_POST['submit'])) {
    $pagetitle = mysqli_real_escape_string($con, $_POST['pagetitle']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $mobnumber = mysqli_real_escape_string($con, $_POST['mobnumber']);
    $timing = mysqli_real_escape_string($con, $_POST['timing']);
    $pagedes = mysqli_real_escape_string($con, $_POST['pagedes']);

    // Assuming a table called `contact_us` exists with the relevant columns
    $query = mysqli_query($con, "UPDATE contact_us SET PageTitle='$pagetitle', Email='$email', MobileNumber='$mobnumber', Timing='$timing', PageDescription='$pagedes' WHERE PageType='contactus'");
    
    if ($query) {
        $msg = "Contact Us information updated successfully!";
    } else {
        $msg = "Error updating content: " . mysqli_error($con);
    }
}

// Fetch current Contact Us content from the database
$result = mysqli_query($con, "SELECT * FROM contact_us WHERE PageType='contactus'");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "No content found for Contact Us. Please check the database.";
    exit;
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>BPMS | Contact Us</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/custom.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <script src="js/wow.min.js"></script>
    <script>new WOW().init();</script>
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <style>
        .header-buttons {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .modal-backdrop.show {
            opacity: 0.5; /* Makes modal background darker */
        }
        .footer {
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <!-- Main content start -->
        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Contact Us</h3>
                    
                    <?php if (isset($msg)) { echo "<p style='color: green;'>$msg</p>"; } ?>
                    
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="pagetitle">Page Title:</label>
                            <input type="text" name="pagetitle" id="pagetitle" class="form-control" value="<?php echo isset($row['PageTitle']) ? $row['PageTitle'] : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($row['Email']) ? $row['Email'] : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="mobnumber">Mobile Number:</label>
                            <input type="text" name="mobnumber" id="mobnumber" class="form-control" value="<?php echo isset($row['MobileNumber']) ? $row['MobileNumber'] : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="timing">Timing:</label>
                            <input type="text" name="timing" id="timing" class="form-control" value="<?php echo isset($row['Timing']) ? $row['Timing'] : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="pagedes">Page Description:</label>
                            <textarea name="pagedes" id="pagedes" rows="5" class="form-control" required><?php echo isset($row['PageDescription']) ? $row['PageDescription'] : ''; ?></textarea>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
        <!--footer-->
        <footer class="footer">
            <div class="container">
                <p class="text-muted">© 2024 Malawi Traditional Dances Hub. All rights reserved.</p>
            </div>
        </footer>
        <!--//footer-->
    </div>

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
    <script src="js/bootstrap.js"></script>

	<script>
        $(document).ready(function() {
            // AJAX call to fetch contact us data
            $.ajax({
                url: 'fetch_contact_us.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (!data.error) {
                        $('#pagetitle').val(data.PageTitle);
                        $('#email').val(data.Email);
                        $('#mobnumber').val(data.MobileNumber);
                        $('#timing').val(data.Timing);
                        $('#pagedes').val(data.PageDescription);
                    } else {
                        console.log(data.error);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('AJAX Error: ' + textStatus);
                }
            });
        });
    </script>
</body>
</html>
