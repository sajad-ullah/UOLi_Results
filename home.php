<?php
include('db_connect.php');

// Ensure the database connection exists
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>

<?php if ($_SESSION['login_type'] == 1): ?>
    <div class="row">
        <!-- Total Students -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <?php
                    $result = $conn->query("SELECT * FROM students");
                    $total_students = ($result) ? $result->num_rows : 0;
                    ?>
                    <h3><?php echo $total_students; ?></h3>
                    <p>Total Students</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="./index.php?page=student_list" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>


                <!-- <li class="nav-item">
                      <a href="./index.php?page=student_list" class="nav-link nav-student_list">
                          <i class="far fa-circle nev-icon"></i>
                          <p>Departments</p>
                      </a>
                 </li> -->

        <!-- Total Classes -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-success">
        <div class="inner">
            <?php
            $total_departments = 0;

            $query = "SELECT COUNT(*) AS total FROM departments";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $total_departments = (int)$row['total'];
            }
            ?>
            <h3><?php echo $total_departments; ?></h3>
            <p>Total Departments</p>
        </div>

        <div class="icon">
            <i class="fas fa-building"></i>
        </div>

        <a href="./index.php?page=student_list" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>


        <!-- Total Subjects -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <?php
                    $result = $conn->query("SELECT * FROM subjects");
                    $total_subjects = ($result) ? $result->num_rows : 0;
                    ?>
                    <h3><?php echo $total_subjects; ?></h3>
                    <p>Total Subjects</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="index.php?page=subjects" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

<div class="col-lg-3 col-6">
    <div class="small-box bg-success">
        <div class="inner">
<?php
$result_count_query = $conn->query("SELECT COUNT(*) AS total FROM results");

if($result_count_query){
    $row = $result_count_query->fetch_assoc();
    $total_results = $row['total'];
} else {
    $total_results = 0;
}
?>
<h3><?php echo $total_results; ?></h3>
            <p>Total Results</p>
        </div>
        <div class="icon">
            <i class="fas fa-chart-bar"></i>

        </div>
        <a href="./index.php?page=results" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
        
        
    </div>
</div>
<br>
<div class="col-lg-3 col-6">
    <div class="small-box bg-info">
        <div class="inner">
            <?php
            $total_classes = 0;

            $query = "SELECT COUNT(*) AS total FROM classes";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $total_classes = (int)$row['total'];
            }
            ?>
            <h3><?php echo $total_classes; ?></h3>
            <p>Total Classes</p>
        </div>
        <div class="icon">
            <i class="fas fa-layer-group"></i>
        </div>
        <a href="index.php?page=classes" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>



<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Welcome, <?php echo htmlspecialchars($_SESSION['login_name'] ?? 'User'); ?>!</h4>
                    <p>You are logged in as a non‑administrative user.</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>