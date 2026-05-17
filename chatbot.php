<?php

include "db.php";

if(!isset($_POST['msg']))
{
    echo "No message received";
    exit();
}

$msg = strtolower($_POST['msg']);

echo "You said: ".$msg."<br>";

// hello
if($msg == "hi" || $msg == "hello")
{
    echo "Hello user";
}

// cheap
else if(strpos($msg,"cheap") !== false)
{
    $sql = "SELECT * FROM properties ORDER BY price ASC LIMIT 1";
    $q = mysqli_query($conn,$sql);

    if(!$q)
    {
        echo "Query failed";
        exit();
    }

    $r = mysqli_fetch_assoc($q);

    if($r)
    {
        echo $r['title']." ".$r['location']." ₹".$r['price'];
    }
    else
    {
        echo "No data";
    }
}

// delhi
else if(strpos($msg,"delhi") !== false)
{
    $sql = "SELECT * FROM properties WHERE location='Delhi'";
    $q = mysqli_query($conn,$sql);

    if(!$q)
    {
        echo "Query error";
        exit();
    }

    while($r=mysqli_fetch_assoc($q))
    {
        echo $r['title']." ₹".$r['price']."<br>";
    }
}

// goa
else if(strpos($msg,"goa") !== false)
{
    $sql = "SELECT * FROM properties WHERE location='Goa'";
    $q = mysqli_query($conn,$sql);

    if(!$q)
    {
        echo "Query error";
        exit();
    }

    while($r=mysqli_fetch_assoc($q))
    {
        echo $r['title']." ₹".$r['price']."<br>";
    }
}

// luxury (assuming price > 10000 means luxury)
else if(strpos($msg,"luxury") !== false)
{
    $sql = "SELECT * FROM properties WHERE price > 5000 ORDER BY price DESC";
    $q = mysqli_query($conn,$sql);

    if(!$q)
    {
        echo "Query error";
        exit();
    }

    while($r=mysqli_fetch_assoc($q))
    {
        echo $r['title']." ".$r['location']." ₹".$r['price']."<br>";
    }
}

// booked (show all properties that are currently booked)
else if(strpos($msg,"booked") !== false)
{
    $sql = "SELECT properties.title, properties.location, properties.price
            FROM bookings 
            JOIN properties ON bookings.property_id = properties.id
            WHERE bookings.booking_status != 'cancelled'
            GROUP BY properties.id";
    $q = mysqli_query($conn,$sql);

    if(!$q)
    {
        echo "Query error";
        exit();
    }

    while($r=mysqli_fetch_assoc($q))
    {
        echo $r['title']." ".$r['location']." ₹".$r['price']."<br>";
    }
}

else
{
    echo "Command not found";
}

?>