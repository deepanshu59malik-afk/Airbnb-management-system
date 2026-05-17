<?php
session_start();
include "db.php";

$uid = $_SESSION['id'];
$pid = $_GET['id'];

/* CHECK IF ALREADY IN WISHLIST */
$check = mysqli_query($conn,
"SELECT * FROM wishlist WHERE user_id='$uid' AND property_id='$pid'"
);

$already = mysqli_num_rows($check) > 0;

if(!$already){
    mysqli_query($conn,
    "INSERT INTO wishlist (user_id,property_id)
    VALUES ('$uid','$pid')");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Wishlist</title>
<link rel="stylesheet" href="style.css">
</head>

<body class="wishlist-body">

<div class="wishlist-card">

    <div class="heart">❤</div>

    <?php if($already){ ?>
        <h2>Already in Wishlist</h2>
        <p>This property is already saved 💖</p>
    <?php } else { ?>
        <h2>Added to Wishlist</h2>
        <p>Saved for later viewing 🔥</p>
    <?php } ?>

    <div class="wishlist-actions">
        <a href="guest.php">
            <button class="back-btn">Back to Dashboard</button>
        </a>
    </div>

</div>

<!-- AUTO REDIRECT -->
<script>
setTimeout(()=>{
    window.location.href="guest.php";
},4000);
</script>

</body>
</html>