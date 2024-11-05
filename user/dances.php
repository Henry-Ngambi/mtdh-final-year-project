<?php  
session_start();  
$title = "Malawi Traditional Dances Hub";  
include 'header.php';  
include 'sidebar.php';  
include '../includes/dbconnection.php';  
?>  

<div class="container">  
    <div class="banner container">  
        <?php  
        // Check if a region is set in the URL
        if (isset($_GET['region'])) {
            $region = htmlspecialchars(ucfirst($_GET['region'])); // Get the region and capitalize the first letter
            echo "<h1>Traditional Dances of $region Malawi</h1>"; // Dynamic title
        } else {
            echo "<h1>Welcome To Malawi Traditional Dances Hub</h1>";
        }
        ?>
    </div>  

    <?php  
    // Display the login success message if set  
    if (isset($_SESSION['login_success'])) {  
        echo '<div class="success-message">' . $_SESSION['login_success'] . '</div>';  
        unset($_SESSION['login_success']); // Unset to avoid repetition on refresh  
    }  

    // Display a personalized welcome message if the user is logged in  
    if (isset($_SESSION['user_id'])) {  
        if (isset($_SESSION['full_name'])) {  
            $fullName = htmlspecialchars($_SESSION['full_name']); // Sanitize output for security  
            echo '<div class="success-message">Welcome, ' . $fullName . '!</div>';  
        }  
    } else {  
        echo '<div class="success-message">Welcome to Malawi Traditional Dances Hub! Please log in.</div>';  
    }
    ?>  

    <!-- Region Selection -->
    <div class="list-container"></div> <!-- Container for dynamic video list -->
</div>  

<!-- Logout link with confirmation -->  
<?php if (isset($_SESSION['user_id'])): ?>  
    <a href="#" onclick="confirmLogout()">Logout</a>  
<?php endif; ?>  

<script>  
document.addEventListener('DOMContentLoaded', function () {
    // Get the region from the URL query parameter
    const urlParams = new URLSearchParams(window.location.search);
    const region = urlParams.get('region') || ''; // Default to empty if not set

    // Initially fetch videos based on the region
    fetchRegionalVideos(region);

    function fetchRegionalVideos(region) {
        // Create the URL with the region parameter if applicable
        const url = region ? `fetch_regional_videos.php?region=${region}` : 'fetch_regional_videos.php';
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                displayVideos(data);
            })
            .catch(error => {
                console.error('Error fetching videos:', error);
            });
    }

    function displayVideos(videos) {
        const listContainer = document.querySelector('.list-container');
        listContainer.innerHTML = ''; // Clear existing content

        videos.forEach(video => {
            const videoHtml = `
                <div class="vid-list">
                    <div class="flex-div">
                        <div class="vid-info">
                            <a href="video.php?id=${video.video_id}" class="video-overlay">
                                <video width="300" class="video-player">
                                    <source src="${video.url}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <div class="play-button-overlay">
                                    <img src="../public/images/play-button1.png" alt="Play" class="play-button-icon">
                                </div>
                            </a>
                            <div class="flex-div">
                                <a href="video.php?id=${video.video_id}"><h2>${toTitleCase(video.title)}</h2></a>
                            </div>
                            <h5>${toTitleCase(video.description)}, ${toTitleCase(video.category_name)}</h5>
                            <p>${video.views} views</p>
                            <p>${toTitleCase(timeElapsedString(video.created_at))}</p> <!-- Format upload time in title case --> 
                        </div>
                    </div>
                </div>
            `;
            listContainer.insertAdjacentHTML('beforeend', videoHtml);
        });
    }

    function timeElapsedString(datetime) {
        const now = new Date();
        const ago = new Date(datetime); // Ensure datetime is in the correct format
        const diff = now - ago;

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

        if (days > 0) return `${days} day${days > 1 ? 's' : ''} ago`;
        if (hours > 0) return `${hours} hour${hours > 1 ? 's' : ''} ago`;
        if (minutes > 0) return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
        return 'just now';
    }

    function toTitleCase(str) {
        return str.toLowerCase().split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    }

    function confirmLogout() {  
        if (confirm("Are you sure you want to logout?")) {  
            window.location.href = "../public/logout.php";  
        }  
    }
});
</script>

<style>  
    .play-button-overlay {  
        display: none;  
        position: absolute;  
        top: 50%;  
        left: 50%;  
        transform: translate(-50%, -50%);  
    }  

    .video-overlay:hover .play-button-overlay {  
        display: block;  
    }  

    .vid-info {  
        position: relative;  
    }  
</style>

<?php include 'footer.php'; ?>
