<?php
require_once 'db_connect.php';
?>

<div class="card card-outline card-primary">
	<div class="card-header">
		<div class="card-tools">
			<a class="btn btn-sm btn-default btn-flat border-primary new_subject" href="javascript:void(0)">
				<i class="fa fa-plus"></i> Add New
			</a>
		</div>
	</div>

	<div class="card-body">
		<table class="table table-hover table-bordered" id="list">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th>Code</th>
					<th>Subjects</th>
					<th>Description</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 1;
				$qry = $conn->query("SELECT * FROM subjects ORDER BY unix_timestamp(date_created) DESC");
				while($row = $qry->fetch_assoc()):
				?>
				<tr>
					<td class="text-center"><?php echo $i++; ?></td>
					<td><b><?php echo htmlspecialchars(ucwords($row['subject_code'])); ?></b></td>
					<td><b><?php echo htmlspecialchars(ucwords($row['subject'])); ?></b></td>
					<td><?php echo htmlspecialchars($row['description']); ?></td>
					<td class="text-center">
	                    <div class="btn-group">
	                        <a href="javascript:void(0)" 
	                           data-id="<?php echo $row['id']; ?>" 
	                           class="btn btn-primary btn-sm manage_subject">
	                          <i class="fas fa-edit"></i>
	                        </a>
	                        <button type="button" 
	                                class="btn btn-danger btn-sm delete_subject" 
	                                data-id="<?php echo $row['id']; ?>">
	                          <i class="fas fa-trash"></i>
	                        </button>
	                  </div>
					</td>
				</tr>	
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
$(document).ready(function(){

	if ($.fn.DataTable.isDataTable('#list')) {
		$('#list').DataTable().destroy();
	}

	$('#list').DataTable({
		columnDefs: [
			{ orderable: false, targets: 4 }
		]
	});

	$('.new_subject').click(function(){
		uni_modal("New Subject","manage_subject.php");
	});

	$('.manage_subject').click(function(){
		uni_modal("Manage Subject","manage_subject.php?id="+$(this).data('id'));
	});

	$('.delete_subject').click(function(){
		_conf("Are you sure to delete this Subject?","delete_subject",[$(this).data('id')]);
	});

});

function delete_subject(id){
	start_load();
	$.ajax({
		url:'ajax.php?action=delete_subject',
		method:'POST',
		data:{id:id},
		success:function(resp){
			if(resp == 1){
				alert_toast("Data successfully deleted",'success');
				setTimeout(function(){
					location.reload();
				},1500);
			}
		}
	});
}
</script>
