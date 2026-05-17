<?php

session_start();

include "db.php";

$user=$_SESSION['id'];

$property=$_POST['property'];

$review=$_POST['review'];

$rating=$_POST['rating'];

mysqli_query(
$conn,
"INSERT INTO reviews
(user_id,property_id,review,rating)
VALUES
('$user','$property','$review','$rating')"
);

header("Location: guest.php");

?>