<?php

session_start();

include "db.php";

$title=$_POST['title'];
$location=$_POST['location'];
$price=$_POST['price'];
$guests=$_POST['guests'];

$host=$_SESSION['id'];

mysqli_query(
$conn,
"INSERT INTO properties
(title,location,price,guests,host_id,status)
VALUES
('$title','$location','$price','$guests','$host','pending')"
);

header("Location: host.php");

?>