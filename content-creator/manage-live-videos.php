<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
} else {
    $openCamera = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = mysqli_real_escape_string($con, $_POST['title']);
        $description = mysqli_real_escape_string($con, $_POST['description']);
        
        $stream_url = "https://yourstreamingplatform.local/live/" . uniqid();

        $query = "INSERT INTO live_streams (title, description, stream_url, is_active) VALUES ('$title', '$description', '$stream_url', 1)";
        if (mysqli_query($con, $query)) {
            $message = "Live Stream created successfully.";
            $openCamera = true; // Set this to true to open the camera modal
        } else {
            $message = "Error creating live stream.";
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>MTDH | Manage Live Videos</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.js"></script>

    <style>
        .main-content {
            padding-top: 70px;
        }
        .message {
            color: green;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Manage Live Videos</h3>
                    
                    <?php if (!empty($message)) { ?>
                        <p class="message"><?php echo $message; ?></p>
                    <?php } ?>

                    <!-- Form to create a new live stream -->
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Create Live Stream:</h4>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="title">Live Stream Title:</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Live Stream</button>
                        </form>
                    </div>

                    <!-- Live Streams List -->
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Live Streams List:</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM live_streams WHERE is_active = 1");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $cnt; ?></th>
                                        <td><?php echo $row['title']; ?></td>
                                        <td><?php echo $row['description']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-info" onclick="openCameraModal(<?php echo $row['id']; ?>)">Preview</button>
                                            <a href="edit-live-stream.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                            <a href="delete-live-stream.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this live stream?')">Delete</a>
                                        </td>
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

    <!-- Modal for Camera Preview -->
    <div id="cameraModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Live Stream Preview</h4>
                </div>
                <div class="modal-body">
                    <video id="cameraPreview" width="100%" height="400px" autoplay></video>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="endStream()">End Stream</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="stopCamera()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cameraStream;
        const streamId = <?php echo json_encode($openCamera ? $row['id'] : null); ?>;

        function openCameraModal(id) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    cameraStream = stream;
                    const video = document.getElementById('cameraPreview');
                    video.srcObject = stream;
                    $('#cameraModal').modal('show');
                })
                .catch(function(error) {
                    console.error("Error accessing camera: ", error);
                    alert("Camera access is not available.");
                });
        }

        function stopCamera() {
            if (cameraStream) {
                let tracks = cameraStream.getTracks();
                tracks.forEach(track => track.stop());
                cameraStream = null;
            }
        }

        function endStream() {
            if (confirm('Are you sure you want to end this live stream?')) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "end-stream.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText);
                        $('#cameraModal').modal('hide');
                        location.reload();
                    }
                };
                xhr.send("id=" + streamId);
            }
        }

        <?php if ($openCamera) { ?>
            $(document).ready(function() {
                openCameraModal(<?php echo $streamId; ?>);
            });
        <?php } ?>
    </script>
</body>
</html>
<?php } ?>
