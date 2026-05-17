<?php
session_start();
include "db.php";

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);

        $_SESSION['id'] = $row['id'];
        $_SESSION['user'] = $row['name'];
        $_SESSION['role'] = $row['role'];

        if($row['role'] == "admin"){
            header("Location: admin.php");
            exit();
        } elseif($row['role'] == "host"){
            header("Location: host.php");
            exit();
        } elseif($row['role'] == "guest"){
            header("Location: guest.php");
            exit();
        }
    } else {
        $error = "⚠️ Wrong email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | Airbnb System</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            width: 350px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 30px;
            color: #333;
        }

        input[type=email], input[type=password] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            transition: 0.3s;
        }

        input[type=email]:focus, input[type=password]:focus {
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102,126,234,0.5);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            border: none;
            background: #667eea;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        .login-btn:hover {
            background: #764ba2;
        }

        .error-msg {
            background: #ffe0e0;
            color: #ff0000;
            padding: 10px;
            margin: 15px 0;
            border-radius: 8px;
            font-weight: bold;
        }

        a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login to Your Account</h2>

    <?php if($error != ''): ?>
        <div class="error-msg"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit" class="login-btn">Login</button>
    </form>

    <p style="margin-top:15px;">Don't have an account? <a href="register.php">Register</a></p>
</div>

</body>
</html>