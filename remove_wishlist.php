<?php
session_start();
include "db.php";

$uid = $_SESSION['id'];
$pid = $_GET['id'];

mysqli_query($conn,
"DELETE FROM wishlist WHERE user_id='$uid' AND property_id='$pid'"
);

header("Location: guest.php#wishlist");
?>