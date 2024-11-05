<?php  
// logout.php  

// Start the session  
session_start();  

// Remove all session variables  
$_SESSION = [];  

// Destroy the session  
session_destroy();  

// Redirect to the login page  
header("Location: ../user/index.php");  
exit; // Ensure no further code is executed  
?>