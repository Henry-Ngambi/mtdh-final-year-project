<?php  
// Include database connection  
require_once '../includes/dbconnection.php';  

// Check if the session is not already started before starting it  
if (session_status() == PHP_SESSION_NONE) {  
    session_start();  
}  

// Check if the user role is set to regular user
if (isset($_SESSION['role_id']) && $_SESSION['role_id'] !== 1) { // 1 role_id for regular users
    session_unset();
    session_destroy();
    header("Location: ../user/index.php");
    exit();
}

// Check if the profile image exists; if not, set to default avatar or create circular icon
$profileImage = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : ''; // Get profile image from session
if (empty($profileImage) || !file_exists("../uploads/profiles/" . $profileImage)) {
    $profileImage = ''; // Leave empty to create circular icon with the user's initials
}
?>  

<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title><?php echo $title ?? 'Malawi Traditional Dances Hub'; ?></title>  
    <link rel="stylesheet" href="style.css"> 
    <link href="bootstrap.css" rel='stylesheet' type='text/css' /> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->  
</head>  
<body>  

<nav class="flex-div">  
    <div class="nav-left flex-div">  
        <img src="../public/images/menu1.png" class="menu-icon clicked" alt="Menu Icon">  
        <img src="../public/images/logo.png" class="logo" alt="MTDH">  
    </div>  
    <div class="nav-middle flex-div">  
        <div class="search-box flex-div">  
            <input type="text" placeholder="Search" id="searchInput">  
            <img src="../public/images/search.png" alt="Search Icon">  
            <div id="searchResults" style="position: absolute; top: 40px; background: #fff; width: 100%; max-height: 200px; overflow-y: auto; display: none;"></div>
        </div>  
    </div>  
    <div class="nav-right flex-div">  
        <div class="user-section">  
            <?php if (isset($_SESSION['full_name'])): ?>  
                <?php if (!empty($profileImage)): ?>  
                    <img src="<?php echo htmlspecialchars($profileImage); ?>" class="user-icon dropdown-toggle" data-toggle="dropdown" alt="User Icon">  
                <?php else: ?>  
                    <div class="profile-initials dropdown-toggle" data-toggle="dropdown"><?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?></div>  
                <?php endif; ?>  
                <div class="profile-card" id="profileCard">  
                    <h4><?php echo htmlspecialchars($_SESSION['full_name']); ?></h4>  
                    <a href="../user/manage-profile.php"><i class="fa fa-user"></i>Profile</a>  
                    <a href="../user/change-password.php"><i class="fa fa-cog"></i>Passwords</a>  
                    <a href="#" onclick="confirmLogout(event)"><i class="fa fa-sign-out"></i>Logout</a>  
                </div>  
            <?php else: ?>  
                <a href="../public/login.php">Login</a>  
            <?php endif; ?>  
        </div>  
    </div>  
</nav>  

<script>  
    $(document).ready(function() {
        // Toggle profile card on icon click
        $('.dropdown-toggle').click(function() {  
            $('#profileCard').toggle();
        });  

        // Hide profile card when clicking outside
        $(document).mouseup(function(e) {  
            var container = $("#profileCard");  
            if (!container.is(e.target) && container.has(e.target).length === 0) {  
                container.hide();  
            }  
        });

        // Menu icon color change on click
        $('.menu-icon').on('mousedown', function() {
            $(this).toggleClass('clicked');
        });

        // Toggle search box border color on focus
        $('#searchInput').on('focus', function() {
            $(this).parent('.search-box').addClass('active');
        }).on('blur', function() {
            $(this).parent('.search-box').removeClass('active');
        });

        // Real-time search with AJAX request
        $('#searchInput').on('input', function() {
            var query = $(this).val().toLowerCase();
            if (query.length > 0) {
                $.ajax({
                    url: 'search_videos.php',
                    type: 'GET',
                    data: { query: query },
                    success: function(response) {
                        $('#searchResults').show().html(response);
                    },
                    error: function() {
                        $('#searchResults').show().html('<p>An error occurred while searching.</p>');
                    }
                });
            } else {
                $('#searchResults').hide();
            }
        });
    });

    // Confirm logout
    function confirmLogout(event) {  
        event.preventDefault();  
        var confirmed = confirm("Are you sure you want to log out?");  
        if (confirmed) {  
            window.location.href = "../public/logout.php";  
        }  
    }  
</script>  

</body>  
</html>
