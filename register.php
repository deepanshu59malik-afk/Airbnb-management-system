<?php

include "db.php";

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

$sql = "INSERT INTO users(name,email,password,role)
VALUES('$name','$email','$password','$role')";

mysqli_query($conn,$sql);

header("Location: login.html?success=1");
exit();

?>