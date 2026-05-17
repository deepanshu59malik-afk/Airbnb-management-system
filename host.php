<?php
session_start();
include "db.php";

// Only hosts allowed
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'host'){
    header("Location: login.html");
    exit();
}

$host_id = $_SESSION['id'];

// --- Dashboard Summary ---
$total_properties = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM properties WHERE host_id='$host_id'"));

$total_bookings_query = mysqli_query($conn, "
    SELECT b.* 
    FROM bookings b
    JOIN properties p ON b.property_id = p.id
    WHERE p.host_id='$host_id'
");
$total_bookings = mysqli_num_rows($total_bookings_query);

$pending_requests = mysqli_num_rows(mysqli_query($conn, "
    SELECT b.* 
    FROM bookings b
    JOIN properties p ON b.property_id = p.id
    WHERE p.host_id='$host_id' AND b.booking_status='pending'
"));

$total_earnings_row = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(p.price) as earnings
    FROM bookings b
    JOIN properties p ON b.property_id = p.id
    WHERE p.host_id='$host_id' AND b.booking_status='confirmed'
"));
$total_earnings = $total_earnings_row['earnings'] ?? 0;

// Messages
$success_msg = $_SESSION['success'] ?? '';
$error_msg = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Host</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial; background:#f7f7f7; margin:0; padding:0; }
        .navbar { background: linear-gradient(90deg,#ff5a5f,#ff785f); color:#fff; padding:15px 20px; display:flex; justify-content: space-between; align-items: center; }
        .navbar a { color:#fff; text-decoration:none; margin-left:15px; font-weight:bold; }
        .navbar a:hover { text-decoration:underline; }
        h2 { color:#333; margin-left:20px; }

        /* Dashboard Cards */
        .cards { display:flex; justify-content: space-around; flex-wrap: wrap; margin:20px; }
        .card { background:#fff; flex:1 1 200px; margin:10px; padding:20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); text-align:center; transition:0.3s; }
        .card:hover { box-shadow:0 8px 20px rgba(0,0,0,0.2); }
        .card h3 { margin-bottom:10px; color:#333; }
        .card p { font-size:24px; font-weight:bold; color:#ff5a5f; margin:0; }

        /* Add Property Button */
        .add-btn { display:inline-block; margin:20px; padding:10px 20px; background:#ff5a5f; color:#fff; text-decoration:none; border-radius:8px; font-weight:bold; transition:0.3s; }
        .add-btn:hover { background:#ff785f; }

        /* Properties Grid */
        .container { display:flex; flex-wrap:wrap; margin:20px; }
        .property-card { background:#fff; margin:10px; padding:15px; border-radius:12px; width:250px; box-shadow:0 5px 15px rgba(0,0,0,0.1); transition:0.3s; }
        .property-card:hover { box-shadow:0 8px 20px rgba(0,0,0,0.2); }
        .property-card img { width:100%; height:150px; object-fit:cover; border-radius:8px; }
        .property-card h3 { margin:10px 0 5px 0; }
        .property-card p { margin:5px 0; font-size:14px; }
        .property-card a { display:inline-block; margin-top:10px; color:#fff; background:#ff5a5f; padding:5px 10px; border-radius:5px; text-decoration:none; font-weight:bold; transition:0.3s; }
        .property-card a:hover { background:#ff785f; }
        .status { font-weight:bold; padding:3px 8px; border-radius:5px; font-size:13px; color:#fff; }
        .status.approved { background:#28a745; }
        .status.pending { background:#ffc107; color:#333; }

        /* Messages */
        .message { padding:10px; border-radius:8px; margin:15px 20px; font-weight:bold; }
        .success { background:#d4edda; color:#155724; }
        .error { background:#f8d7da; color:#721c24; }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Host</h2>
    <div>
        Welcome <?= $_SESSION['user'] ?>
        <a href="logout.php">Logout</a>
    </div>
</div>

<?php if($success_msg): ?><div class="message success"><?= $success_msg ?></div><?php endif; ?>
<?php if($error_msg): ?><div class="message error"><?= $error_msg ?></div><?php endif; ?>

<div class="cards">
    <div class="card"><h3>Total Properties</h3><p><?= $total_properties ?></p></div>
    <div class="card"><h3>Total Bookings</h3><p><?= $total_bookings ?></p></div>
    <div class="card"><h3>Pending Requests</h3><p><?= $pending_requests ?></p></div>
    <div class="card"><h3>Total Earnings</h3><p>₹<?= number_format($total_earnings,2) ?></p></div>
</div>

<a class="add-btn" href="add_property.html">+ Add New Property</a>

<h2>My Properties</h2>
<div class="container">
<?php
$result=mysqli_query($conn,"SELECT * FROM properties WHERE host_id='$host_id'");
while($row=mysqli_fetch_assoc($result)){

    // --- IMAGE LOGIC: uploaded image OR fallback ---
    if(!empty($row['image']) && file_exists("images/".$row['image'])){
        $image = "images/".$row['image'];
    } else {
        $location = strtolower($row['location']);
        $image = "images/default.jpg"; // default fallback
        $location_images = [
            "delhi" => "images/delhi.jpg",
            "goa" => "images/goa.webp",
            "mumbai" => "images/mumbai.jpeg",
            "manali" => "images/manali.jpg",
            "jaipur" => "images/jaipur.avif",
            "udaipur" => "images/udaipur.avif",
            "shimla" => "images/shimla.jpg",
            "gurgaon" => "images/gurgaon.avif",
            "chandigarh" => "images/chandigarh.jpg",
            "pondicherry" => "images/pondicherry.avif"
        ];
        foreach($location_images as $city => $city_image){
            if(strpos($location, $city) !== false){
                $image = $city_image;
                break;
            }
        }
    }

    // Property Card
    echo "<div class='property-card'>";
    echo "<img src='$image' alt='".$row['title']."'>";
    echo "<h3>".$row['title']."</h3>";
    echo "<p>".$row['location']."</p>";
    echo "<p>₹".$row['price']."</p>";
    echo "<p>Guests: ".$row['guests']."</p>";
    echo "<p class='status ".$row['status']."'>".$row['status']."</p>";
    echo "<a href='delete_property_host.php?id=".$row['id']."'>Delete</a>";
    echo "</div>";
}
?>
</div>

</body>
</html>