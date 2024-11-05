<?php
include '../includes/dbconnection.php';

// Initialize the query to fetch trending videos
$query = "SELECT v.video_id, v.title, v.description, v.views, v.created_at, v.url, c.category_name 
          FROM videos v 
          JOIN categories c ON v.category_id = c.category_id 
          ORDER BY v.views DESC LIMIT 10"; // Adjust as needed for trending logic

$result = mysqli_query($con, $query);

$videos = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Append video data to the array
    $videos[] = [
        'video_id' => $row['video_id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'views' => $row['views'],
        'created_at' => $row['created_at'],
        'url' => $row['url'],
        'category_name' => $row['category_name'],
    ];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($videos);
?>
