<?php
session_start();
include '../includes/dbconnection.php';  

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize message variable
$msg = ""; 

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
                $profile_image = $sanitized_filename; // Save only the filename (not the path) for database
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
        $stmt->bind_param("sssssi", $aname, $mobno, $nationality, $city, $profile_image, $adminid); // Store only the filename
        
        if ($stmt->execute()) {
            $msg = "Your profile has been updated successfully.";
        } else {
            $msg = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
}

// Fetch user data for the form
$adminid = $_SESSION['user_id'];
$ret = mysqli_query($con, "SELECT * FROM users WHERE user_id='$adminid'");
$row = mysqli_fetch_array($ret);

// Include header and sidebar at the beginning
include('header.php'); 
include('sidebar.php');
?>

<div class="container">
    <div class="form-body">
        <h2 class="text-center">Update Your Profile</h2>
        <form method="post" enctype="multipart/form-data">
            <p style="font-size:16px; color:red" align="center"><?php echo $msg; ?></p>
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" value="<?php echo htmlspecialchars($row['full_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Contact Number</label>
                <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone_number'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>" readonly='true'>
            </div>
            <div class="form-group">
                <label for="nationality">Nationality</label>
                <input type="text" class="form-control" id="nationality" name="nationality" placeholder="Nationality" value="<?php echo htmlspecialchars($row['nationality'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="City" value="<?php echo htmlspecialchars($row['city'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_image">Profile Image</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($row['profile_image'] ?? ''); ?>">
                <small class="form-text text-muted">Leave blank to keep the existing image.</small>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
