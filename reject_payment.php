<?php

include "db.php";

$id = $_GET['id'];

/* 1. Update payment */

mysqli_query($conn,
"UPDATE payments
SET payment_status='rejected'
WHERE id='$id'"
);

/* 2. Update booking */

mysqli_query($conn,
"UPDATE bookings
SET booking_status='cancelled'
WHERE property_id = (
SELECT property_id FROM payments WHERE id='$id'
)
AND user_id = (
SELECT user_id FROM payments WHERE id='$id'
)
ORDER BY id DESC LIMIT 1
");

header("Location: admin.php");

?>