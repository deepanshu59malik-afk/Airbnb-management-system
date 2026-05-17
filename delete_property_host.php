<?php

include "db.php";
$id=$_GET['id'];

mysqli_query(
$conn,
"DELETE FROM properties
WHERE id='$id'"
);

header("Location: host.php");

?>