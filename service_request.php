<?php

session_start();

if(!isset($_SESSION['id']))
{
    header("Location: login.html");
    exit();
}

$type = $_GET['type'];

?>

<link rel="stylesheet" href="style.css">

<h2>Request Service</h2>

<form action="submit_service.php" method="post">

<input type="hidden" name="type" value="<?php echo $type; ?>">

<p>Service: <?php echo $type; ?></p>

<?php if($type == "guide") { ?>

<label>Select Date</label>
<input type="date" name="date" required>

<label>Select Time</label>
<input type="time" name="time" required>

<?php } ?>

<button type="submit">Confirm Request</button>

</form>