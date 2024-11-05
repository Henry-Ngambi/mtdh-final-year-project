<?php  
session_start();  
$title = "MTDH | Explore Dances";  
include 'header.php';  
include 'sidebar.php';  
include '../includes/dbconnection.php';  
?>  

<div class="container">  
    <div class="banner">  
        <h2>Explore Dances of Malawi</h2>  
        <p>Learn about the rich cultural heritage of Malawi through its traditional dances.</p>  
    </div>  

    <div class="explore-content">  
        <ul>  
            <?php  
            // Fetch dance categories based on region from the database  
            $query = mysqli_query($con, "SELECT * FROM dances ORDER BY region ASC");  
            while ($row = mysqli_fetch_assoc($query)) {  
                $dance_name = htmlspecialchars($row['dance_name']);  
                $region = htmlspecialchars($row['region']);  
                $dance_id = htmlspecialchars($row['id']);  
            ?>  
            <li><a href="dance_details.php?id=<?php echo $dance_id; ?>"><?php echo $dance_name; ?> - <?php echo $region; ?></a></li>  
            <?php } ?>  
        </ul>  
    </div>  
</div>  

<?php include 'footer.php'; ?>  

<style>
    /* Additional CSS styling */
    .banner {
        padding: 20px;
        text-align: center;
    }
    .explore-content {
        margin: 20px;
        font-size: 1.1em;
    }
    .explore-content ul {
        list-style-type: none;
        padding: 0;
    }
    .explore-content li {
        margin: 10px 0;
    }
</style>
