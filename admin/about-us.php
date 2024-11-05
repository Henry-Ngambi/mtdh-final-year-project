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

// Update About Us content when form is submitted
if (isset($_POST['submit'])) {
    $mission = mysqli_real_escape_string($con, $_POST['mission']);
    $what_we_do = mysqli_real_escape_string($con, $_POST['what_we_do']);
    $our_team = mysqli_real_escape_string($con, $_POST['our_team']);

    // Assuming a table called `about_us` exists with columns `mission`, `what_we_do`, and `our_team`
    $query = mysqli_query($con, "UPDATE about_us SET mission='$mission', what_we_do='$what_we_do', our_team='$our_team' WHERE id=1");
    
    if ($query) {
        $msg = "About Us content updated successfully!";
    } else {
        $msg = "Error updating content: " . mysqli_error($con);
    }
}

// Fetch current About Us content from the database
$result = mysqli_query($con, "SELECT * FROM about_us WHERE id=1");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "No content found for About Us. Please check the database.";
    exit;
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>BPMS | About Us</title>
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
        table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            overflow-x: auto; /* Allows horizontal scrolling if needed */
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
                    <h3 class="title1">About Us</h3>
                    
                    <?php if (isset($msg)) { echo "<p style='color: green;'>$msg</p>"; } ?>
                    
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="mission">Our Mission:</label>
                            <textarea name="mission" id="mission" rows="4" class="form-control" required><?php echo isset($row['mission']) ? $row['mission'] : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="what_we_do">What We Do:</label>
                            <textarea name="what_we_do" id="what_we_do" rows="4" class="form-control" required><?php echo isset($row['what_we_do']) ? $row['what_we_do'] : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="our_team">Our Team:</label>
                            <textarea name="our_team" id="our_team" rows="4" class="form-control" required><?php echo isset($row['our_team']) ? $row['our_team'] : ''; ?></textarea>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
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
            // Fetch existing About Us data when the page loads
            $.ajax({
                url: 'get_about_us.php', // Fetch current content
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'error') {
                        console.error('Error fetching About Us data:', data.message);
                    } else {
                        $('#mission').val(data.mission);
                        $('#what_we_do').val(data.what_we_do);
                        $('#our_team').val(data.our_team);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        });
    </script>
</body>
</html>
