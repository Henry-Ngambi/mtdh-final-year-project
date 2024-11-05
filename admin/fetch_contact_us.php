<?php
session_start();
include('includes/dbconnection.php');

// Fetch current Contact Us content from the database
$result = mysqli_query($con, "SELECT * FROM contact_us WHERE PageType='contactus'");
$row = mysqli_fetch_assoc($result);

if ($row) {
    // Return JSON response
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'No content found']);
}
?>
