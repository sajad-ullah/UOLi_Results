<?php if(!isset($conn)){ include 'db_connect.php'; } ?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><?php echo isset($id) ? 'Edit Student' : 'Add New Student' ?></h3>
            <div class="card-tools">
                <a href="departments.php" class="btn btn-flat btn-primary btn-sm">
                    <i class="fas fa-building"></i> View Departments
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="" id="manage-student">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div id="msg" class=""></div>
                        <div class="form-group text-dark">
                            <label for="" class="control-label">Student ID #</label>
                            <input type="text" class="form-control form-control-sm" name="student_code" value="<?php echo isset($student_code) ? $student_code : '' ?>" required>
                        </div>
                        <div class="form-group text-dark">
                            <label for="" class="control-label">First Name</label>
                            <input type="text" class="form-control form-control-sm" name="firstname" value="<?php echo isset($firstname) ? $firstname : '' ?>" required>
                        </div>
                        <div class="form-group text-dark">
                            <label for="" class="control-label">Middle Name</label>
                            <input type="text" class="form-control form-control-sm" name="middlename" value="<?php echo isset($middlename) ? $middlename : '' ?>">
                        </div>
                        <div class="form-group text-dark">
                            <label for="" class="control-label">Last Name</label>
                            <input type="text" class="form-control form-control-sm" name="lastname" value="<?php echo isset($lastname) ? $lastname : '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="control-label">Department</label>
                            <select name="department_id" id="department_id" class="custom-select custom-select-sm" required>
                                <option value="">-- Select Department --</option>
                                <?php 
                                $depts = $conn->query("SELECT id, name FROM departments ORDER BY name ASC");
                                while($dept = $depts->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $dept['id']; ?>" <?php echo isset($department_id) && $department_id == $dept['id'] ? 'selected' : '' ?>>
                                        <?php echo htmlspecialchars($dept['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group text-dark">
                            <label for="" class="control-label">Address</label>
                            <textarea name="address" id="address" cols="30" rows="4" class="form-control"><?php echo isset($address) ? $address : '' ?></textarea>
                        </div>
                        <div class="form-group text-dark">
                            <label for="" class="control-label">Class</label>
                            <select name="class_id" id="class_id" class="form-control select2 select2-sm" required>
                                <option value="">-- Select Class --</option> 
                                <?php 
                                $classes = $conn->query("SELECT * FROM classes ORDER BY level ASC, section ASC");
                                while($row = $classes->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($class_id) && $class_id == $row['id'] ? 'selected' : '' ?>>
                                        <?php echo ucwords($row['level'].'-'.$row['section']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer border-top border-info">
            <div class="d-flex w-100 justify-content-center align-items-center">
                <button class="btn btn-flat bg-gradient-primary mx-2" form="manage-student">Save</button>
                <a class="btn btn-flat bg-gradient-secondary mx-2" href="./index.php?page=student_list">Cancel</a>
            </div>
        </div>
    </div>
</div>

<script>
    $('#manage-student').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_student',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp == 1){
                    alert_toast('Data successfully saved',"success");
                    setTimeout(function(){
                        // After save, go to the department page of this student
                        var dept_id = $('#department_id').val();
                        if(dept_id) {
                            location.href = 'departments.php?dept_id=' + dept_id;
                        } else {
                            location.href = 'index.php?page=student_list';
                        }
                    },2000)
                }else if(resp == 2){
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Student Code already exists.</div>')
                    end_load()
                } else {
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error saving data.</div>')
                    end_load()
                }
            }
        })
    })
</script>