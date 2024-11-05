<!-- MTDH System/user/index.php -->  

<?php  
$title = "Homepage";  
include 'header.php';  
include 'sidebar.php';  
?>  

<div class="container">  
    <div class="banner">  
        <img src="images/banner.png" alt="Banner Image">  
    </div>  

    <div class="list-container">  
        <div class="vid-list">  
            <img src="images/thumbnail1.png" class="thumbnail">  
            <div class="flex-div">  
                <img src="images/hp.jpg" alt="User">  
                <div class="vid-info">  
                    <a href="video.php">The Best of Northern Malawi</a>  
                    <p>The pride of Malawi</p>  
                    <p>5k views</p>  
                </div>  
            </div>  
        </div>  
        <!-- Add more video items here -->  
    </div>  
</div>  

<?php include 'footer.php'; ?>