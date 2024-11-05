<?php
require_once '../includes/dbconnection.php';

if (isset($_GET['query'])) {
    $query = $con->real_escape_string($_GET['query']);
    $searchQuery = "
        SELECT v.video_id, v.title, v.description, v.url, v.views, v.created_at, c.category_name
        FROM videos AS v
        JOIN categories AS c ON v.category_id = c.category_id
        WHERE v.title LIKE '%$query%'
           OR v.description LIKE '%$query%'
           OR c.category_name LIKE '%$query%'
        LIMIT 10";
    
    $result = $con->query($searchQuery);

    if ($result && $result->num_rows > 0) {
        while ($video = $result->fetch_assoc()) {
            echo '<div class="search-result">';
            echo '<a href="video.php?id=' . $video['video_id'] . '">';
            echo '<h3>' . htmlspecialchars($video['title']) . '</h3>';
            echo '<p>' . htmlspecialchars($video['description']) . '</p>';
            echo '<p><em>Region: ' . htmlspecialchars($video['category_name']) . '</em></p>';
            echo '</a>';
            echo '</div>';
        }
    } else {
        echo '<p>No results found for "' . "<b><i>" . htmlspecialchars($query). "</i></b>" . '".</p>';
    }
}
?>
