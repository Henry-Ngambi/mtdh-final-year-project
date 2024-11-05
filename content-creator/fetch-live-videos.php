<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['user_id']) == 0) {
    exit;
}

$query = "SELECT * FROM videos WHERE is_live = 1";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    $cnt = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<th scope="row">' . $cnt . '</th>';
        echo '<td>' . htmlspecialchars($row['title']) . '</td>';
        echo '<td>' . htmlspecialchars($row['description']) . '</td>';
        echo '<td><a href="' . htmlspecialchars($row['url']) . '" target="_blank">Watch Live</a></td>';
        echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
        echo '<td><a href="edit-live-stream.php?id=' . htmlspecialchars($row['video_id']) . '">Edit</a> | <a href="delete-live-stream.php?id=' . htmlspecialchars($row['video_id']) . '" onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
        echo '</tr>';
        $cnt++;
    }
} else {
    echo '<tr><td colspan="6">No live videos available.</td></tr>';
}
?>
