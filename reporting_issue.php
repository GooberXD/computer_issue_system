<?php include('config/db.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Report an Issue</title>
</head>
<body>
    <h2>Report a Computer Problem</h2>
    <form action="submit_report.php" method="POST">
        <label>Room Number:</label>
        <input type="text" name="room_num" required><br><br>

        <label>Computer ID (Optional):</label>
        <input type="number" name="comp_id"><br><br>

        <label>Describe the Problem:</label><br>
        <textarea name="description" rows="5" required></textarea><br><br>

        <button type="submit" name="submit">Submit Report</button>
    </form>
</body>
</html>