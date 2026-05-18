<?php
session_start();
include('connect.php');

// hello system - julius
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
        echo "<script>alert('User not found. Please register first.')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIT-U CCS | Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
 
    <div class="panel-left">
        <div class="logo-ring">
            <div class="logo-inner">
                <img src="images/Cebu_Institute_of_Technology_University_Logo.png" alt="CIT-U Logo">
            </div>
        </div>
        <p class="panel-tagline">CIT University</p>
        <h1 class="panel-title">College of Computer Studies</h1>
        <div class="divider"></div>
        <p class="panel-subtitle">Issue Reporting System</p>
        <p class="panel-desc">Track and manage computer lab issues across all rooms and departments.</p>
    </div>
 
    <div class="panel-right">
        <div class="card">
            <div class="gold-strip"></div>
            <div class="card-body">
 
                <div class="card-header">
                    <h2>Welcome back</h2>
                    <p>Sign in to your account to continue</p>
                </div>
 
                <?php if(!empty($error)): ?>
                <div class="alert alert-error">
                    ⚠️ <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
 
                <form method="POST">
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input
                            type="text"
                            id="fullname"
                            name="fullname"
                            placeholder="Enter your full name"
                            value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>"
                            required
                        >
                    </div>
 
                    <button type="submit" name="login" class="btn-primary">Sign In</button>
                </form>
 
                <div class="card-footer">
                    Don't have an account? <a href="register.php">Register here</a>
                </div>
 
            </div>
        </div>
    </div>
 
</body>