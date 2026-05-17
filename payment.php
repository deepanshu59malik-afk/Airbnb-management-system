<?php

session_start();

if(!isset($_SESSION['id']))
{
    header("Location: login.html");
    exit();
}

include "db.php";

$pid = $_POST['id'];
$price = $_POST['total_price'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];

$p = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT * FROM properties WHERE id='$pid'")
);

?>

<link rel="stylesheet" href="style.css">

<div class="payment-container">

<div class="payment-card">

<h2>Complete Payment</h2>

<h3><?php echo $p['title']; ?></h3>

<p>Amount: ₹<?php echo $price; ?></p>

<img src="qr.jpeg" class="qr">

<p>Scan this QR using any UPI app</p>

<form action="confirm_payment.php" method="post">

<input type="hidden" name="pid" value="<?php echo $pid; ?>">
<input type="hidden" name="price" value="<?php echo $price; ?>">
<input type="hidden" name="checkin" value="<?php echo $checkin; ?>">
<input type="hidden" name="checkout" value="<?php echo $checkout; ?>">

<button>I Have Paid</button>

</form>

</div>

</div>