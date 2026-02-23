<?php if(!isset($conn)){ include 'db_connect.php'; } ?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Departments</h3>
            <div class="card-tools">
                <!-- New Department Button triggers modal -->
                <button class="btn btn-flat btn-primary btn-sm" data-toggle="modal" data-target="#departmentModal" id="newDepartmentBtn">
                    <i class="fas fa-plus"></i> Add New Department
                </button>
            </div>
        </div>
        <div class="card-body">

<?php
// Check if a department ID is clicked
if(isset($_GET['dept_id']) && !empty($_GET['dept_id'])):
    $dept_id = intval($_GET['dept_id']);
    
    // Get department name
    $dept_query = $conn->query("SELECT name FROM departments WHERE id = $dept_id");
    if($dept_query && $dept_query->num_rows > 0):
        $dept = $dept_query->fetch_assoc();
?>

<!-- Back Button -->
<a href="index.php?page=departments" class="btn btn-flat btn-secondary mb-3">
    <i class="fas fa-arrow-left"></i> Back to Departments
</a>

<h4>Department: <b><?php echo htmlspecialchars($dept['name']); ?></b></h4>

<!-- Show all students for this department -->
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Class</th>
                <th>Gender</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>

<?php
$i = 1;

// Get students in this department
$qry = $conn->query("SELECT s.*, 
                    CONCAT(c.level, '-', c.section) as class,
                    CONCAT(s.firstname, ' ', COALESCE(s.middlename, ''), ' ', s.lastname) as fullname 
                    FROM students s 
                    LEFT JOIN classes c ON c.id = s.class_id 
                    WHERE s.department_id = $dept_id
                    ORDER BY s.lastname ASC, s.firstname ASC");

if($qry && $qry->num_rows > 0):
    while($row = $qry->fetch_assoc()):
        // Clean up fullname (remove extra spaces)
        $fullname = preg_replace('/\s+/', ' ', trim($row['fullname']));
?>
<tr>
    <td class="text-center"><?php echo $i++; ?></td>
    <td><?php echo htmlspecialchars($row['student_code']); ?></td>
    <td><?php echo ucwords(htmlspecialchars($fullname)); ?></td>
    <td><?php echo htmlspecialchars($row['class'] ?? 'N/A'); ?></td>
    <td><?php echo htmlspecialchars($row['gender'] ?? 'N/A'); ?></td>
    <td class="text-center">
        <a href="index.php?page=new_student&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
    </td>
</tr>
<?php 
    endwhile; 
else:
?>
<tr>
    <td colspan="6" class="text-center">No students found in this department.</td>
</tr>
<?php endif; ?>

        </tbody>
    </table>
</div>

<?php 
    else:
?>
<div class="alert alert-danger">Department not found.</div>
<a href="index.php?page=departments" class="btn btn-flat btn-secondary">
    <i class="fas fa-arrow-left"></i> Back to Departments
</a>
<?php
    endif;
else:
?>

<!-- SHOW ALL DEPARTMENTS FIRST -->
<!-- Search input -->
<input type="text" id="searchDepartment" class="form-control mb-3" placeholder="Search departments...">

<div class="table-responsive">
    <table class="table table-bordered table-hover" id="departmentTable">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Department Name</th>
                <th class="text-center">Total Students</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>

<?php
$i = 1;

// Get all departments with student count
$qry = $conn->query("SELECT d.*, 
                     (SELECT COUNT(*) FROM students WHERE department_id = d.id) as student_count 
                     FROM departments d 
                     ORDER BY d.name ASC");

if($qry && $qry->num_rows > 0):
    while($row = $qry->fetch_assoc()):
?>
<tr class="department-row">
    <td class="text-center"><?php echo $i++; ?></td>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td class="text-center"><?php echo $row['student_count']; ?></td>
    <td class="text-center">
        <a href="?page=departments&dept_id=<?php echo $row['id']; ?>" class="btn btn-flat btn-primary btn-sm">
            <i class="fas fa-users"></i> View Students
        </a>
        <button class="btn btn-flat btn-default btn-sm edit-department" 
                data-id="<?php echo $row['id']; ?>" 
                data-name="<?php echo htmlspecialchars($row['name']); ?>">
            <i class="fas fa-edit"></i> Edit
        </button>
        <button class="btn btn-flat btn-danger btn-sm delete-department" 
                data-id="<?php echo $row['id']; ?>" 
                data-name="<?php echo htmlspecialchars($row['name']); ?>">
            <i class="fas fa-trash"></i> Delete
        </button>
    </td>
</tr>
<?php 
    endwhile; 
else:
?>
<tr>
    <td colspan="4" class="text-center">No departments found. Click "Add New Department" to create one.</td>
</tr>
<?php endif; ?>

        </tbody>
    </table>
</div>

<?php endif; ?>

        </div>
    </div>
</div>

<!-- Department Modal (Add/Edit) -->
<div class="modal fade" id="departmentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="departmentForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="departmentModalTitle">New Department</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="departmentId">
        <div class="form-group">
            <label>Department Name</label>
            <input type="text" name="name" id="departmentName" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
    </form>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="deleteDepartmentName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteDepartment">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

    // Search departments (only works on the main table)
    $("#searchDepartment").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#departmentTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // New Department button
    $("#newDepartmentBtn").click(function(){
        $("#departmentModalTitle").text("New Department");
        $("#departmentForm")[0].reset();
        $("#departmentId").val('');
    });

    // Edit Department button
    $(document).on("click", ".edit-department", function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        $("#departmentModalTitle").text("Edit Department");
        $("#departmentId").val(id);
        $("#departmentName").val(name);
        $("#departmentModal").modal('show');
    });

    // Submit Add/Edit
    $("#departmentForm").submit(function(e){
        e.preventDefault();
        start_load(); // optional, if defined
        $.ajax({
            url: 'ajax.php?action=' + ($("#departmentId").val() == '' ? 'save_department' : 'update_department'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp){
                if(resp == 1){
                    alert_toast("Department saved successfully",'success');
                    setTimeout(function(){ location.reload(); }, 1000);
                } else {
                    alert_toast("Error saving department",'error');
                    end_load(); // if start_load was used
                }
            },
            error: function() {
                alert_toast("Request failed",'error');
                end_load();
            }
        });
    });

    // Delete department - show modal
    var deleteId = null;
    $(document).on("click", ".delete-department", function(){
        deleteId = $(this).data('id');
        var name = $(this).data('name');
        $("#deleteDepartmentName").text(name);
        $("#deleteModal").modal('show');
    });

    $("#confirmDeleteDepartment").click(function(){
        if(deleteId){
            start_load();
            $.ajax({
                url: 'ajax.php?action=delete_department',
                method: 'POST',
                data: {id: deleteId},
                success: function(resp){
                    if(resp == 1){
                        alert_toast("Department deleted successfully",'success');
                        setTimeout(function(){ location.reload(); }, 1000);
                    } else {
                        alert_toast("Cannot delete: department has students or other error",'error');
                        end_load();
                    }
                },
                error: function() {
                    alert_toast("Request failed",'error');
                    end_load();
                }
            });
            $("#deleteModal").modal('hide');
        }
    });

});

// Toast function (from first code)
function alert_toast(message, type){
    var bg = (type=='success')?'bg-success':'bg-danger';
    var toast = $('<div class="toast '+bg+' text-white" style="position:fixed; top:20px; right:20px; z-index:9999; min-width:200px;" data-delay="2000">'+
                  '<div class="toast-body">'+message+'</div></div>');
    $('body').append(toast);
    toast.toast('show');
    toast.on('hidden.bs.toast', function(){ $(this).remove(); });
}
</script>