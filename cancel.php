<?php

include "db.php";

$id=$_GET['id'];

mysqli_query(
$conn,
"DELETE FROM bookings
WHERE id='$id'"
);

header("Location: guest.php");

?>