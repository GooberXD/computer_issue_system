<?php
session_start();
include('connect.php');

if(isset($_POST['login'])){
    $fullname = $_POST['fullname']; 

    $result = $conn->query("SELECT * FROM User WHERE user_FullName = '$fullname'");
    
    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['user_ID'];
        $_SESSION['user_name'] = $user['user_FullName'];
        $_SESSION['isAdmin'] = $user['isAdmin'];
        
        header("Location: dashboard.php");
    } else {
        echo "<p style='color:red;'>User not found. Please register first.</p>";
    }
}
?>

<h2>Login</h2>
<form method="POST">
    <input type="text" name="fullname" placeholder="Enter Full Name" required><br><br>
    <button type="submit" name="login">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>