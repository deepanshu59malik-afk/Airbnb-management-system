<?php

include "db.php";

$name=$_POST['name'];

$email=$_POST['email'];

$msg=$_POST['message'];

mysqli_query(
$conn,
"INSERT INTO messages
(name,email,message)
VALUES
('$name','$email','$msg')"
);

header("Location: index.html");

?>