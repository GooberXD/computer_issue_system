<?php
session_start();
include('connect.php');

if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

if(isset($_POST['submit_report'])){
    $room = $_POST['room'];
    $comp = $_POST['comp_id']; 
    $desc = $_POST['desc'];
    $date = date('Y-m-d');
    $uid = $_SESSION['user_id'];

    $sql = "INSERT INTO Issue_Report (issueRpt_computerRoom, issueRpt_computerID, issueRpt_problemDescription, issueRpt_Date, reporter_ID) 
            VALUES ('$room', '$comp', '$desc', '$date', '$uid')";
    
    if (!$conn->query($sql)) {
        echo "Error: " . $conn->error;
    }
}

$query = "SELECT Issue_Report.*, User.user_FullName 
          FROM Issue_Report 
          JOIN User ON Issue_Report.reporter_ID = User.user_ID 
          ORDER BY issueRpt_Date DESC";

$results = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Reports</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:hover { background-color: #f5f5f5; }
        .nav { margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="nav">
        <a href="dashboard.php">← Back to Dashboard</a> | 
        <strong>Viewing All Computer Issues</strong>
    </div>

    <h2>System Issue Reports</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Room</th>
                <th>PC Info</th>
                <th>Problem Description</th>
                <th>Reported By</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if($results->num_rows > 0) {
                while($row = $results->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['issueRpt_ID'] . "</td>";
                    echo "<td>" . $row['issueRpt_Date'] . "</td>";
                    echo "<td>" . $row['issueRpt_computerRoom'] . "</td>";
                    echo "<td>" . $row['issueRpt_computerID'] . "</td>";
                    echo "<td>" . $row['issueRpt_problemDescription'] . "</td>";
                    echo "<td>" . $row['user_FullName'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>No reports found in the system.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>