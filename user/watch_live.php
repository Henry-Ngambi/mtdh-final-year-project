<?php
session_start();
$title = "MTDH | Watch Live";
include 'header.php';
include 'sidebar.php';
include('../includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit();
}

// Fetch live streams from the live_streams table
$live_streams = $con->query("SELECT * FROM live_streams WHERE is_active = 1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .live-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }
        .live-video-container {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            width: 100%;
            max-width: 800px;
        }
        .live-video iframe {
            width: 100%;
            height: 450px;
            border: none;
        }
        .no-live-events {
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container live-container">
    <h1>Live Events</h1>

    <?php if ($live_streams->num_rows > 0) { ?>
        <!-- Display live streams if available -->
        <?php while ($stream = $live_streams->fetch_assoc()) { ?>
            <div class="live-video-container">
                <h2><?php echo htmlspecialchars($stream['title']); ?></h2>
                <p><?php echo htmlspecialchars($stream['description']); ?></p>

                <div class="live-video">
                    <iframe src="<?php echo htmlspecialchars($stream['url']); ?>" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>

                <!-- Reaction buttons -->
                <div class="reactions">
                    <button onclick="sendReaction('like', <?php echo $stream['id']; ?>)">👍 Like</button>
                    <button onclick="sendReaction('heart', <?php echo $stream['id']; ?>)">❤️ Heart</button>
                </div>

                <!-- Comment Section -->
                <div class="comments-section">
                    <h3>Comments</h3>
                    <form id="commentForm-<?php echo $stream['id']; ?>" onsubmit="postComment(event, <?php echo $stream['id']; ?>)">
                        <textarea name="comment" placeholder="Write a comment..." required></textarea>
                        <button type="submit">Post Comment</button>
                    </form>
                    <div id="commentsList-<?php echo $stream['id']; ?>"></div>
                </div>
            </div>

            <script>
                setInterval(function() {
                    fetchComments(<?php echo $stream['id']; ?>);
                }, 5000);

                function fetchComments(streamId) {
                    fetch('fetch_comments.php?stream_id=' + streamId)
                        .then(response => response.json())
                        .then(data => {
                            let commentsList = document.getElementById('commentsList-' + streamId);
                            commentsList.innerHTML = '';
                            data.comments.forEach(comment => {
                                commentsList.innerHTML += '<div class="comment"><strong>' + comment.username + ':</strong> ' + comment.comment + '</div>';
                            });
                        })
                        .catch(error => console.error('Error fetching comments:', error));
                }
            </script>

        <?php } ?>
    <?php } else { ?>
        <div class="no-live-events">
            <p>No live events at the moment.</p>
        </div>
    <?php } ?>
</div>

<script>
    function sendReaction(reactionType, streamId) {
        fetch('post_reaction.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ stream_id: streamId, reaction_type: reactionType })
        })
        .then(response => response.json())
        .then(data => alert(data.message))
        .catch(error => console.error('Error:', error));
    }

    function postComment(event, streamId) {
        event.preventDefault();
        const form = document.getElementById('commentForm-' + streamId);
        const formData = new FormData(form);
        
        fetch('post_comment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            form.reset();
            fetchComments(streamId);
        })
        .catch(error => console.error('Error posting comment:', error));
    }
</script>

<?php include 'footer.php'; ?>

</body>
</html>
