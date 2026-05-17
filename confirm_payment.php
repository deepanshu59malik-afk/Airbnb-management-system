<?php
session_start();
include "db.php";

if(!isset($_SESSION['id'])){
    header("Location: login.html");
    exit();
}

$uid = $_SESSION['id'];
$pid = $_POST['pid'];
$price = $_POST['price'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];

/* 1. Check if the property is already booked for these dates */
$sql = "SELECT * FROM bookings 
        WHERE property_id='$pid' 
        AND booking_status='pending'
        AND (
            (checkin <= '$checkout' AND checkout >= '$checkin')
        )";

$res = mysqli_query($conn, $sql);

if(mysqli_num_rows($res) > 0){
    echo "<!DOCTYPE html>
    <html>
    <head>
    <meta charset='UTF-8'>
    <title>Booking Error</title>
    <link rel='stylesheet' href='style.css'>
    </head>
    <body class='payment-success-body'>
    <div class='success-wrapper'>
        <div class='success-card'>
            <div class='success-icon'>❌</div>
            <h2>Booking Failed</h2>
            <p class='sub-text'>Sorry! This property is already booked for the selected dates.</p>
            <div class='btn-group'>
                <a href='guest.php'><button class='primary-btn'>Go Back</button></a>
            </div>
        </div>
    </div>
    </body>
    </html>";
    exit();
}

/* 2. Insert booking as pending */
mysqli_query($conn,
"INSERT INTO bookings
(user_id,property_id,checkin,checkout,booking_status)
VALUES
('$uid','$pid','$checkin','$checkout','pending')"
);

$booking_id = mysqli_insert_id($conn);

/* 3. Insert payment as pending */
mysqli_query($conn,
"INSERT INTO payments
(user_id,property_id,amount,payment_method,payment_status)
VALUES
('$uid','$pid','$price','QR','pending')"
);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payment Submitted</title>
<link rel="stylesheet" href="style.css">
</head>

<body class="payment-success-body">

<div class="success-wrapper">

    <div class="success-card">

        <div class="success-icon">✔</div>

        <h2>Payment Submitted</h2>
        <p class="sub-text">Your booking request is under review</p>

        <div class="booking-details">
            <p><b>Check-in:</b> <?php echo $checkin; ?></p>
            <p><b>Check-out:</b> <?php echo $checkout; ?></p>
            <p><b>Amount Paid:</b> ₹<?php echo $price; ?></p>
        </div>

        <div class="status-box">
            ⏳ Waiting for Admin Approval
        </div>

        <div class="btn-group">
            <a href="guest.php">
                <button class="primary-btn">Go to Dashboard</button>
            </a>
        </div>

    </div>

</div>

</body>
</html>