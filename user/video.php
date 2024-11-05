<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTDH | Watching Video</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bootstrap.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php
include 'header.php';
include 'sidebar.php';
include '../includes/dbconnection.php';

// Get the video ID from the URL
$video_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch video details
$video_query = mysqli_query($con, "SELECT * FROM videos WHERE video_id = '$video_id'");
$video_row = mysqli_fetch_assoc($video_query);

// Function to format time elapsed
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    
    if ($diff->days > 0) {
        return $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' ago';
    }
    if ($diff->h > 0) {
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    }
    if ($diff->i > 0) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    }
    return 'just now';
}

// Increment view count
mysqli_query($con, "UPDATE videos SET views = views + 1 WHERE video_id = '$video_id'");

// Fetch total likes and dislikes
$likes_query = mysqli_query($con, "SELECT COUNT(*) AS likes FROM video_interactions WHERE video_id = '$video_id' AND interaction_type = 'like'");
$dislikes_query = mysqli_query($con, "SELECT COUNT(*) AS dislikes FROM video_interactions WHERE video_id = '$video_id' AND interaction_type = 'dislike'");
$likes_count = mysqli_fetch_assoc($likes_query)['likes'];
$dislikes_count = mysqli_fetch_assoc($dislikes_query)['dislikes'];
?>

<div class="container play-container">
    <div class="row">
        <div class="play-video">
            <video controls autoplay>
                <source src="<?php echo htmlspecialchars($video_row['url']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            <div class="tags">
                <a href="#">#Dance</a> <a href="#">#Culture</a> <a href="#">#Malawi</a>
            </div>
            <h3><?php echo htmlspecialchars($video_row['title']); ?></h3>
            <p><h5><?php echo ucwords(strtolower($video_row['description'])); ?></h5></p>
                    
            <div class="play-video-info">
                <p><?php echo htmlspecialchars($video_row['views']); ?> views &bull; <?php echo ucwords(time_elapsed_string($video_row['created_at'])); ?></p> <!-- Format upload time -->
                <div>
                    <span id="like-count"><?php echo htmlspecialchars($likes_count); ?></span> 
                    <a href="#" id="like-video"><img src="../public/images/like.png" alt="Like"></a>
                    <span id="dislike-count"><?php echo htmlspecialchars($dislikes_count); ?></span> 
                    <a href="#" id="dislike-video"><img src="../public/images/dislike.png" alt="Dislike"></a>
                    <a href="#"><img src="../public/images/share.png" alt="Share"> Share</a>
                    <a href="#"><img src="../public/images/save.png" alt="Save"> Save</a>
                </div>
            </div>

            <div class="vid-description">
                
                <hr>

                <h4>Comments</h4>
                <div class="add-comment">
                    <?php
                    // Fetch user profile image
                    $user_profile_image = isset($_SESSION['user_profile_image']) ? $_SESSION['user_profile_image'] : '../public/images/profile.jpeg';
                    ?>
                    <img src="<?php echo htmlspecialchars($user_profile_image); ?>" class="comment-avatar" alt="Comment Avatar">
                    <input type="text" id="comment-input" placeholder="Write a Comment...">
                    <button type="button" class="btn" id="post-comment">Comment</button>
                </div>

                <!-- // Fetch and display comments--> 
                <div id="comments-section">
                    <?php
                    $comments_query = mysqli_query($con, "SELECT c.*, u.full_name, u.profile_image FROM comments c LEFT JOIN users u ON c.user_id = u.user_id WHERE c.video_id = '$video_id' ORDER BY c.comment_id DESC");
                    while ($comment = mysqli_fetch_assoc($comments_query)) {
                        // Use a fallback for the profile image in case it's null
                        $comment_user_profile_image = !empty($comment['profile_image']) ? $comment['profile_image'] : '../public/images/profile.jpeg';
                        $comment_likes_query = mysqli_query($con, "SELECT COUNT(*) AS likes FROM user_interactions WHERE comment_id = '{$comment['comment_id']}' AND interaction_type = 'like'");
                        $comment_dislikes_query = mysqli_query($con, "SELECT COUNT(*) AS dislikes FROM user_interactions WHERE comment_id = '{$comment['comment_id']}' AND interaction_type = 'dislike'");
                        $likes_count = mysqli_fetch_assoc($comment_likes_query)['likes'];
                        $dislikes_count = mysqli_fetch_assoc($comment_dislikes_query)['dislikes'];
                        
                        // Ensure $comment['full_name'] is not null
                        $full_name = !empty($comment['full_name']) ? $comment['full_name'] : 'Anonymous';
                    ?>
                        <div class="comment">
                            <div class="comment-header">
                                <img src="<?php echo htmlspecialchars($comment_user_profile_image); ?>" alt="" class="comment-avatar">
                                <strong><?php echo htmlspecialchars($full_name); ?></strong>
                                <em><?php echo date('F j, Y, g:i A', strtotime($comment['created_at'])); ?></em>
                            </div>
                            <p class="comment-text"><?php echo htmlspecialchars($comment['comment']); ?></p>
                            <div class="comment-actions">
                                <span class="like" data-id="<?php echo $comment['comment_id']; ?>">👍 <?php echo htmlspecialchars($likes_count); ?></span>
                                <span class="dislike" data-id="<?php echo $comment['comment_id']; ?>">👎 <?php echo htmlspecialchars($dislikes_count); ?></span>
                                <a href="#" class="reply">Reply</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Right Sidebar Section -->
        <div class="right-sidebar">
            <h3>More Videos</h3>
            <?php
            $related_videos_query = mysqli_query($con, "SELECT * FROM videos WHERE video_id != '$video_id' LIMIT 10");
            while ($related_video = mysqli_fetch_assoc($related_videos_query)) {
                $thumbnail = !empty($related_video['thumbnail']) ? $related_video['thumbnail'] : '../public/images/default-thumbnail.png';
            ?>
                <div class="side-video-list">
                    <a href="video.php?id=<?php echo $related_video['video_id']; ?>" class="small-thumbnail">
                        <img src="<?php echo htmlspecialchars($thumbnail); ?>" alt="Thumbnail">
                        <!-- Play Icon -->
                        <div class="play-icon">
                            <img src="../public/images/play-button1.png" alt="Play Icon"> <!-- Adjust the path to your play icon -->
                        </div>
                    </a>
                    <div class="vid-info">
                        <a href="video.php?id=<?php echo $related_video['video_id']; ?>"><h3><?php echo htmlspecialchars($related_video['title']); ?></h3></a>
                        <p><?php echo ucwords(htmlspecialchars($related_video['description'])); ?></p> <!-- Description in ucwords -->
                        <p><?php echo htmlspecialchars($related_video['views']); ?> views</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
$(document).ready(function() {
    // Handle posting a comment
    $('#post-comment').on('click', function() {
        var commentText = $('#comment-input').val();
        var videoId = <?php echo $video_id; ?>;

        if (commentText) {
            $.ajax({
                type: "POST",
                url: "post_comment.php",
                data: { comment: commentText, video_id: videoId },
                success: function(response) {
                    $('#comments-section').prepend(response);
                    $('#comment-input').val('');
                }
            });
        }
    });

    // Handle likes
    $('.like').on('click', function() {
        var commentId = $(this).data('id');
        var $likeCount = $(this);

        $.ajax({
            type: "POST",
            url: "like_comment.php",
            data: { comment_id: commentId },
            success: function(response) {
                $likeCount.text('👍 ' + response);
            }
        });
    });

    // Handle dislikes
    $('.dislike').on('click', function() {
        var commentId = $(this).data('id');
        var $dislikeCount = $(this);

        $.ajax({
            type: "POST",
            url: "dislike_comment.php",
            data: { comment_id: commentId },
            success: function(response) {
                $dislikeCount.text('👎 ' + response);
            }
        });
    });

    // Handle video likes
    $('#like-video').on('click', function(e) {
        e.preventDefault();
        var videoId = <?php echo $video_id; ?>;

        $.ajax({
            type: "POST",
            url: "like_video.php",
            data: { video_id: videoId },
            success: function(response) {
                $('#like-count').text(response);
            }
        });
    });

    // Handle video dislikes
    $('#dislike-video').on('click', function(e) {
        e.preventDefault();
        var videoId = <?php echo $video_id; ?>;

        $.ajax({
            type: "POST",
            url: "dislike_video.php",
            data: { video_id: videoId },
            success: function(response) {
                $('#dislike-count').text(response);
            }
        });
    });
});
</script>

</body>
</html>
