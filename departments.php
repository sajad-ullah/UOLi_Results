<?php
// departments.php
include 'db_connect.php';
?>


<style>

/* Additional Styles for Better UI */
.semester-btn {
    margin: 5px;
    transition: all 0.3s;
    border-radius: 20px !important;
    padding: 8px 20px !important;
    font-weight: 500;
}

.semester-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.semester-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.department-actions .btn {
    margin: 0 3px;
    border-radius: 20px;
    padding: 5px 15px;
}

.table th {
    background-color: #f4f6f9;
    font-weight: 600;
}

.badge {
    padding: 5px 8px;
    font-size: 11px;
    border-radius: 12px;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 3px solid #007bff;
}

.card-outline.card-info .card-header {
    background-color: #e8f4f8;
    border-bottom: 3px solid #17a2b8;
}

.card-outline.card-warning .card-header {
    background-color: #fff3cd;
    border-bottom: 3px solid #ffc107;
}

.badge-pill {
    padding: 5px 15px;
    font-size: 12px;
    border-radius: 20px;
}

.semester-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 30px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #dee2e6;
}

.search-box {
    border-radius: 20px;
    padding: 8px 15px;
    border: 2px solid #dee2e6;
    transition: all 0.3s;
    width: 250px;
}

.search-box:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    outline: none;
}

.student-row {
    transition: all 0.2s;
}

.student-row:hover {
    background-color: #e8f4f8 !important;
    cursor: pointer;
}

.action-btn {
    border-radius: 15px;
    padding: 4px 12px;
    font-size: 11px;
    margin: 0 2px;
}

.empty-state {
    padding: 50px;
    text-align: center;
    color: #6c757d;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #dee2e6;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}
</style>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="page-header w-100">
                <h3 class="card-title">
                    <?php 
                    if(isset($_GET['dept_id']) && !empty($_GET['dept_id'])):
                        $dept_id = intval($_GET['dept_id']);
                        $dept_query = $conn->query("SELECT * FROM departments WHERE id = $dept_id");
                        $dept = $dept_query->fetch_assoc();
                        echo '<i class="fas fa-building"></i> ' . htmlspecialchars($dept['name']);
                    else:
                        echo '<i class="fas fa-list"></i> All Departments';
                    endif;
                    ?>
                </h3>
                <?php if(!isset($_GET['dept_id'])): ?>
                <div class="header-actions">
                    <a href="index.php?page=new_department" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle"></i> New Department
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <?php
            // Check if a specific department is selected
            if(isset($_GET['dept_id']) && !empty($_GET['dept_id'])):
                $dept_id = intval($_GET['dept_id']);
                
                // Get all classes (semesters)
                $classes_query = $conn->query("SELECT * FROM classes ORDER BY level ASC, section ASC");
                
                // Get active semester from URL – no auto‑selection
                $active_semester = isset($_GET['semester_id']) ? intval($_GET['semester_id']) : 0;
            ?>
                
                <!-- Semester Buttons -->
                <div class="semester-container">
                    <?php 
                    if($classes_query && $classes_query->num_rows > 0):
                        while($class = $classes_query->fetch_assoc()): 
                            $class_id = $class['id'];
                            $student_count = $conn->query("SELECT COUNT(*) as cnt FROM students WHERE class_id = $class_id AND department_id = $dept_id")->fetch_assoc()['cnt'];
                            $active_class = ($active_semester == $class_id) ? 'active' : '';
                    ?>
                            <a href="index.php?page=departments&dept_id=<?php echo $dept_id; ?>&semester_id=<?php echo $class_id; ?>"
                               class="btn btn-outline-primary semester-btn <?php echo $active_class; ?>">
                                <i class="fas fa-layer-group"></i> 
                                <?php echo ucwords($class['level'] . ' - ' . $class['section']); ?>
                                <span class="badge badge-light ml-2"><?php echo $student_count; ?></span>
                            </a>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <div class="alert alert-warning mb-0 w-100">
                            <i class="fas fa-exclamation-triangle"></i> No semesters found. 
                            <a href="index.php?page=new_class" class="alert-link">Create a new semester</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Display Selected Semester Students -->
                <?php 
                $display_class_id = $active_semester;
                if($display_class_id > 0):
                    $class_info = $conn->query("SELECT * FROM classes WHERE id = $display_class_id")->fetch_assoc();
                ?>
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-users"></i> 
                                Students in <?php echo ucwords($class_info['level'] . ' - ' . $class_info['section']); ?>
                            </h5>
                            <div class="card-tools d-flex gap-2">
                                <input type="text" id="searchInput" class="form-control search-box" placeholder="Search students..." style="width:200px;">
                                <a href="index.php?page=new_student&dept_id=<?php echo $dept_id; ?>&class_id=<?php echo $display_class_id; ?>" 
                                   class="btn btn-success btn-sm ml-2">
                                    <i class="fas fa-user-plus"></i> Add Student
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered" id="studentTable">
                                    <thead>
                                        <tr class="bg-gradient-secondary">
                                            <th class="text-center">#</th>
                                            <th style="color: black;">Registration</th>
                                            <th style="color: black;">Name</th>
                                            <th style="color: black;">Address</th>
                                            <th  style="color: black;" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $students_query = $conn->query("
                                        SELECT s.*, 
                                               CONCAT(s.firstname, ' ', COALESCE(s.middlename, ''), ' ', s.lastname) as fullname
                                        FROM students s
                                        WHERE s.department_id = $dept_id AND s.class_id = $display_class_id
                                        ORDER BY s.lastname ASC
                                    ");
                                    
                                    if($students_query->num_rows > 0):
                                        $i = 1;
                                        while($row = $students_query->fetch_assoc()):
                                            $fullname = trim(preg_replace('/\s+/', ' ', $row['fullname']));
                                            // Check if result exists
                                            $result_check = $conn->query("SELECT id FROM results WHERE student_id = ".$row['id']." LIMIT 1");
                                            $has_result = ($result_check && $result_check->num_rows > 0);
                                            if($has_result) {
                                                $result_id = $result_check->fetch_assoc()['id'];
                                            }
                                    ?>
                                        <tr class="student-row">
                                            <td class="text-center"><?php echo $i++; ?></td>
                                            <td><span class="badge badge-secondary"><?php echo htmlspecialchars($row['student_code']); ?></span></td>
                                            <td><?php echo ucwords(htmlspecialchars($fullname)); ?></td>
                                            <td><?php echo htmlspecialchars($row['address'] ?? 'N/A'); ?></td>
                                            <td class="text-center">
                                                <!-- View Student Details
                                                <a href="index.php?page=view_student&id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-info btn-sm action-btn" 
                                                   title="View Student">
                                                    <i class="fas fa-eye"></i>
                                                </a> -->

                                                <!-- Edit Student -->
                                                <a href="index.php?page=edit_student&id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-primary btn-sm action-btn" 
                                                   title="Edit Student">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <?php if($has_result): ?>
                                                    <!-- View Result (Printable) -->
                                                  
                                                    <!-- View Result (Printable) -->
                                                    <a href="view_result.php?id=<?php echo urlencode($result_id); ?>&class_id=<?php echo urlencode($display_class_id); ?>" 
                                                       class="btn btn-success btn-sm action-btn"
                                                       title="Print Student Result"
                                                       onclick="openPrintWindow(this.href); return false;">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    
                                                    <!-- Edit Result -->
                                                    <a href="index.php?page=edit_result&id=<?php echo $result_id; ?>&class_id=<?php echo $display_class_id; ?>" 
                                                       class="btn btn-warning btn-sm action-btn" 
                                                       title="Edit Result">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <!-- Add Result -->
                                                    <a href="index.php?page=new_result&student_id=<?php echo $row['id']; ?>&class_id=<?php echo $display_class_id; ?>" 
                                                       class="btn btn-secondary btn-sm action-btn" 
                                                       title="Add Result">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <!-- Delete Student -->
                                            <button type="button"
                                                    class="btn btn-danger btn-sm action-btn delete-student" 
                                                    data-id="<?php echo $row['id']; ?>" 
                                                    data-name="<?php echo htmlspecialchars($fullname); ?>"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            </td>
                                        </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="5" class="empty-state">
                                                <i class="fas fa-user-slash"></i>
                                                <h5>No Students Found</h5>
                                                <p>Click the button above to add students to this semester.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-hand-point-up"></i> Please select a semester from the buttons above to view students.
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- Display all departments with edit/delete functionality -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="searchDepartment" class="form-control search-box" placeholder="Search departments...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered" id="departmentTable">
                        <thead>

                        
                            <tr class="bg-gradient-primary">
                                <th class="text-center">#</th>
                                <th>Department Name</th>
                                <th class="text-center">Total Students</th>
                                <th class="text-center">Total Semesters</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $departments = $conn->query("
                            SELECT d.*, 
                                   (SELECT COUNT(*) FROM students WHERE department_id = d.id) as student_count,
                                   (SELECT COUNT(DISTINCT class_id) FROM students WHERE department_id = d.id) as semester_count
                            FROM departments d 
                            ORDER BY d.name ASC
                        ");
                        
                        if($departments->num_rows > 0):
                            $i = 1;
                            while($row = $departments->fetch_assoc()):
                        ?>
                        
                            <tr class="department-row">
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                <td class="text-center">
                                    <span class="badge badge-primary badge-pill">
                                        <i class="fas fa-users"></i> <?php echo $row['student_count']; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info badge-pill">
                                        <i class="fas fa-layer-group"></i> <?php echo $row['semester_count']; ?>
                                    </span>
                                </td>
                                <td class="text-center department-actions">
                                    <a href="?dept_id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="index.php?page=edit_department&id=<?php echo $row['id']; ?>" 
                                       class="btn btn-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm delete-department" 
                                            data-id="<?php echo $row['id']; ?>" 
                                            data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <i class="fas fa-building"></i>
                                    <h5>No Departments Found</h5>
                                    <p>Click the "New Department" button to create one.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>    
                    </tbody>
                    </table>
                    
                </div>
            <?php endif; ?>
            
        </div>
    </div>
    
</div>

<script>
function openPrintWindow(url) {

    var printWindow = window.open(url, 'PrintWindow', 
        'width=1200,height=700,left=10,top=50');

    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
    };
}
</script>

<script>

$(document).ready(function(){
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-dismiss alerts after 5 seconds
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 5000);

    // Search functionality for students
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#studentTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Search functionality for departments
    $("#searchDepartment").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#departmentTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

   
$(document).ready(function(){

    // Use event delegation (important for dynamic tables)
    $(document).on("click", ".delete-student", function(){

        var id   = $(this).data("id");
        var name = $(this).data("name");

        if(confirm("Are you sure you want to delete " + name + " ?")){

            $.ajax({
                url: "delete_student.php",
                type: "POST",
                data: { id: id },
                success: function(response){

                    if(response.trim() === "success"){
                        alert("Student deleted successfully!");
                        location.reload();
                    }else{
                        alert("Delete failed!");
                    }

                },
                error: function(){
                    alert("Something went wrong!");
                }
            });

        }

    });

});


    // Delete handlers
    var deleteId = null;
    var deleteType = null;

    $(document).on('click', '.delete-student', function(){
        deleteId = $(this).data('id');
        deleteType = 'student';
        var name = $(this).data('name');
        $("#deleteItemName").text(name);
        $("#deleteModal").modal('show');
    });

    $(document).on('click', '.delete-department', function(){
        deleteId = $(this).data('id');
        deleteType = 'department';
        var name = $(this).data('name');
        $("#deleteItemName").text(name);
        $("#deleteModal").modal('show');
    });

    $("#confirmDelete").click(function(){
        if(deleteId && deleteType) {
            $.ajax({
                url: 'ajax.php?action=delete_' + deleteType,
                method: 'POST',
                data: {id: deleteId},
                success: function(resp){
                    if(resp == 1) {
                        alert_toast(deleteType.charAt(0).toUpperCase() + deleteType.slice(1) + " deleted successfully", 'success');
                        setTimeout(function(){
                            location.reload();
                        }, 1500);
                    } else {
                        alert_toast("Failed to delete " + deleteType, 'error');
                    }
                }
            });
        }
        $("#deleteModal").modal('hide');
    });

    // Add animation to semester buttons
    $(".semester-btn").click(function(e) {
        $(".semester-btn").removeClass('active');
        $(this).addClass('active');
    });

    // Make student rows clickable (optional)
    $(document).on('dblclick', '.student-row', function(){
        var studentId = $(this).find('.delete-student').data('id');
        if(studentId) {
            window.location.href = 'index.php?page=edit_student&id=' + studentId;
        }
    });
});

</script>