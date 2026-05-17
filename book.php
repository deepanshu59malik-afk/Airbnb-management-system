<?php

session_start();

include "db.php";

if(!isset($_SESSION['id']))
{
    echo "Login required";
    exit();
}

if(!isset($_POST['id']))
{
    echo "No property id";
    exit();
}

$uid = $_SESSION['id'];

$pid = $_POST['id'];

$checkin = $_POST['checkin'];

$checkout = $_POST['checkout'];

$sql = "INSERT INTO bookings
(user_id,property_id,checkin,checkout)
VALUES
('$uid','$pid','$checkin','$checkout')";

if(mysqli_query($conn,$sql))
{
    header("Location: guest.php");
}
else
{
    echo "Booking error";
}

?>