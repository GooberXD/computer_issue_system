<?php
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); }
include('connect.php');

$rooms_result = $conn->query("SELECT room_Number FROM Room");
?>

<h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>

<nav>
    <a href="dashboard.php">Home</a> | 
    <a href="readrecords.php">View Reports</a> | 
    <a href="logout.php">Logout</a>
</nav>

<hr>
<h3>Submit a Computer Issue</h3>
<form action="readrecords.php" method="POST">
    
    <label>Select Room:</label><br>
    <select name="room" required>
        <option value="">-- Select a Room --</option>
        <?php 
        // Loop through the rooms found in the Room table
        while($room_row = $rooms_result->fetch_assoc()) {
            echo "<option value='".$room_row['room_Number']."'>".$room_row['room_Number']."</option>";
        }
        ?>
    </select>
    <p style="font-size: 0.8em; color: gray;">Note: If no rooms appear, you must add them to the 'Room' table in phpMyAdmin first.</p>

    <label>Computer Number/Name:</label><br>
<input type="text" name="comp_id" placeholder="e.g. PC-05 or Desk 2"><br><br>

    <label>Problem Description:</label><br>
    <textarea name="desc" placeholder="Describe the problem..." required></textarea><br><br>

    <button type="submit" name="submit_report">Submit Report</button>
</form>