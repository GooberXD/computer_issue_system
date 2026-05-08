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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIT-U CCS | View Reports</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* ── Page layout ── */
        body {
            flex-direction: column;
            background: #f7f3ee;
        }
 
        /* ── Page content ── */
        .page-content {
            flex: 1;
            padding: 2.5rem 2rem;
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
        }
 
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            color: #1a0a0a;
            margin-bottom: 0.3rem;
        }
 
        .page-subtitle {
            color: #7a6a5a;
            font-size: 13.5px;
            margin-bottom: 2rem;
        }
 
        /* ── Table ── */
        .table-wrapper {
            background: #fff;
            border: 1.5px solid #e0d4c4;
            border-radius: 12px;
            overflow: hidden;
        }
 
        .table-header {
            background: #6B1A1A;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
 
        .table-header h3 {
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
 
        .report-count {
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 12px;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
        }
 
        table {
            width: 100%;
            border-collapse: collapse;
        }
 
        thead tr {
            background: #faf5ee;
            border-bottom: 1.5px solid #e0d4c4;
        }
 
        th {
            padding: 0.85rem 1.2rem;
            text-align: left;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #5a4a3a;
        }
 
        td {
            padding: 0.9rem 1.2rem;
            font-size: 13.5px;
            color: #1a0a0a;
            border-bottom: 1px solid #f0e8dc;
        }
 
        tbody tr:last-child td {
            border-bottom: none;
        }
 
        tbody tr:hover td {
            background: #fdf8f2;
        }
 
        .empty-row td {
            text-align: center;
            color: #a09080;
            padding: 2.5rem;
            font-size: 14px;
        }
 
        /* ── ID badge ── */
        .id-badge {
            background: #f0e8dc;
            color: #6B1A1A;
            font-size: 12px;
            font-weight: 500;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            display: inline-block;
        }
    </style>
</head>
<body>
 
    <!-- Navbar -->
    <nav class="navbar">
        <p class="navbar-brand">CIT-U &nbsp;|&nbsp; Issue Reporting System</p>
        <div class="navbar-links">
            <a href="dashboard.php">Home</a>
            <a href="readrecords.php" class="active">View Reports</a>
            <a href="logout.php">Logout</a>
        </div>
        <p class="navbar-user">Logged in as <span><?php echo $_SESSION['user_name']; ?></span></p>
    </nav>
 
    <!-- Main content -->
    <div class="page-content">
 
        <h2 class="page-title">Issue Reports</h2>
        <p class="page-subtitle">All computer issues submitted across all rooms.</p>
 
        <div class="table-wrapper">
            <div class="table-header">
                <h3>System Issue Reports</h3>
                <span class="report-count"><?php echo $results->num_rows; ?> record(s)</span>
            </div>
 
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
                    <?php if($results->num_rows > 0): ?>
                        <?php while($row = $results->fetch_assoc()): ?>
                        <tr>
                            <td><span class="id-badge">#<?php echo $row['issueRpt_ID']; ?></span></td>
                            <td><?php echo $row['issueRpt_Date']; ?></td>
                            <td><?php echo $row['issueRpt_computerRoom']; ?></td>
                            <td><?php echo $row['issueRpt_computerID']; ?></td>
                            <td><?php echo $row['issueRpt_problemDescription']; ?></td>
                            <td><?php echo $row['user_FullName']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr class="empty-row">
                            <td colspan="6">No reports found in the system.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
 
    </div>
 
</body>
</html>
 