<?php

include "db.php";

$id=$_GET['id'];

mysqli_query(
$conn,
"UPDATE properties
SET status='rejected'
WHERE id='$id'"
);

header("Location: admin.php");

?>