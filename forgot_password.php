<?php include("config/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<link rel="stylesheet" href="style.css">
</head>

<body class="auth-body">

<div class="auth-container">
    <div class="auth-card">

        <h2>Reset Password</h2>
        <p class="subtitle">Enter your email to reset password</p>

        <form method="POST">

            <input type="email" name="email" placeholder="Enter your email" required>

            <button name="reset">Send Reset Link</button>

        </form>

        <?php
        if(isset($_POST['reset'])){
            $email = $_POST['email'];

            $check = $conn->query("SELECT * FROM users WHERE email='$email'");

            if($check->num_rows > 0){
                echo "<p style='color:lightgreen;'>Reset link sent (demo)</p>";
            } else {
                echo "<p style='color:red;'>Email not found</p>";
            }
        }
        ?>

        <p class="register-text">
            <a href="login.php">Back to Login</a>
        </p>

    </div>
</div>

</body>
</html>