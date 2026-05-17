<?php

include "db.php";

$id=$_GET['id'];

$sql="
SELECT bookings.*, properties.title, properties.price
FROM bookings
JOIN properties ON bookings.property_id=properties.id
WHERE bookings.id='$id'
";

$r=mysqli_query($conn,$sql);
$row=mysqli_fetch_assoc($r);

/* calculate nights + total */
$checkin = new DateTime($row['checkin']);
$checkout = new DateTime($row['checkout']);
$nights = $checkin->diff($checkout)->days;
$total = $nights * $row['price'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Receipt</title>
<link rel="stylesheet" href="style.css">
</head>

<body class="receipt-body">

<div class="receipt-card">

<!-- HEADER -->
<div class="receipt-header">
<h2>StayFinder</h2>
<span>Booking Receipt</span>
</div>

<!-- PROPERTY -->
<div class="receipt-section">
<h3><?php echo $row['title']; ?></h3>
<p>Booking ID: #<?php echo $row['id']; ?></p>
</div>

<!-- DETAILS -->
<div class="receipt-details">

<div>
<span>Check-in</span>
<p><?php echo $row['checkin']; ?></p>
</div>

<div>
<span>Check-out</span>
<p><?php echo $row['checkout']; ?></p>
</div>

<div>
<span>Price / night</span>
<p>₹<?php echo $row['price']; ?></p>
</div>

<div>
<span>Nights</span>
<p><?php echo $nights; ?></p>
</div>

</div>

<!-- TOTAL -->
<div class="receipt-total">
<span>Total Paid</span>
<h1>₹<?php echo $total; ?></h1>
</div>

<!-- BUTTONS -->
<div class="receipt-actions">

<button onclick="window.print()" class="print-btn">
Download / Print
</button>

<a href="guest.php">
<button class="back-btn">Back</button>
</a>

</div>

</div>

</body>
</html>