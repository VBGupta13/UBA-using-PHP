<?php
session_start();
include("config.php");

// Check if verification code is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['digit1'], $_POST['digit2'], $_POST['digit3'], $_POST['digit4'], $_POST['digit5'], $_POST['digit6'])) {
        $verification_code = $_POST['digit1'] . $_POST['digit2'] . $_POST['digit3'] . $_POST['digit4'] . $_POST['digit5'] . $_POST['digit6'];

        // Check if verification code matches the one sent
        if ($verification_code == $_SESSION['verification_code']) {
            // Verification successful, redirect to success page
            header("Location: success.html");
            exit;
        } else {
            // Verification failed, display error message
            $error = "Invalid verification code. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .container {
            max-width: 400px;
            transform: translate(10%,70%);
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            text-align: center;
        }
        .otp-input {
            width: 35px;
            height: 35px;
            margin: 0 5px;
            font-size: 20px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .otp-input:focus {
            outline: none;
            border-color: #4caf50;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
        }
        .images {
            display: flex;
            justify-content: space-between;
        }
        .images img {
            height: 380px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verify It's You</h2>
        <?php if(isset($error)) echo "<center><p class='error'>$error</p></center>"; ?>
        <form method="post" action="">
            <div>
                <input type="text" class="otp-input" name="digit1" maxlength="1" required>
                <input type="text" class="otp-input" name="digit2" maxlength="1" required>
                <input type="text" class="otp-input" name="digit3" maxlength="1" required>
                <input type="text" class="otp-input" name="digit4" maxlength="1" required>
                <input type="text" class="otp-input" name="digit5" maxlength="1" required>
                <input type="text" class="otp-input" name="digit6" maxlength="1" required>
            </div>
            
            <br>
            <input type="submit" value="Submit">
        </form>
    </div>
    <div class="images">
       <img src="mail.jpg" width="600" height="400" style="margin-left: 20px;"> 
    <img src="verify.jpg" width="400" height="380" style="margin-right: 40px;">
    </div>
    
</body>
</html>