<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

$selected_dept = isset($_GET['dept_id']) ? intval($_GET['dept_id']) : 0;

// Fetch all departments
$dept_query = $conn->query("SELECT id, name FROM departments ORDER BY name");
if (!$dept_query) {
    die("Error fetching departments: " . $conn->error);
}

$students = null;
$department_name = '';
$error = '';

if ($selected_dept > 0) {
    // Get department name
    $dept_stmt = $conn->prepare("SELECT name FROM departments WHERE id = ?");
    if (!$dept_stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $dept_stmt->bind_param("i", $selected_dept);
    $dept_stmt->execute();
    $dept_result = $dept_stmt->get_result();
    if ($dept_result->num_rows > 0) {
        $dept_row = $dept_result->fetch_assoc();
        $department_name = htmlspecialchars($dept_row['name']);
    } else {
        $error = "Department not found.";
        $selected_dept = 0;
    }
    $dept_stmt->close();

    if ($selected_dept > 0) {
        // Fetch students – use your actual column names
        $student_stmt = $conn->prepare("SELECT id, student_code, firstname, middlename, lastname FROM students WHERE department_id = ?");
        if (!$student_stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $student_stmt->bind_param("i", $selected_dept);
        $student_stmt->execute();
        $students = $student_stmt->get_result();
        $student_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Students by Department</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        select, button { padding: 5px; margin: 5px 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Select Department to View Students</h1>
    <form method="get" action="">
        <label for="dept">Department:</label>
        <select name="dept_id" id="dept">
            <option value="">-- Choose a department --</option>
            <?php
            // Reset pointer to loop again
            $dept_query->data_seek(0);
            while ($dept = $dept_query->fetch_assoc()):
            ?>
                <option value="<?php echo $dept['id']; ?>" <?php if ($selected_dept == $dept['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($dept['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Show Students</button>
    </form>

    <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($selected_dept > 0): ?>
        <h2>Students in <?php echo $department_name; ?></h2>
        <?php if ($students && $students->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Code</th>
                        <th>Full Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $students->fetch_assoc()): ?>
                    <?php
                        // Build full name
                        $fullname = trim($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']);
                        $fullname = preg_replace('/\s+/', ' ', $fullname); // remove extra spaces
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['student_code']); ?></td>
                        <td><?php echo htmlspecialchars($fullname); ?></td>
                        <td>
                            <a href="new_student.php?id=<?php echo $row['id']; ?>">Edit</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No students found in this department.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>