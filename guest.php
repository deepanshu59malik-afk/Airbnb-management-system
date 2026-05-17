<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.html");
    exit();
}

include "db.php";

$uid = $_SESSION['id'];

/* stats */
$bookings = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM bookings WHERE user_id='$uid'"
));

$props = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM properties WHERE status='approved'"
));

/* fetch booked dates for all properties */
$bookedDates = [];
$propResult = mysqli_query($conn, "SELECT * FROM bookings WHERE booking_status='pending'");
while($b = mysqli_fetch_assoc($propResult)){
    $pid = $b['property_id'];
    $start = strtotime($b['checkin']);
    $end = strtotime($b['checkout']);

    if(!isset($bookedDates[$pid])) $bookedDates[$pid] = [];

    for($d = $start; $d < $end; $d += 86400){
        $bookedDates[$pid][] = date("Y-m-d", $d);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Guest Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">

<!-- SIDEBAR -->
<div class="sidebar">
<h2>StayFinder</h2>
<a href="#">Dashboard</a>
<a href="#properties">🏡 Explore</a>
<a href="#bookings">📅 Bookings</a>
<a href="#wishlist">❤️ Wishlist</a>
<a href="#profile">👤 Profile</a>
<a href="logout.php">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<div class="topbar">
Welcome <?php echo $_SESSION['user']; ?> 👋
</div>

<!-- STATS -->
<div class="stats">
<div class="stat">My Bookings <h3><?php echo $bookings; ?></h3></div>
<div class="stat">Available <h3><?php echo $props; ?></h3></div>
</div>

<!-- SEARCH -->
<h2 class="section-title" id="properties">
<span>🏡</span> Explore Properties
</h2>
<div class="section-divider"></div>

<form method="get" class="search-box-new">
<input name="search" placeholder="Search by location...">
<button>Search</button>
</form>

<!-- PROPERTIES -->
<div class="property-grid">

<?php
$where = "status='approved'";
if(isset($_GET['search']) && $_GET['search']!=""){
    $s = $_GET['search'];
    $where .= " AND location LIKE '%$s%'";
}

$r = mysqli_query($conn,"SELECT * FROM properties WHERE $where");

while($row = mysqli_fetch_assoc($r)){
?>

<div class="property-card">

<div class="image-box">

<?php
$location = strtolower($row['location']);
$image = "images/default.jpg";

if(strpos($location, "delhi") !== false) $image = "images/delhi.jpg";
elseif(strpos($location, "goa") !== false) $image = "images/goa.webp";
elseif(strpos($location, "mumbai") !== false) $image = "images/mumbai.jpeg";
elseif(strpos($location, "manali") !== false) $image = "images/manali.jpg";
elseif(strpos($location, "jaipur") !== false) $image = "images/jaipur.avif";
elseif(strpos($location, "udaipur") !== false) $image = "images/udaipur.avif";
elseif(strpos($location, "shimla") !== false) $image = "images/shimla.jpg";
elseif(strpos($location, "gurgaon") !== false) $image = "images/gurgaon.avif";
elseif(strpos($location, "chandigarh") !== false) $image = "images/chandigarh.jpg";
?>

<img src="<?php echo $image; ?>">
<span class="price">₹<?php echo $row['price']; ?>/night</span>

</div>

<div class="property-info">

<h3><?php echo $row['title']; ?></h3>
<p class="location">📍 <?php echo $row['location']; ?></p>

<form action="payment.php" method="post" class="book-form">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
<input type="hidden" name="price" value="<?php echo $row['price']; ?>">
<input type="hidden" name="total_price" class="total-price-input" value="0">

<div class="date-row">

<div class="date-field">
<label>Check-in</label>
<input type="date" name="checkin" class="checkin" required>
</div>

<div class="date-field">
<label>Check-out</label>
<input type="date" name="checkout" class="checkout" required>
</div>

</div>

<div class="price-box">
<div class="price-top">
<span class="night-count">0 nights</span>
<span>₹<?php echo $row['price']; ?>/night</span>
</div>
<div class="price-total">
₹<span class="price-value">0</span>
</div>
</div>

<button type="submit" class="book-btn">Book Now</button>

</form>

<a href="wishlist.php?id=<?php echo $row['id']; ?>">
<button class="wish-btn">❤ Wishlist</button>
</a>

</div>
</div>

<script>
// Booked dates for this property
let bookedDates<?php echo $row['id']; ?> = <?php echo json_encode($bookedDates[$row['id']] ?? []); ?>;
</script>

<?php } ?>

</div>

<!-- BOOKINGS -->
<h2 class="section-title" id="bookings">
<span>📅</span> My Bookings
</h2>

<div class="section-divider"></div>

<?php
$sql = "
SELECT bookings.*, properties.title, properties.location, properties.price
FROM bookings
JOIN properties ON bookings.property_id = properties.id
WHERE bookings.user_id='$uid'
";

$r = mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($r)){
?>

<div class="booking-card">

<div class="booking-img">

<?php
$location = strtolower($row['location']);
$image = "images/default.jpg";

if(strpos($location, "delhi") !== false) $image = "images/delhi.jpg";
elseif(strpos($location, "goa") !== false) $image = "images/goa.webp";
elseif(strpos($location, "mumbai") !== false) $image = "images/mumbai.jpeg";
elseif(strpos($location, "manali") !== false) $image = "images/manali.jpg";
elseif(strpos($location, "jaipur") !== false) $image = "images/jaipur.avif";
elseif(strpos($location, "udaipur") !== false) $image = "images/udaipur.avif";
elseif(strpos($location, "shimla") !== false) $image = "images/shimla.jpg";
elseif(strpos($location, "gurgaon") !== false) $image = "images/gurgaon.avif";
elseif(strpos($location, "chandigarh") !== false) $image = "images/chandigarh.jpg";
?>

<img src="<?php echo $image; ?>">

<span class="status <?php echo $row['booking_status']; ?>">
<?php echo ucfirst($row['booking_status']); ?>
</span>

</div>

<div class="booking-info">

<h3><?php echo $row['title']; ?></h3>
<p class="location">📍 <?php echo $row['location']; ?></p>

<div class="booking-meta">
<span>₹<?php echo $row['price']; ?>/night</span>
<span><?php echo $row['checkin']; ?> → <?php echo $row['checkout']; ?></span>
</div>

<div class="booking-actions">
<a href="receipt.php?id=<?php echo $row['id']; ?>"><button class="receipt-btn">Receipt</button></a>
<a href="cancel.php?id=<?php echo $row['id']; ?>"><button class="cancel-btn">Cancel</button></a>
</div>

<form action="add_review.php" method="post" class="review-box">
<input type="hidden" name="property" value="<?php echo $row['property_id']; ?>">
<input name="review" placeholder="Write your experience...">
<select name="rating">
<option value="5">⭐⭐⭐⭐⭐</option>
<option value="4">⭐⭐⭐⭐</option>
<option value="3">⭐⭐⭐</option>
<option value="2">⭐⭐</option>
<option value="1">⭐</option>
</select>
<button class="review-btn">Submit</button>
</form>

</div>
</div>

<?php } ?>

<!-- WISHLIST -->
<h2 class="section-title" id="wishlist">
<span>❤️</span> My Wishlist
</h2>

<div class="section-divider"></div>

<div class="property-grid">

<?php
$sql = "
SELECT properties.*
FROM wishlist
JOIN properties ON wishlist.property_id = properties.id
WHERE wishlist.user_id = '$uid'
";

$r = mysqli_query($conn,$sql);

if(mysqli_num_rows($r) == 0){
    echo "<p>No wishlist items yet 😔</p>";
}

while($row = mysqli_fetch_assoc($r)){
?>

<div class="property-card">

<div class="image-box">

<?php
$location = strtolower($row['location']);
$image = "images/default.jpg";

if(strpos($location, "delhi") !== false) $image = "images/delhi.jpg";
elseif(strpos($location, "goa") !== false) $image = "images/goa.webp";
elseif(strpos($location, "mumbai") !== false) $image = "images/mumbai.jpeg";
elseif(strpos($location, "manali") !== false) $image = "images/manali.jpg";
elseif(strpos($location, "jaipur") !== false) $image = "images/jaipur.avif";
elseif(strpos($location, "udaipur") !== false) $image = "images/udaipur.avif";
elseif(strpos($location, "shimla") !== false) $image = "images/shimla.jpg";
elseif(strpos($location, "gurgaon") !== false) $image = "images/gurgaon.avif";
elseif(strpos($location, "chandigarh") !== false) $image = "images/chandigarh.jpg";
?>

<img src="<?php echo $image; ?>">
<span class="price">₹<?php echo $row['price']; ?>/night</span>

</div>

<div class="property-info">

<h3><?php echo $row['title']; ?></h3>
<p>📍 <?php echo $row['location']; ?></p>

<div class="wishlist-btns">

<form action="payment.php" method="post">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
<input type="hidden" name="price" value="<?php echo $row['price']; ?>">
<button class="book-btn">Book Now</button>
</form>

<a href="remove_wishlist.php?id=<?php echo $row['id']; ?>">
<button class="remove-btn">Remove</button>
</a>

</div>

</div>

</div>

<?php } ?>

</div>

<!-- PROFILE -->
<h2 class="section-title" id="profile">
<span>👤</span> Profile
</h2>

<div class="section-divider"></div>

<div class="profile-card">

<div class="profile-header">
<div class="avatar">
<?php echo strtoupper(substr($_SESSION['user'],0,1)); ?>
</div>
<h3><?php echo $_SESSION['user']; ?></h3>
<p class="role"><?php echo $_SESSION['role']; ?></p>
</div>

<form action="update_profile.php" method="post" class="profile-form">

<div class="input-group">
<input type="email" name="email" placeholder="Enter new email" required>
</div>

<div class="input-group">
<input type="password" name="password" placeholder="Enter new password">
</div>

<button type="submit" class="update-btn">Update Profile</button>
</form>

</div>
</div>
</div>

<!-- JS -->
<script>
const today = new Date().toISOString().split("T")[0];

document.querySelectorAll(".checkin").forEach(input => {
    input.min = today;
    input.addEventListener("change", function(){
        let checkout = this.closest(".date-row").querySelector(".checkout");
        checkout.min = this.value;
    });
});

document.querySelectorAll(".book-form").forEach(form => {
    let checkin = form.querySelector(".checkin");
    let checkout = form.querySelector(".checkout");
    let price = parseInt(form.querySelector("input[name='price']").value);

    let output = form.querySelector(".price-value");
    let nightsText = form.querySelector(".night-count");
    let totalInput = form.querySelector(".total-price-input");

    function calculate(){
        if(checkin.value && checkout.value){
            let d1 = new Date(checkin.value);
            let d2 = new Date(checkout.value);
            let nights = (d2 - d1) / (1000 * 60 * 60 * 24);
            if(nights > 0){
                let total = nights * price;
                output.innerText = total;
                nightsText.innerText = nights + " nights";
                totalInput.value = total;
            }
        }
    }

    checkin.addEventListener("change", calculate);
    checkout.addEventListener("change", calculate);
});

/* Prevent double booking */
document.querySelectorAll(".property-card").forEach(card => {
    const pid = card.querySelector("input[name='id']").value;
    const checkinInput = card.querySelector(".checkin");
    const checkoutInput = card.querySelector(".checkout");

    const booked = window['bookedDates' + pid] || [];

    function disableDates(input){
        input.addEventListener('change', function(){
            if(booked.includes(this.value)){
                alert("This date is already booked! Please choose another date.");
                this.value = '';
                checkoutInput.value = '';
            }
        });
    }

    disableDates(checkinInput);
    disableDates(checkoutInput);
});
</script>

</body>
</html>