<?php

include "db.php";

$id = $_GET['id'];

mysqli_query(
$conn,
"UPDATE services
SET status='approved'
WHERE id='$id'"
);

header("Location: admin.php");

?>