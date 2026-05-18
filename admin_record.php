<?php
session_start();
include('connect.php');

// Block non-admins
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
if($_SESSION['isAdmin'] != 1) { header("Location: dashboard.php"); exit(); }

// Handle delete
if(isset($_POST['delete_report'])){
    $id = intval($_POST['report_id']);
    $conn->query("DELETE FROM Issue_Report WHERE issueRpt_ID = '$id'");
    header("Location: admin_record.php");
    exit();
}

// Handle status update 
if(isset($_POST['update_status'])){
    $id = intval($_POST['issue_id']);
    $new_status = $_POST['new_status'];
    $allowed_statuses = ['Resolved', 'Pending', 'Unresolved'];

    if($id && in_array($new_status, $allowed_statuses)){
        $conn->query("UPDATE Issue_Report SET issueRpt_Status = '$new_status' WHERE issueRpt_ID = '$id'");
        echo json_encode(['success' => true, 'message' => 'Status updated successfully', 'new_status' => $new_status]);
    } 
    else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
    exit();
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
    <title>CIT-U CCS | Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { flex-direction: column; }

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

        .page-subtitle { color: #7a6a5a; font-size: 13.5px; margin-bottom: 2rem; }

        /* ── Table ── */
        .table-wrapper {
            background: #fff;
            border: 1.5px solid #e0d4c4;
            border-radius: 12px;
            overflow: visible;
        }

        .table-header {
            background: #6B1A1A;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 10px 10px 0 0;
        }

        .table-header h3 { color: #fff; font-size: 14px; font-weight: 500; letter-spacing: 0.5px; }

        .report-count {
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 12px;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
        }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #faf5ee; border-bottom: 1.5px solid #e0d4c4; }
        th {
            padding: 0.85rem 1.2rem;
            text-align: left;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #5a4a3a;
        }

        td { padding: 0.9rem 1.2rem; font-size: 13.5px; color: #1a0a0a; border-bottom: 1px solid #f0e8dc; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fdf8f2; }
        .empty-row td { text-align: center; color: #a09080; padding: 2.5rem; font-size: 14px; }

        .id-badge {
            background: #f0e8dc;
            color: #6B1A1A;
            font-size: 12px;
            font-weight: 500;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            display: inline-block;
        }

        .btn-delete {
            background: #fdecea;
            color: #8B2A2A;
            border: 1px solid #f5c0bc;
            padding: 0.35rem 0.8rem;
            border-radius: 6px;
            font-size: 12.5px;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-delete:hover { background: #8B2A2A; color: #fff; border-color: #8B2A2A; }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.55);
        }
        .modal-overlay.active { display: flex; }
        .modal-dialog {
            padding: 0;
            width: 380px;
            max-width: 90%;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            animation: modalIn 0.2s ease;
        }
        @keyframes modalIn {
            from { transform: scale(0.92); opacity: 0; }
            to   { transform: scale(1);    opacity: 1; }
        }
        .modal-header {
            background: #6B1A1A;
            padding: 1rem 1.4rem;
            border-radius: 0;
        }
        .modal-header h3 {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            margin: 0;
        }
        .modal-body { padding: 1.4rem; }
        .modal-footer { padding: 1rem 1.4rem; border-top: 1px solid #f0e8dc; }

        /* ── Status badges ── */
        .status-badge {
            cursor: pointer;
            user-select: none;
            transition: opacity 0.2s, transform 0.1s;
        }
        .status-badge:hover { opacity: 0.85; transform: scale(1.04); }
        .status-resolved   { background: #edf7ed; color: #2e6b2e; border: 1px solid #b2d9b2; }
        .status-pending    { background: #fffaed; color: #8B6B00; border: 1px solid #f5d9a8; }
        .status-unresolved { background: #fdecea; color: #8B2A2A; border: 1px solid #f5c0bc; }

        /* ── Status options in modal ── */
        .status-options { display: flex; flex-direction: column; gap: 0.6rem; }

        .status-option {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem 1rem;
            border-radius: 8px;
            border: 1.5px solid #e0d4c4;
            cursor: pointer;
            transition: border-color 0.15s, background 0.15s;
        }
        .status-option:hover { border-color: #6B1A1A; background: #fdf8f2; }
        .status-option input[type="radio"] { display: none; }
        .status-option.selected-resolved   { border-color: #b2d9b2; background: #edf7ed; }
        .status-option.selected-pending    { border-color: #f5d9a8; background: #fffaed; }
        .status-option.selected-unresolved { border-color: #f5c0bc; background: #fdecea; }

        .status-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .dot-resolved   { background: #2e6b2e; }
        .dot-pending    { background: #8B6B00; }
        .dot-unresolved { background: #8B2A2A; }

        .status-option-label { font-size: 13.5px; font-weight: 500; color: #1a0a0a; }

        /* ── Admin badge ── */
        .admin-badge {
            background: #D48C3C;
            color: #fff;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            margin-left: 0.5rem;
            vertical-align: middle;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <p class="navbar-brand">CIT-U &nbsp;|&nbsp; Issue Reporting System</p>
        <div class="navbar-links">
            <a href="dashboard.php">Home</a>
            <a href="readrecords.php">View Reports</a>
            <a href="admin_record.php" class="active">Admin Panel</a>
            <a href="logout.php">Logout</a>
        </div>
        <p class="navbar-user">
            Logged in as <span><?php echo $_SESSION['user_name']; ?></span>
            <span class="admin-badge">Admin</span>
        </p>
    </nav>

    <!-- Main content -->
    <div class="page-content">

        <h2 class="page-title">Admin Panel</h2>
        <p class="page-subtitle">Manage and remove computer issue reports.</p>

        <div class="table-wrapper">
            <div class="table-header">
                <h3>All Issue Reports</h3>
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
                        <th>Status</th>
                        <th>Action</th>
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
                            <td>
                                <?php
                                    $status = $row['issueRpt_status'];
                                    $status_class = 'status-pending';
                                    if ($status == 'Resolved')    $status_class = 'status-resolved';
                                    elseif ($status == 'Unresolved') $status_class = 'status-unresolved';
                                ?>
                                <span class="status-badge <?php echo $status_class; ?>"
                                      data-issue-id="<?php echo $row['issueRpt_ID']; ?>"
                                      data-current-status="<?php echo $status; ?>"
                                      title="Click to change status">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this report?')">
                                    <input type="hidden" name="report_id" value="<?php echo $row['issueRpt_ID']; ?>">
                                    <button type="submit" name="delete_report" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr class="empty-row">
                            <td colspan="8">No reports found in the system.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Status Update Modal -->
    <div class="modal-overlay" id="statusModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>Update Issue Status</h3>
            </div>
            <div class="modal-body">
                <label>Select New Status</label>
                <div class="status-options">
                    <div class="status-option" data-value="Resolved" onclick="selectOption(this)">
                        <input type="radio" name="status" value="Resolved">
                        <span class="status-dot dot-resolved"></span>
                        <span class="status-option-label">Resolved</span>
                    </div>
                    <div class="status-option" data-value="Pending" onclick="selectOption(this)">
                        <input type="radio" name="status" value="Pending">
                        <span class="status-dot dot-pending"></span>
                        <span class="status-option-label">Pending</span>
                    </div>
                    <div class="status-option" data-value="Unresolved" onclick="selectOption(this)">
                        <input type="radio" name="status" value="Unresolved">
                        <span class="status-dot dot-unresolved"></span>
                        <span class="status-option-label">Unresolved</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal" id="cancelBtn">Cancel</button>
                <button type="button" class="btn-modal btn-save" id="saveBtn">Save</button>
            </div>
        </div>
    </div>

    <script>
        let selectedIssueId = null;

        function selectOption(el) {
            document.querySelectorAll('.status-option').forEach(o =>
                o.classList.remove('selected-resolved', 'selected-pending', 'selected-unresolved')
            );
            const val = el.getAttribute('data-value');
            el.classList.add('selected-' + val.toLowerCase());
            el.querySelector('input[type="radio"]').checked = true;
        }

        document.querySelectorAll('.status-badge').forEach(badge => {
            badge.addEventListener('click', function () {
                selectedIssueId = this.getAttribute('data-issue-id');
                const currentStatus = this.getAttribute('data-current-status');

                document.getElementById('statusModal').classList.add('active');

                const currentOption = document.querySelector(`.status-option[data-value="${currentStatus}"]`);
                if (currentOption) selectOption(currentOption);
            });
        });

        document.getElementById('cancelBtn').addEventListener('click', function () {
            document.getElementById('statusModal').classList.remove('active');
            selectedIssueId = null;
        });

        document.getElementById('statusModal').addEventListener('click', function (e) {
            if (e.target === this) {
                this.classList.remove('active');
                selectedIssueId = null;
            }
        });

        document.getElementById('saveBtn').addEventListener('click', function () {
            const selectedRadio = document.querySelector('input[name="status"]:checked');
            if (!selectedRadio) { alert('Please select a status.'); return; }

            const newStatus = selectedRadio.value;
            const formData = new FormData();
            formData.append('issue_id', selectedIssueId);
            formData.append('new_status', newStatus);
            formData.append('update_status', '1');

            
            fetch('admin_record.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const badge = document.querySelector(`[data-issue-id="${selectedIssueId}"]`);
                        if (badge) {
                            badge.classList.remove('status-resolved', 'status-pending', 'status-unresolved');
                            badge.classList.add('status-' + newStatus.toLowerCase());
                            badge.textContent = newStatus;
                            badge.setAttribute('data-current-status', newStatus);
                        }
                        document.getElementById('statusModal').classList.remove('active');
                        selectedIssueId = null;
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(() => alert('An error occurred while updating the status.'));
        });
    </script>

</body>
</html>