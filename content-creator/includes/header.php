<div class="sticky-header header-section ">
    <div class="header-left">
        <!--toggle button start-->
        <button id="showLeftPush"><i class="fa fa-bars"></i></button>
        <!--toggle button end-->
        <!--logo -->
        <div class="logo">
            <a href="index.html">
                <h1>MTDH</h1>
                <span>Content Creator Panel</span>
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
            $userName = htmlspecialchars($row['full_name']);
            $profileImage = !empty($row['profile_image']) ? htmlspecialchars($row['profile_image']) : '../public/images/profile.jpeg'; // Use a default avatar if no image is available
            ?>
            <ul>
                <li class="dropdown profile_details_drop">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <div class="profile_img">
                            <span class="prfil-img"><img src="<?php echo $profileImage; ?>" alt="" width="50" height="60"></span>
                            <div class="user-name">
                                <p><?php echo $userName; ?></p>
                                <span>The Creator</span>
                            </div>
                            <i class="fa fa-angle-down lnr"></i>
                            <i class="fa fa-angle-up lnr"></i>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                    <ul class="dropdown-menu drp-mnu">
                        <li><a href="change-password.php"><i class="fa fa-cog"></i> Settings</a></li>
                        <li><a href="creator-profile.php"><i class="fa fa-user"></i> Profile</a></li>
                        <li><a href="../user/index.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="clearfix"> </div>
    </div>
    <div class="clearfix"> </div>
</div>
