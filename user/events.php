<?php
session_start();
$title = "MTDH | Events";
include 'header.php';
include 'sidebar.php';
include('../includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="bootstrap.css" rel='stylesheet' type='text/css' />
</head>
<body>

<div class="container event-container">
    <h1>Upcoming Events</h1>

    <!-- Display events -->
    <div class="event-cards" id="eventCards">
        <p>Loading events...</p> <!-- Loading message -->
    </div>
</div>

<!-- Modal for booking -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Complete Your Booking</h2>
        <form method="post" action="process_booking.php">
            <input type="hidden" name="event_id" id="event_id">
            <div class="form-group">
                <label for="total_amount">Total Amount</label>
                <input type="text" id="total_amount" name="total_amount" readonly>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select name="payment_method" id="payment_method" onchange="togglePaymentDetails()" required>
                    <option value="">Select Payment Method</option>
                    <option value="1">Credit Card</option>
                    <option value="2">PayPal</option>
                    <option value="3">Airtel Money</option>
                </select>
            </div>
            
            <!-- Payment details fields -->
            <div id="payment_details" class="payment-details">
                <div id="credit_card_details" class="form-group" style="display: none;">
                    <label for="card_number">Card Number</label>
                    <input type="text" id="card_number" name="card_number">
                </div>
                <div id="paypal_details" class="form-group" style="display: none;">
                    <label for="paypal_email">PayPal Email</label>
                    <input type="email" id="paypal_email" name="paypal_email">
                </div>
                <div id="airtel_money_details" class="form-group" style="display: none;">
                    <label for="airtel_phone">Airtel Money Phone Number</label>
                    <input type="text" id="airtel_phone" name="airtel_phone" placeholder="Enter Airtel Money phone number">
                </div>
            </div>

            <button type="submit" name="submit_booking" class="btn btn-primary">Confirm</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetchEvents();

    function fetchEvents() {
        fetch('fetch_events.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                displayEvents(data);
            })
            .catch(error => {
                console.error('Error fetching events:', error);
                document.getElementById('eventCards').innerHTML = '<p>Error loading events.</p>';
            });
    }

    function displayEvents(events) {
        const cards = document.getElementById('eventCards');
        cards.innerHTML = ''; // Clear existing content

        if (events.length > 0) {
            events.forEach(event => {
                const eventHtml = `
                    <div class="event-card">
                        <img src="${event.image ? event.image : 'default-image.jpg'}" 
                             alt="${htmlspecialchars(event.title ?? 'No Title')}" 
                             class="event-image">
                        <h2>${htmlspecialchars(event.title ?? 'No Title')}</h2>
                        <p class="label">Description: <span class="details">${htmlspecialchars(event.description ?? 'No Description')}</span></p>
                        <p class="label">Date: <span class="details">${htmlspecialchars(event.event_date ?? 'TBA')}</span></p>
                        <p class="label">Location: <span class="details">${htmlspecialchars(event.location ?? 'No Location')}</span></p>
                        <p class="label">Ticket Price: <span class="details">${event.ticket_price ? number_format(event.ticket_price, 2) : 'N/A'}</span></p>

                        <!-- Booking button -->
                        <button class="btn btn-primary" onclick="handleBookNow(${event.event_id}, '${event.ticket_price}')">Book Now</button>
                    </div>
                `;
                cards.insertAdjacentHTML('beforeend', eventHtml);
            });
        } else {
            cards.innerHTML = '<p>No upcoming events at the moment.</p>';
        }
    }

    // Handle "Book Now" button click
    window.handleBookNow = function(eventId, ticketPrice) {
        // Check if the user is logged in (replace with session check as needed)
        const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        
        if (!isLoggedIn) {
            // Save booking details in session storage
            sessionStorage.setItem('bookingEventId', eventId);
            sessionStorage.setItem('bookingTicketPrice', ticketPrice);
            // Redirect to login page
            window.location.href = '../public/login.php';
        } else {
            // Open booking modal if user is logged in
            openModal(eventId, ticketPrice);
        }
    };

    // Open modal
    window.openModal = function(eventId, ticketPrice) {
        document.getElementById("event_id").value = eventId;
        document.getElementById("total_amount").value = ticketPrice;
        document.getElementById("bookingModal").style.display = "block";
    };

    // Close modal
    window.closeModal = function() {
        document.getElementById("bookingModal").style.display = "none";
    };

    // Toggle payment details based on the selected payment method
    window.togglePaymentDetails = function() {
        var paymentMethod = document.getElementById("payment_method").value;
        document.getElementById("payment_details").style.display = paymentMethod ? "block" : "none";
        document.getElementById("credit_card_details").style.display = paymentMethod === "1" ? "block" : "none";
        document.getElementById("paypal_details").style.display = paymentMethod === "2" ? "block" : "none";
        document.getElementById("airtel_money_details").style.display = paymentMethod === "3" ? "block" : "none";
    };

    // Close modal when clicking outside of the modal content
    window.onclick = function(event) {
        if (event.target == document.getElementById("bookingModal")) {
            closeModal();
        }
    };

    // Helper functions
    function htmlspecialchars(string) {
        return string
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function number_format(number, decimals = 2) {
        return Number(number).toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});
</script>

<?php include 'footer.php'; ?>

</body>
</html>
