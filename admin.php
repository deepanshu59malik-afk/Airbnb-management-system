<?php

session_start();

if(!isset($_SESSION['user']))
{
header("Location: login.html");
exit();
}

$conn=mysqli_connect("localhost","root","","airbnb");

$users=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users"));
$properties=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM properties"));
$bookings=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM bookings"));

?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>


<div class="dashboard">


<!-- SIDEBAR -->

<div class="sidebar">

<h2>StayFinder</h2>

<a href="#"><i class="fa fa-chart-line"></i> Dashboard</a>

<a href="#users"><i class="fa fa-users"></i> Users</a>

<a href="#properties"><i class="fa fa-home"></i> Properties</a>

<a href="#bookings"><i class="fa fa-book"></i> Bookings</a>

<a href="#charts"><i class="fa fa-chart-bar"></i> Analytics</a>

<a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>

</div>


<!-- MAIN -->

<div class="main">



<!-- TOPBAR -->

<div class="topbar">

Welcome <?php echo $_SESSION['user']; ?>

</div>



<!-- STATS -->

<div class="stats">

<div class="stat">
<i class="fa fa-users"></i>
<h3><?php echo $users; ?></h3>
<p>Total Users</p>
</div>

<div class="stat">
<i class="fa fa-building"></i>
<h3><?php echo $properties; ?></h3>
<p>Properties</p>
</div>

<div class="stat">
<i class="fa fa-calendar-check"></i>
<h3><?php echo $bookings; ?></h3>
<p>Bookings</p>
</div>

<div class="stat">
<i class="fa fa-indian-rupee-sign"></i>
<h3>₹<?php echo $bookings*1000; ?></h3>
<p>Revenue</p>
</div>

</div>


<!-- USERS -->

<h2 id="users">Users</h2>

<table>

<tr>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Action</th>
</tr>

<?php

$r=mysqli_query($conn,"SELECT * FROM users");

while($row=mysqli_fetch_assoc($r))
{

echo "<tr>";

echo "<td>".$row['name']."</td>";
echo "<td>".$row['email']."</td>";
echo "<td>".$row['role']."</td>";

echo "<td>
<a href='delete_user.php?id=".$row['id']."'>
Delete
</a>
</td>";

echo "</tr>";

}

?>

</table>



<!-- PROPERTIES -->

<h2 id="properties">Properties</h2>

<table>

<tr>
<th>Title</th>
<th>Location</th>
<th>Price</th>
<th>Action</th>
</tr>

<?php

$r=mysqli_query($conn,"SELECT * FROM properties");

while($row=mysqli_fetch_assoc($r))
{

echo "<tr>";

echo "<td>".$row['title']."</td>";
echo "<td>".$row['location']."</td>";
echo "<td>".$row['price']."</td>";

echo "<td>
<a href='delete_property.php?id=".$row['id']."'>
Delete
</a>
</td>";

echo "</tr>";

}

?>

</table>
<h2>Property Approval</h2>

<table>

<tr>
<th>Title</th>
<th>Location</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

$r=mysqli_query(
$conn,
"SELECT * FROM properties"
);

while($row=mysqli_fetch_assoc($r))
{

echo "<tr>";

echo "<td>".$row['title']."</td>";

echo "<td>".$row['location']."</td>";

echo "<td>".$row['status']."</td>";

echo "<td>

<a href='approve.php?id=".$row['id']."'>Approve</a>

<a href='reject.php?id=".$row['id']."'>Reject</a>

</td>";

echo "</tr>";

}

?>

</table>


<!-- BOOKINGS -->

<h2 id="bookings">Bookings</h2>

<table>

<tr>
<th>ID</th>
<th>User</th>
<th>Property</th>
</tr>

<?php

$sql="

SELECT bookings.id,
users.name,
properties.title

FROM bookings

JOIN users
ON bookings.user_id=users.id

JOIN properties
ON bookings.property_id=properties.id

";

$r=mysqli_query($conn,$sql);

while($row=mysqli_fetch_assoc($r))
{

echo "<tr>";

echo "<td>".$row['id']."</td>";
echo "<td>".$row['name']."</td>";
echo "<td>".$row['title']."</td>";

echo "</tr>";

}

?>

</table>



<!-- CHART -->

<h2 id="charts">Analytics</h2>

<canvas id="chart"></canvas>


</div>

</div>



<script>

new Chart(
document.getElementById("chart"),
{
type: "bar",
data:
{
labels:["Users","Properties","Bookings"],
datasets:[
{
data:[
<?php echo $users ?>,
<?php echo $properties ?>,
<?php echo $bookings ?>
]
}
]
}
}
);

</script>
<h2>Reviews</h2>

<table border="1">

<tr>

<th>User</th>
<th>Property</th>
<th>Review</th>
<th>Rating</th>

</tr>

<?php

$r = mysqli_query($conn,"SELECT * FROM reviews");

while($row=mysqli_fetch_assoc($r))
{

echo "<tr>";

echo "<td>".$row['user_id']."</td>";

echo "<td>".$row['property_id']."</td>";

echo "<td>".$row['review']."</td>";

echo "<td>".str_repeat("⭐",$row['rating'])."</td>";

echo "</tr>";

}

?>

</table>
<h2>Support Messages</h2>

<table>

<tr>
<th>Name</th>
<th>Email</th>
<th>Message</th>
</tr>

<?php

$r=mysqli_query(
$conn,
"SELECT * FROM messages"
);

while($row=mysqli_fetch_assoc($r))
{

echo "<tr>";

echo "<td>".$row['name']."</td>";

echo "<td>".$row['email']."</td>";

echo "<td>".$row['message']."</td>";

echo "</tr>";

}

?>

</table>
<h2>Service Requests</h2>

<table>

<tr>
<th>User</th>
<th>Service</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

$sql = "

SELECT services.*,
users.name

FROM services

JOIN users
ON services.user_id = users.id

";

$r = mysqli_query($conn,$sql);

while($row=mysqli_fetch_assoc($r))
{

echo "<tr>";

echo "<td>".$row['name']."</td>";

echo "<td>".$row['service_type']."</td>";

echo "<td>".$row['service_date']."</td>";

echo "<td>".$row['service_time']."</td>";

echo "<td>".$row['status']."</td>";

echo "<td>

<a href='approve_service.php?id=".$row['id']."'>Approve</a>

<a href='complete_service.php?id=".$row['id']."'>Complete</a>

</td>";

echo "</tr>";

}

?>

</table>
<h2>Payments</h2>

<table>

<tr>
<th>User</th>
<th>Property</th>
<th>Amount</th>
<th>Method</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php

$sql = "

SELECT payments.*,
users.name,
properties.title

FROM payments

JOIN users ON payments.user_id = users.id
JOIN properties ON payments.property_id = properties.id

";

$r = mysqli_query($conn,$sql);

while($row=mysqli_fetch_assoc($r))
{

echo "<tr>";

echo "<td>".$row['name']."</td>";

echo "<td>".$row['title']."</td>";

echo "<td>₹".$row['amount']."</td>";

echo "<td>".$row['payment_method']."</td>";

echo "<td>".$row['payment_status']."</td>";

echo "<td>".$row['created_at']."</td>";

echo "<td>

<a href='approve_payment.php?id=".$row['id']."'>Approve</a>

<a href='reject_payment.php?id=".$row['id']."'>Reject</a>

</td>";

echo "</tr>";

}

?>

</table>
</body>
</html>