<?php
$currentPage = basename($_SERVER['PHP_SELF']); // Get the current page name
?>

<link rel="stylesheet" href="style.css">

<aside class="sidebar">
    <div class="shortcut-links">
        <a href="./index.php" class="<?php echo ($currentPage == 'index.php' || $currentPage == 'user') ? 'active' : ''; ?>">
            <img src="../public/images/home.png"><p>Home</p>
        </a>
        <a href="trending.php" class="<?php echo ($currentPage == 'trending.php') ? 'active' : ''; ?>">
            <img src="../public/images/trending.png" class="icon"><p>Trending</p>
        </a>
        <a href="subscriptions.php" class="<?php echo ($currentPage == 'subscriptions.php') ? 'active' : ''; ?>">
            <img src="../public/images/subscription.png"><p>Subscriptions</p>
        </a>
        <a href="events.php" class="<?php echo ($currentPage == 'events.php') ? 'active' : ''; ?>">
            <img src="../public/images/event.png"><p>Events</p>
        </a>
        <a href="watch_live.php" class="<?php echo ($currentPage == 'watch_live.php') ? 'active' : ''; ?>">
            <img src="../public/images/live.png"><p>Watch Live</p>
        </a>
        <a href="explore.php" class="<?php echo ($currentPage == 'explore.php') ? 'active' : ''; ?>">
            <img src="../public/images/explore.png"><p>Explore</p>
        </a>
    </div>
    
    <hr>

    <div class="subscribed-list">
        <div class="categories">
            <p>Categories</p>
        </div>
        <a href="dances.php?region=northern" class="<?php echo (isset($_GET['region']) && $_GET['region'] == 'northern') ? 'active' : ''; ?>">
            <div class="icon">NM</div>
            <p>Northern Malawi</p>
        </a>
        <a href="dances.php?region=central" class="<?php echo (isset($_GET['region']) && $_GET['region'] == 'central') ? 'active' : ''; ?>">
            <div class="icon">CM</div>
            <p>Central Malawi</p>
        </a>
        <a href="dances.php?region=southern" class="<?php echo (isset($_GET['region']) && $_GET['region'] == 'southern') ? 'active' : ''; ?>">
            <div class="icon">SM</div>
            <p>Southern Malawi</p>
        </a>
    </div>

</aside>

