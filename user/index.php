<?php  
session_start();  
$title = "Malawi Traditional Dances Hub";  
include 'header.php';  
include 'sidebar.php';  
include '../includes/dbconnection.php';  

// Check if the user is subscribed or not
$isSubscribed = isset($_SESSION['is_subscribed']) && $_SESSION['is_subscribed'] === 'true';
?>  

<link href="bootstrap.css" rel='stylesheet' type='text/css' />

<div class="container">  
    <div class="banner container">  
        <h1>Welcome To Malawi Traditional Dances Hub</h1>  
        <p>The Home of Cultural Dances</p>  
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
        echo '<div class="success-message">Please Log In!</div>';  
    }
    ?>  

    <div class="list-container"></div> <!-- Container for dynamic video list -->

    <!-- Fetch and display About Us content -->
    <?php
    // Fetch About Us information
    $aboutQuery = "SELECT * FROM about_us LIMIT 1"; 
    $aboutResult = $con->query($aboutQuery);
    $aboutData = $aboutResult->fetch_assoc();
    ?>

    <!-- Fetch and display Contact Us content -->
    <?php
    // Fetch Contact Us information
    $contactQuery = "SELECT * FROM contact_us LIMIT 1"; 
    $contactResult = $con->query($contactQuery);
    $contactData = $contactResult->fetch_assoc();
    ?>
    
    <div class="about-contact-section">
        <div class="about-section">
            <h2 class="section-title">About Us</h2>
            <p><strong>Mission:</strong> <?php echo htmlspecialchars($aboutData['mission']); ?></p>
            <p><strong>What We Do:</strong> <?php echo htmlspecialchars($aboutData['what_we_do']); ?></p>
            <p><strong>Our Team:</strong> <?php echo htmlspecialchars($aboutData['our_team']); ?></p>
        </div>
        
        <div class="contact-section">
            <h2 class="section-title">Contact Us</h2>
            <p><i><?php echo htmlspecialchars($contactData['PageDescription']); ?></i></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($contactData['Email']); ?></p>
            <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($contactData['MobileNumber']); ?></p>
            <p><strong>Timing:</strong> <?php echo htmlspecialchars($contactData['Timing']); ?></p>
        </div>
    </div> 

</div>  

<script>  
document.addEventListener('DOMContentLoaded', function () {
    fetchVideos();

    function fetchVideos() {
        fetch('fetch_videos.php')
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
                                <video width="300" class="video-player" data-subscribed="<?php echo $isSubscribed ? 'true' : 'false'; ?>">
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
                            <p>${toTitleCase(timeElapsedString(video.created_at))}</p> 
                        </div>
                    </div>
                </div>
            `;
            listContainer.insertAdjacentHTML('beforeend', videoHtml);
        });

        // Add event listeners to video elements
        document.querySelectorAll('.video-player').forEach(video => {
            video.addEventListener('play', handleVideoPlay);
        });
    }

    function handleVideoPlay(event) {
        const video = event.target;
        const isSubscribed = video.getAttribute('data-subscribed') === 'true';

        if (!isSubscribed) {
            setTimeout(() => {
                video.pause();
                alert('Please subscribe to continue watching.');
            }, 10000); // Pause after 10 seconds
        } else {
            console.log("User is subscribed, no interruption needed.");
        }
    }

    function timeElapsedString(datetime) {
        const now = new Date();
        const ago = new Date(datetime); 
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

<?php include 'footer.php'; ?>  
