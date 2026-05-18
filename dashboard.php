<?php
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); }
include('connect.php');

$rooms_result = $conn->query("SELECT room_Number FROM Room");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIT-U CCS | Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* ── Dashboard specific styles ── */
        body {
            flex-direction: column;
            background: #f7f3ee;
        }
  
        /* ── Page content ── */
        .page-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
        }
 
        /* ── Override card width for dashboard ── */
        .card {
            max-width: 520px;
        }
 
        .card-header p {
            color: #7a6a5a;
            font-size: 13.5px;
            margin-top: 0.4rem;
        }
 
        /* ── Hint text ── */
        .hint {
            font-size: 12px;
            color: #a09080;
            margin-top: 0.4rem;
        }
 
        /* ── Textarea ── */
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
    </style>
</head>
<body>
 
    <!-- Navbar -->
    <nav class="navbar">
        <p class="navbar-brand">CIT-U &nbsp;|&nbsp; Issue Reporting System</p>
        <div class="navbar-links">
            <a href="dashboard.php" class="active">Home</a>
            <a href="readrecords.php">View Reports</a>
            <?php if($_SESSION['isAdmin'] == 1): ?>
            <a href="admin_record.php">Admin Panel</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </div>
        <p class="navbar-user">Logged in as <span><?php echo $_SESSION['user_name']; ?></span></p>
    </nav>
 
    <div class="page-content">
        <div class="card">
            <div class="gold-strip"></div>
            <div class="card-body">
 
                <div class="card-header">
                    <h2>Submit an Issue</h2>
                    <p>Fill in the details of the computer problem you encountered.</p>
                </div>
 
                <form action="readrecords.php" method="POST">
 
                    <!-- Room selection -->
                    <div class="form-group">
                        <label for="room">Room</label>
                        <select name="room" id="room" required>
                            <option value="">-- Select a Room --</option>
                            <?php while($room_row = $rooms_result->fetch_assoc()): ?>
                                <option value="<?php echo $room_row['room_Number']; ?>">
                                    <?php echo $room_row['room_Number']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
 
                    <!-- Computer ID input -->
                    <div class="form-group">
                        <label for="comp_id">Computer Number / Name</label>
                        <input type="text" id="comp_id" name="comp_id" placeholder="e.g. PC-05 or Desk 2">
                    </div>
 
                    <!-- Problem Description input-->
                    <div class="form-group">
                        <label for="desc">Problem Description</label>
                        <textarea id="desc" name="desc" placeholder="Describe the problem..." required></textarea>
                    </div>
 
                    <button type="submit" name="submit_report" class="btn-primary">Submit Report</button>
 
                </form>
 
            </div>
        </div>
    </div>
 
</body>
</html>