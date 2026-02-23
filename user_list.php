<?php include 'db_connect.php'; ?>
<?php
// Optional: Add any necessary logic here
?>
<div class="container-fluid py-4">
    <div class="card result-card shadow-lg border-0 rounded-4">
        <?php if (!isset($_SESSION['rs_id'])): ?>
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-0 pt-4 px-4">
            <h4 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i>Results Overview</h4>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm rounded-pill px-4 py-2 shadow-sm" href="./index.php?page=new_result">
                    <i class="fas fa-plus-circle me-1"></i> Add New Result
                </a>
            </div>
        </div>
        <?php endif; ?>

        <div class="card-body p-4">
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
                        if (isset($_SESSION['rs_id'])) {
                            $where = " WHERE r.student_id = {$_SESSION['rs_id']} ";
                        }
                        $qry = $conn->query("SELECT r.*, CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) as name, s.student_code, CONCAT(c.level, '-', c.section) as class FROM results r INNER JOIN classes c ON c.id = r.class_id INNER JOIN students s ON s.id = r.student_id $where ORDER BY UNIX_TIMESTAMP(r.date_created) DESC");
                        while ($row = $qry->fetch_assoc()):
                            $subjects = $conn->query("SELECT * FROM result_items WHERE result_id = " . $row['id'])->num_rows;
                        ?>
                        <tr>
                            <td class="text-center fw-semibold"><?php echo $i++; ?></td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-dark px-3 py-2 rounded-pill"><?php echo htmlspecialchars($row['student_code']); ?></span></td>
                            <td class="fw-medium"><?php echo ucwords(htmlspecialchars($row['name'])); ?></td>
                            <td class="fw-medium"><?php echo ucwords(htmlspecialchars($row['class'])); ?></td>
                            <td class="text-center fw-semibold"><?php echo $subjects; ?></td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-75 text-white px-3 py-2 rounded-pill"><?php echo htmlspecialchars($row['marks_percentage']); ?>%</span>
                            </td>
                            <td class="text-center">
                                <?php if (isset($_SESSION['login_id'])): ?>
                                    <div class="btn-group" role="group">
                                        <a href="./index.php?page=edit_result&id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm rounded-start-3" data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button data-id="<?php echo $row['id']; ?>" type="button" class="btn btn-outline-info btn-sm view_result" data-bs-toggle="tooltip" title="View Result">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-end-3 delete_result" data-id="<?php echo $row['id']; ?>" data-bs-toggle="tooltip" title="Delete">
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
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles for this page -->
<style>
    /* Import Google Fonts (if not already included) */
    @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f7fc;
    }

    .result-card {
        background: #ffffff;
        border-radius: 24px !important;
        transition: all 0.2s ease;
    }

    .result-card .card-header h4 {
        color: #1e293b;
        font-size: 1.5rem;
        letter-spacing: -0.02em;
    }

    .result-card .btn-primary {
        background: linear-gradient(145deg, #2563eb, #1d4ed8);
        border: none;
        font-weight: 500;
        transition: 0.2s;
    }

    .result-card .btn-primary:hover {
        background: linear-gradient(145deg, #1d4ed8, #1e3a8a);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.5);
    }

    /* Table styling */
    #resultTable thead th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        border-bottom-width: 2px;
        border-color: #e2e8f0;
        padding: 1rem 0.75rem;
    }

    #resultTable tbody tr {
        transition: 0.2s;
        border-bottom: 1px solid #f1f5f9;
    }

    #resultTable tbody tr:hover {
        background-color: #f8fafc;
        transform: scale(1.002);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    #resultTable td {
        padding: 1rem 0.75rem;
        color: #334155;
        font-size: 0.95rem;
        vertical-align: middle;
    }

    /* Badges */
    .badge.bg-secondary {
        background-color: #e9eef3 !important;
        color: #1e293b !important;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .badge.bg-success {
        background: linear-gradient(145deg, #10b981, #059669) !important;
        font-weight: 600;
        font-size: 0.85rem;
        border: none;
    }

    /* Button groups */
    .btn-group .btn-outline-primary,
    .btn-group .btn-outline-info,
    .btn-group .btn-outline-danger {
        border-width: 1.5px;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        transition: all 0.15s;
    }

    .btn-group .btn-outline-primary {
        color: #2563eb;
        border-color: #2563eb;
    }

    .btn-group .btn-outline-primary:hover {
        background-color: #2563eb;
        color: #fff;
    }

    .btn-group .btn-outline-info {
        color: #0891b2;
        border-color: #0891b2;
    }

    .btn-group .btn-outline-info:hover {
        background-color: #0891b2;
        color: #fff;
    }

    .btn-group .btn-outline-danger {
        color: #dc2626;
        border-color: #dc2626;
    }

    .btn-group .btn-outline-danger:hover {
        background-color: #dc2626;
        color: #fff;
    }

    /* Tooltip custom */
    [data-bs-toggle="tooltip"] {
        cursor: pointer;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .result-card .card-header {
            flex-direction: column;
            align-items: start;
            gap: 10px;
        }
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
    }
</style>

<!-- DataTables Initialization (if used) -->
<script>
    $(document).ready(function() {
        // Initialize DataTable for better UX (optional, but recommended)
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
                },
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"me-3"l><"flex-grow-1"f>>rtip',
            });
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

        // Status toggle (if needed)
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