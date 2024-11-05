<?php
session_start();
include '../includes/dbconnection.php';

// Fetch upcoming events from the database
$events_query = mysqli_query($con, "SELECT * FROM events");

// Initialize an array to store events
$events = [];
if (mysqli_num_rows($events_query) > 0) {
    while ($event = mysqli_fetch_assoc($events_query)) {
        $events[] = $event;
    }
}

// Return the events as JSON
header('Content-Type: application/json');
echo json_encode($events);
?>
