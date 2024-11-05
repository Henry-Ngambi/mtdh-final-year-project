<?php
include '../includes/dbconnection.php';

// Fetch videos from the database
$query = mysqli_query($con, "SELECT * FROM videos");

$videos = [];
while ($row = mysqli_fetch_assoc($query)) {
    // Get category name
    $category_id = $row['category_id'];
    $category_query = mysqli_query($con, "SELECT category_name FROM categories WHERE category_id = '$category_id'");
    $category_row = mysqli_fetch_assoc($category_query);
    $category_name = $category_row['category_name'] ?? 'Unknown';

    // Append video data to the array
    $videos[] = [
        'video_id' => $row['video_id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'views' => $row['views'],
        'created_at' => $row['created_at'],
        'url' => $row['url'],
        'category_name' => $category_name,
    ];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($videos);
?>
