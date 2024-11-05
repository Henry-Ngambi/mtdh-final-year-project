<?php
session_start();
include('includes/dbconnection.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM live_streams WHERE id = $id";
    $result = mysqli_query($con, $query);
    $stream = mysqli_fetch_assoc($result);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = mysqli_real_escape_string($con, $_POST['title']);
        $description = mysqli_real_escape_string($con, $_POST['description']);
        
        $update_query = "UPDATE live_streams SET title = '$title', description = '$description' WHERE id = $id";
        if (mysqli_query($con, $update_query)) {
            header("Location: manage-live-stream.php?message=Live Stream Updated");
        } else {
            $message = "Error updating live stream.";
        }
    }
} else {
    header("Location: manage-live-stream.php");
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Edit Live Stream</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="container">
        <h3>Edit Live Stream</h3>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $stream['title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required><?php echo $stream['description']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Live Stream</button>
        </form>
    </div>
</body>
</html>
