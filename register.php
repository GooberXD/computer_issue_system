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
        
        echo "<p style='color:green;'>Registration successful! <a href='login.php'>Click here to Login</a></p>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2>User Registration</h2>
<form method="POST">
    <input type="text" name="fullname" placeholder="Full Name" required><br><br>
    <input type="text" name="course" placeholder="Course/Section"><br><br>
    
    <label>Select Role:</label>
    <select name="role" required>
        <option value="Student">Student</option>
        <option value="Teacher">Teacher</option>
        <option value="Admin">Admin</option>
    </select><br><br>

    <input type="text" name="year_level" placeholder="Year Level (Students only)"><br><br>
    <input type="text" name="department" placeholder="Department (Teachers only)"><br><br>
    <input type="text" name="position" placeholder="Position (Admins only)"><br><br>

    <button type="submit" name="register">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>