<?php  
session_start();  
$title = "Trending Dances - Malawi Traditional Dances Hub";  
include 'header.php';  
include 'sidebar.php';  
include '../includes/dbconnection.php';  
?>  

<div class="container">  
    <!-- Region Selection Tabs -->
    <div class="tab-container">
        <button class="tab-button active" data-region="">All</button>
        <button class="tab-button" data-region="Northern">Northern Malawi</button>
        <button class="tab-button" data-region="Central">Central Malawi</button>
        <button class="tab-button" data-region="Southern">Southern Malawi</button>
    </div>

    <div class="banner container">  
        <h1>All Trending Traditional Dances</h1>  
    </div>  

    <div class="list-container"></div> <!-- Container for dynamic video list -->

    <?php  
    // Display login messages or welcome messages...
    // (Include your previous login message code here)
    ?>

    <!-- Logout link with confirmation -->  
    <?php if (isset($_SESSION['user_id'])): ?>  
        <a href="#" onclick="confirmLogout()">Logout</a>  
    <?php endif; ?>  
</div>  

<script>  
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-button');
    const listContainer = document.querySelector('.list-container');

    // Load videos for the default active tab
    fetchVideos('');

    // Add event listeners to each tab button
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const region = button.getAttribute('data-region');
            fetchVideos(region);
            setActiveTab(button);
            updateTitle(region); // Update title based on selected region
        });
    });

    function fetchVideos(region) {
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
                            <p>${toTitleCase(timeElapsedString(video.created_at))}</p> 
                        </div>
                    </div>
                </div>
            `;
            listContainer.insertAdjacentHTML('beforeend', videoHtml);
        });
    }

    function setActiveTab(activeButton) {
        tabButtons.forEach(button => {
            button.classList.remove('active');
        });
        activeButton.classList.add('active');
    }

    function updateTitle(region) {
        const titleElement = document.querySelector('.banner h1');
        titleElement.textContent = region === "" ? "All Trending Traditional Dances" : `${region} Malawi Traditional Dances`;
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