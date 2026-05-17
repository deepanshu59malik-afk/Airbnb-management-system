<?php
session_start();
include "db.php";

if(!isset($_SESSION['user']) || $_SESSION['role'] != 'host'){
    header("Location: login.html");
    exit();
}

$host_id = $_SESSION['id'];

$title = mysqli_real_escape_string($conn, $_POST['title']);
$location = mysqli_real_escape_string($conn, $_POST['location']);
$price = mysqli_real_escape_string($conn, $_POST['price']);
$guests = mysqli_real_escape_string($conn, $_POST['guests']);

// Handle image upload if provided
$image_name = '';
if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
    $image_tmp = $_FILES['image']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $image_name = uniqid('prop_').".".$ext;
    move_uploaded_file($image_tmp, "images/".$image_name);
}

// Insert into properties
$sql = "INSERT INTO properties (title, location, price, guests, host_id, status, image)
        VALUES ('$title','$location','$price','$guests','$host_id','pending','$image_name')";

if(mysqli_query($conn, $sql)){
    $_SESSION['success'] = "Property added successfully! Awaiting approval.";
}else{
    $_SESSION['error'] = "Error: ".mysqli_error($conn);
}

// Redirect back to host dashboard
header("Location: host.php");
exit();
?>