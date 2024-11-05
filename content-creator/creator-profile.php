<?php
session_start();
error_reporting(E_ALL); // Show all errors
ini_set('display_errors', 1); // Display errors during development
include('includes/dbconnection.php');

if (empty($_SESSION['user_id'])) {
    header('location:logout.php');
    exit; // Make sure to exit after header redirection
}

$msg = ""; // Initialize message variable

if (isset($_POST['submit'])) {
    $adminid = $_SESSION['user_id'];
    $aname = trim($_POST['full_name']);
    $mobno = trim($_POST['phone']);
    $nationality = trim($_POST['nationality']);
    $city = trim($_POST['city']);

    // Handle file upload for profile image
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "../uploads/profiles/"; // Directory to save uploaded files
        $filename = basename($_FILES["profile_image"]["name"]);
        $sanitized_filename = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $filename); // Sanitize filename
        $target_file = $target_dir . $sanitized_filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
            // Attempt to move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                // File upload successful
                $profile_image = $target_file; // Path for database
            } else {
                $msg = "Sorry, there was an error uploading your file.";
            }
        } else {
            $msg = "File is not a valid image.";
        }
    } else {
        $profile_image = $_POST['existing_image']; // Use existing image if no new upload
    }

    // Update user information in the database
    if (empty($msg)) { // Proceed only if there are no file upload errors
        $stmt = $con->prepare("UPDATE users SET full_name = ?, phone_number = ?, nationality = ?, city = ?, profile_image = ? WHERE user_id = ?");
        $stmt->bind_param("sssssi", $aname, $mobno, $nationality, $city, $profile_image, $adminid);

        if ($stmt->execute()) {
            $msg = "Admin profile has been updated successfully.";
        } else {
            $msg = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>MTDH | Update Your Profile</title>
    <script type="application/x-javascript">
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);
        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <!-- font CSS -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- js -->
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
    <!-- Metis Menu -->
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <link href="css/custom.css" rel="stylesheet">
</head>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <!-- Left Fixed - Navigation -->
        <?php include_once('includes/sidebar.php'); ?>
        <!-- Header Starts -->
        <?php include_once('includes/header.php'); ?>
        <!-- Main Content Start -->
        <div id="page-wrapper">
            <div class="main-page">
                <div class="forms">
                    <h3 class="title1">Update Your Profile</h3>
                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">
                        <div class="form-title">
                            <h4>Update Profile :</h4>
                        </div>
                        <div class="form-body">
                            <form method="post" enctype="multipart/form-data">
                                <p style="font-size:16px; color:red" align="center">
                                    <?php echo $msg; ?>
                                </p>

                                <?php
                                $adminid = $_SESSION['user_id'];
                                $ret = mysqli_query($con, "SELECT * FROM users WHERE user_id='$adminid'");
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <div class="form-group">
                                        <label for="full_name">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" value="<?php echo htmlspecialchars($row['full_name']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Contact Number</label>
                                        <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone_number']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" readonly='true'>
                                    </div>
                                    <div class="form-group">
                                        <label for="nationality">Nationality</label>
                                        <input type="text" class="form-control" id="nationality" name="nationality" placeholder="Nationality" value="<?php echo htmlspecialchars($row['nationality']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" name="city" placeholder="City" value="<?php echo htmlspecialchars($row['city']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="profile_image">Profile Image</label>
                                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                                        <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($row['profile_image']); ?>">
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-default">Update</button>
                                </form>
                                <?php } // End of while loop ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
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
    <!-- Scrolling JS -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.js"></script>
</body>
</html>
