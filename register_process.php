<?php
include 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $password)) {
    // Password does not meet the criteria, redirect back with an error parameter
    header("Location: register.html?error=weakpassword");
    die();
}

$check_query = "SELECT * FROM users WHERE  username='$username' AND  email='$email'";
$result = $conn->query($check_query);

if ($result->num_rows > 0) {
    
    header("Location: register.html?error=exists");
    die();
} else {
    
    $sql = "INSERT INTO users (username, email, password, login_attempts, ismalicious) VALUES ('$username', '$email', '$password', 0, 0)";

    if ($conn->query($sql) === TRUE) {
        header("Location: success1.html");
        die();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
