<?php
session_start();
include "db.php";

$uid = $_SESSION['id'];
$pid = $_GET['id'];

// Get checkin and checkout from form (you should have these in your booking form)
$checkin = $_POST['checkin'];   // e.g., 2026-04-01
$checkout = $_POST['checkout']; // e.g., 2026-04-05

// 1️⃣ Check for overlapping bookings
$sql = "SELECT * FROM bookings 
        WHERE property_id='$pid' 
        AND booking_status='pending'
        AND (
            (checkin <= '$checkout' AND checkout >= '$checkin')
        )";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    // Overlap exists
    echo "<script>alert('Sorry! This property is already booked for these dates.'); window.location='guest.php';</script>";
    exit();
}

// 2️⃣ Insert booking
$insert = "INSERT INTO bookings (user_id, property_id, checkin, checkout, booking_status)
           VALUES ('$uid', '$pid', '$checkin', '$checkout', 'pending')";

if(mysqli_query($conn, $insert)){
    header("Location: guest.php?msg=Booking successful");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>