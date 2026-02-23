<?php
// Ensure database connection
if (!isset($conn)) {
    include 'db_connect.php';
}
?>
<div class="container-fluid py-4">
    <div class="card result-card shadow-lg border-0 rounded-4">
        <?php if (!isset($_SESSION['rs_id'])): ?>
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-0 pt-4 px-4 flex-wrap gap-2">
            <h4 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i>Results Overview</h4>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm rounded-pill px-4 py-2 shadow-sm" href="./index.php?page=new_result">
                    <i class="fas fa-plus-circle me-1"></i> Add New Result
                </a>
            </div>
        </div>
        <?php endif; ?>

        <div class="card-body p-3 p-md-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="resultTable">
                    <thead class="bg-light text-dark-50">
                        <tr>
                            <th class="text-center" style="width:5%;">#</th>
                            <th style="width:15%;">Student Code</th>
                            <th style="width:25%;">Student Name</th>
                            <th style="width:20%;">Class</th>
                            <th class="text-center" style="width:10%;">Subjects</th>
                            <th class="text-center" style="width:10%;">Average</th>
                            <th class="text-center" style="width:15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $where = "";

                        // If student is logged in, show only their results
                        if (isset($_SESSION['rs_id']) && !empty($_SESSION['rs_id'])) {
                            $student_id = intval($_SESSION['rs_id']);
                            // Ensure it's a valid positive integer
                            if ($student_id > 0) {
                                $where = " WHERE r.student_id = $student_id ";
                            }
                        }

                        // Base query
                        $sql = "SELECT r.*, 
                                       CONCAT(s.firstname, ' ', COALESCE(s.middlename,''), ' ', s.lastname) as name, 
                                       s.student_code, 
                                       CONCAT(c.level, '-', c.section) as class 
                                FROM results r 
                                INNER JOIN classes c ON c.id = r.class_id 
                                INNER JOIN students s ON s.id = r.student_id 
                                $where 
                                ORDER BY UNIX_TIMESTAMP(r.date_created) DESC";

                        // Optional: Debug output (uncomment to see the query and row count)
                        // echo "<!-- SQL: $sql -->";
                        // echo "<!-- Rows: " . $conn->query($sql)->num_rows . " -->";

                        $qry = $conn->query($sql);

                        if ($qry && $qry->num_rows > 0):
                            while ($row = $qry->fetch_assoc()):
                                $subjects = $conn->query("SELECT COUNT(*) as cnt FROM result_items WHERE result_id = " . $row['id'])->fetch_assoc()['cnt'];
                        ?>
                        <tr>
                            <td class="text-center fw-semibold"><?php echo $i++; ?></td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-dark px-3 py-2 rounded-pill d-inline-block"><?php echo htmlspecialchars($row['student_code']); ?></span></td>
                            <td class="fw-medium"><?php echo ucwords(htmlspecialchars($row['name'])); ?></td>
                            <td class="fw-medium"><?php echo ucwords(htmlspecialchars($row['class'])); ?></td>
                            <td class="text-center fw-semibold"><?php echo $subjects; ?></td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-75 text-white px-3 py-2 rounded-pill"><?php echo htmlspecialchars($row['marks_percentage']); ?>%</span>
                            </td>
                            <td class="text-center">
                                <?php if (isset($_SESSION['login_id'])): ?>
                                    <div class="btn-group" role="group" aria-label="Result actions">
                                        <a href="./index.php?page=edit_result&id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button data-id="<?php echo $row['id']; ?>" type="button" class="btn btn-outline-info btn-sm view_result" data-bs-toggle="tooltip" title="View Result">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm delete_result" data-id="<?php echo $row['id']; ?>" data-bs-toggle="tooltip" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php elseif (isset($_SESSION['rs_id'])): ?>
                                    <button data-id="<?php echo $row['id']; ?>" type="button" class="btn btn-info btn-sm view_result rounded-pill px-4">
                                        <i class="fas fa-eye me-1"></i> View Result
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No results found</h5>
                                <?php if (isset($_SESSION['rs_id'])): ?>
                                    <p class="text-muted">You don't have any published results yet.</p>
                                <?php else: ?>
                                    <p class="text-muted">Click "Add New Result" to create one.</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles for this page (unchanged, keep as is) -->
<style>
    /* ... your existing styles ... */
</style>

<!-- DataTables Initialization -->
<script>
    $(document).ready(function() {
        // Initialize DataTable if available
        if ($.fn.DataTable) {
            $('#resultTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                ordering: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search results...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    emptyTable: "No data available",
                    zeroRecords: "No matching results found"
                },
                dom: '<"d-flex justify-content-between align-items-center mb-3 flex-wrap"<"me-3"l><"flex-grow-1"f>>rtip',
                responsive: true
            });
        } else {
            console.log("DataTables not loaded");
        }

        // Activate tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(el) {
            return new bootstrap.Tooltip(el);
        });

        // Delete confirmation
        $('.delete_result').click(function() {
            _conf("Are you sure you want to delete this result?", "delete_result", [$(this).attr('data-id')]);
        });

        // View result modal
        $('.view_result').click(function() {
            uni_modal("Result Details", "view_result.php?id=" + $(this).attr('data-id'), 'mid-large');
        });

        // Status toggle (if used)
        $('.status_chk').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 2;
            if ($(this).attr('data-state-stats') !== undefined && $(this).attr('data-state-stats') == 'error') {
                $(this).removeAttr('data-state-stats');
                return false;
            }
            var id = $(this).attr('data-id');
            start_load();
            $.ajax({
                url: 'ajax.php?action=update_result_stats',
                method: 'POST',
                data: { id: id, status: status },
                error: function(err) {
                    console.log(err);
                    alert_toast("Something went wrong while updating the result's status.", 'error');
                    $('#status_chk').attr('data-state-stats', 'error').bootstrapToggle('toggle');
                    end_load();
                },
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Result status successfully updated.", 'success');
                        end_load();
                    } else {
                        alert_toast("Something went wrong while updating the result's status.", 'error');
                        $('#status_chk').attr('data-state-stats', 'error').bootstrapToggle('toggle');
                        end_load();
                    }
                }
            });
        });
    });

    // Delete function (called from _conf)
    function delete_result($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_result',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>