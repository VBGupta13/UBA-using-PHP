<?php
session_start();


include("config.php");

// Function to send email notification
function sendNotificationEmail($to, $username) {
    $subject = "Unauthorized Access Attempt";
    $message = "Hello $username,\n\nSomeone attempted to login to your account with incorrect credentials multiple times. Please review your account security.\n\nThank you.";
    $headers = "From: Sguptach1105S@gmail.com";

    mail($to, $subject, $message, $headers);
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Cast to integer to ensure it's treated as a number
$_SESSION['login_attempts'] = (int)$_SESSION['login_attempts'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
      // Calculate the time taken for login attempt
      $username = $_POST['username'];
      $password = $_POST['password'];
      $start_time = $_POST['start_time'];
      $end_time = time();
      $time_taken = $end_time-$start_time;
      
      // Update the database with the time taken
      $update_query = "UPDATE users SET duration = $time_taken WHERE username = '$username'";
      $update_result = mysqli_query($conn, $update_query);
      
  

    if (!empty($username) && !empty($password) && !is_numeric($username)) {

        $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if ($user_data['password'] === $password) {

                //correct login attempt   //reset login attempts
                $attempts = $_SESSION['login_attempts'];
                $update_query = "UPDATE users SET login_attempts = $attempts WHERE username = '$username'";
                $update_result = mysqli_query($conn, $update_query);
                $ismalicious=0;
                $update_query = "UPDATE users SET ismalicious = $ismalicious WHERE username = '$username'";
                $update_result = mysqli_query($conn, $update_query);
            
                 // Generate a random verification code
                $verification_code = mt_rand(100000, 999999);

                // Store verification code in session
                $_SESSION['verification_code'] = $verification_code;

                // Send verification email
                $to = $user_data['email'];
                $subject = "Login Verification Code";
                $message = "Your verification code is: $verification_code";
                $headers = "From: guptach1105@gmail.com";

                if (mail($to, $subject, $message, $headers)) {
                    // Email sent successfully, redirect to verification page
                    header("Location: verification.php");
                    exit;
                } else {
                    echo "Failed to send verification email!";
                }
            } else {
                $_SESSION['login_attempts']++;
                $attempts = $_SESSION['login_attempts'];

                $update_query = "UPDATE users SET login_attempts = $attempts WHERE username = '$username'";
                $update_result = mysqli_query($conn, $update_query);
                if ($attempts >= 3) {
                    // Send email notification to the original user
                    sendNotificationEmail($user_data['email'], $username);

                    session_destroy();
                    header("Location: 404page.html");
                    $ismalicious = 1;
                    $update_query = "UPDATE users SET ismalicious = $ismalicious WHERE username = '$username'";
                    $update_result = mysqli_query($conn, $update_query);
                    exit;
                } else {
                    header("Location: wrong.html");
                    exit;
                }
            }
        } else {
            echo "User not found!";
        }
    } else {
        echo "Wrong username or password!";
    }
  
    
}
?>