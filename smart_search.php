<?php

include "db.php";

$location = $_POST['location'];
$guests = $_POST['guests'];
$price = $_POST['price'];

$query = "SELECT * FROM properties WHERE 1";

if($location!=""){
    $query .= " AND location='$location'";
}

if($guests!=""){
    $query .= " AND guests>='$guests'";
}

if($price!=""){
    $query .= " AND price<='$price'";
}

$result = mysqli_query($conn,$query);

while($row=mysqli_fetch_assoc($result)){

echo $row['title']." ";
echo $row['location']." ";
echo $row['price']."<br>";

}

?>