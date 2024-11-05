<div class="sticky-header header-section ">
    <div class="header-left">
        <!--toggle button start-->
        <button id="showLeftPush"><i class="fa fa-bars"></i></button>
        <!--toggle button end-->
        <!--logo -->
        <div class="logo">
            <a href="index.html">
                <h1>MTDH</h1>
                <span>Administrator Panel</span>
            </a>
        </div>
        <!--//logo-->
        <div class="clearfix"> </div>
    </div>

    <div class="header-right">
        <div class="profile_details">
            <?php
            $adid = $_SESSION['user_id'];
            $stmt = $con->prepare("SELECT full_name, profile_image FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $adid);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $userName = $row['full_name'];
            $profileImage = $row['profile_image']; // Fetch the profile image
            
            // Check if the profile image exists; if not, set to default avatar
            if (empty($profileImage) || !file_exists($profileImage)) {
                $profileImage = '../public/images/profile.jpeg'; // Path to default avatar
            }
            ?>
            <ul>
                <li class="dropdown profile_details_drop">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <div class="profile_img">
                            <span class="prfil-img">
                                <img src="<?php echo $profileImage; ?>" alt="Profile Image">
                            </span>
                            <div class="user-name">
                                <p><?php echo $userName; ?></p>
                                <span>The Administrator</span>
                            </div>
                            <i class="fa fa-angle-down lnr"></i>
                            <i class="fa fa-angle-up lnr"></i>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                    <ul class="dropdown-menu drp-mnu">
                        <li><a href="change-password.php"><i class="fa fa-cog"></i> Settings</a></li>
                        <li><a href="admin-profile.php"><i class="fa fa-user"></i> Profile</a></li>
                        <li><a href="../user/index.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="clearfix"> </div>
    </div>
    <div class="clearfix"> </div>
</div>
