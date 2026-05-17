<?php
session_start();
include "db.php";

if(!isset($_SESSION['user'])){
    header("Location: login.html");
    exit();
}

$uid = $_SESSION['id'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Get email from form
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';

    if(!empty($email)){
        // Update email in database
        $update_query = "UPDATE users SET email='$email' WHERE id='$uid'";
        if(mysqli_query($conn, $update_query)){
            // Store success message in session and redirect
            $_SESSION['success'] = "Email updated successfully!";
            header("Location: guest.php");
            exit();
        } else {
            $_SESSION['error'] = "Update failed: " . mysqli_error($conn);
            header("Location: guest.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Please enter a new email.";
        header("Location: guest.php");
        exit();
    }
} else {
    // If someone tries to open this page directly
    header("Location: guest.php");
    exit();
}
?>