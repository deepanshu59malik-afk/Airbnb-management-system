<?php

session_start();

include "db.php";

$uid = $_SESSION['id'];

$type = $_POST['type'];

$date = isset($_POST['date']) ? $_POST['date'] : NULL;
$time = isset($_POST['time']) ? $_POST['time'] : NULL;

mysqli_query(
$conn,
"INSERT INTO services
(user_id,service_type,service_date,service_time)
VALUES
('$uid','$type','$date','$time')"
);

header("Location: guest.php");

?>