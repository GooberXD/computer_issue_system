<?php
include('connect.php');

if(isset($_POST['register'])){
    $fullname = $_POST['fullname'];
    $course = $_POST['course'];
    $role = $_POST['role']; 
    
    $isStudent = ($role == 'Student') ? 1 : 0;
    $isTeacher = ($role == 'Teacher') ? 1 : 0;
    $isAdmin = ($role == 'Admin') ? 1 : 0;

    $sql = "INSERT INTO User (user_FullName, user_Course, isAdmin, isStudent, isTeacher) 
            VALUES ('$fullname', '$course', '$isAdmin', '$isStudent', '$isTeacher')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id; 

        if($role == 'Student'){
            $year = $_POST['year_level'];
            $conn->query("INSERT INTO Student (student_ID, student_YearLevel) VALUES ('$last_id', '$year')");
        } elseif($role == 'Teacher') {
            $dept = $_POST['department'];
            $conn->query("INSERT INTO Teacher (teacher_ID, teacher_Department) VALUES ('$last_id', '$dept')");
        } elseif($role == 'Admin') {
            $pos = $_POST['position'];
            $conn->query("INSERT INTO Admin (admin_ID, admin_isActive, admin_Position) VALUES ('$last_id', 1, '$pos')");
        }
        
        echo "<script>alert('Registration successful! Click OK to proceed to login.'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIT-U CCS | Register</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* ── Role selector tabs ── */
        .role-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.2rem;
        }
 
        .role-tab {
            flex: 1;
            padding: 0.6rem 0.5rem;
            border: 1.5px solid #d4c4b0;
            border-radius: 8px;
            background: #faf8f5;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: #5a4a3a;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s;
        }
 
        .role-tab:hover {
            border-color: #6B1A1A;
            color: #6B1A1A;
        }
 
        .role-tab.active {
            background: #6B1A1A;
            border-color: #6B1A1A;
            color: #fff;
            font-weight: 500;
        }
 
        /* hidden real select — driven by tabs via JS */
        #role { display: none; }
    </style>
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
        <p class="panel-desc">Create your account to start reporting computer lab issues.</p>
    </div>
 
    
    <div class="panel-right">
        <div class="card">
            <div class="gold-strip"></div>
            <div class="card-body">
 
                <div class="card-header">
                    <h2>Create account</h2>
                    <p>Fill in your details to get started</p>
                </div>
 
                <?php if(!empty($error)): ?>
                    <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
 
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success">✅ <?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
 
                <form method="POST">
 
                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname"
                               placeholder="e.g. Juan dela Cruz"
                               required>
                    </div>
 
                    <!-- Course -->
                    <div class="form-group">
                        <label for="course">Course / Section</label>
                        <input type="text" id="course" name="course"
                               placeholder="e.g. BSIT 2-A"
                               required>
                    </div>
 
                    <!-- Role tabs -->
                    <div class="form-group">
                        <label>Role</label>
                        <div class="role-tabs">
                            <div class="role-tab active" onclick="setRole('Student', this)">Student</div>
                            <div class="role-tab" onclick="setRole('Teacher', this)">Teacher</div>
                            <div class="role-tab" onclick="setRole('Admin', this)">Admin</div>
                        </div>
                        <!-- Hidden select that actually submits the value -->
                        <select name="role" id="role">
                            <option value="Student" selected>Student</option>
                            <option value="Teacher">Teacher</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
 
                    <!-- Student: Year Level -->
                    <div class="form-group extra-field" id="field-student">
                        <label for="year_level">Year Level</label>
                        <input type="text" id="year_level" name="year_level"
                               placeholder="e.g. 2nd Year"
                               >
                    </div>
 
                    <!-- Teacher: Department -->
                    <div class="form-group extra-field" id="field-teacher">
                        <label for="department">Department</label>
                        <input type="text" id="department" name="department"
                               placeholder="e.g. College of Computer Studies"
                               >
                    </div>
 
                    <!-- Admin: Position -->
                    <div class="form-group extra-field" id="field-admin">
                        <label for="position">Position</label>
                        <input type="text" id="position" name="position"
                               placeholder="e.g. Lab Technician"
                               >
                    </div>
 
                    <button type="submit" name="register" class="btn-primary">Create Account</button>
                </form>
 
                <div class="card-footer">
                    Already have an account? <a href="login.php">Sign in here</a>
                </div>
 
            </div>
        </div>
    </div>
 
    <script>
        // Show the right extra field on page load
        showField('Student');
 
        function setRole(role, clickedTab) {
            document.getElementById('role').value = role;
            document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
            if(clickedTab) { clickedTab.classList.add('active'); }
            showField(role);
        }
 
        function showField(role) {
            document.getElementById('field-student').style.display = (role === 'Student') ? 'block' : 'none';
            document.getElementById('field-teacher').style.display = (role === 'Teacher') ? 'block' : 'none';
            document.getElementById('field-admin').style.display   = (role === 'Admin')   ? 'block' : 'none';
        }
    </script>
 
</body>
</html>