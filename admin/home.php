<h1>Welcome to <?php echo $_settings->info('name') ?></h1>
<hr class="bg-light">
<?php if($_settings->userdata('type') != 3):?>
<div class="row">
  <!-- Pending Applications -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hourglass-half"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pending Applications</span>
        <span class="info-box-number text-right">
          <?php 
            $pending = $conn->query("SELECT * FROM `leave_applications` WHERE YEAR(date_start) = YEAR(CURDATE()) AND status = 0")->num_rows;
            echo number_format($pending);
          ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Total Employees -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Employees</span>
        <span class="info-box-number text-right">
          <?php 
            $employees = $conn->query("SELECT * FROM `users` WHERE type = 3")->num_rows;
            echo number_format($employees);
          ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Total Leave Types -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-list-ul"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Type of Leave</span>
        <span class="info-box-number text-right">
          <?php 
            $leave_types = $conn->query("SELECT id FROM `leave_types` WHERE status = 1")->num_rows;
            echo number_format($leave_types);
          ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Rejected Applications -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times-circle"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Rejected Applications</span>
        <span class="info-box-number text-right">
          <?php 
            $rejected = $conn->query("SELECT * FROM `leave_applications` WHERE status = 2")->num_rows;
            echo number_format($rejected);
          ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Approved Applications (This Year) -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check-circle"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Approved Leaves This Year</span>
        <span class="info-box-number text-right">
          <?php 
            $approved = $conn->query("SELECT * FROM `leave_applications` WHERE status = 1 AND YEAR(date_start) = YEAR(CURDATE())")->num_rows;
            echo number_format($approved);
          ?>
        </span>
      </div>
    </div>
  </div>

</div>

<?php else: ?>
<!-- For Regular Employee -->
<div class="row">
  <!-- Personal Pending Applications -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hourglass-half"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pending Applications</span>
        <span class="info-box-number text-right">
          <?php 
            $pending = $conn->query("SELECT * FROM `leave_applications` WHERE YEAR(date_start) = YEAR(CURDATE()) AND status = 0 AND user_id = '{$_settings->userdata('id')}'")->num_rows;
            echo number_format($pending);
          ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Upcoming Leaves -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar-alt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Upcoming Leaves</span>
        <span class="info-box-number text-right">
          <?php 
            $upcoming = $conn->query("SELECT * FROM `leave_applications` WHERE date(date_start) > CURDATE() AND status = 1 AND user_id = '{$_settings->userdata('id')}'")->num_rows;
            echo number_format($upcoming);
          ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Total Leaves Taken -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Leaves Taken This Year</span>
        <span class="info-box-number text-right">
          <?php 
            $taken = $conn->query("SELECT * FROM `leave_applications` WHERE status = 1 AND YEAR(date_start) = YEAR(CURDATE()) AND user_id = '{$_settings->userdata('id')}'")->num_rows;
            echo number_format($taken);
          ?>
        </span>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>


<?php if($_settings->userdata('type') != 3):?>


  <?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<?php 
$meta_qry = $conn->query("SELECT * FROM employee_meta where user_id = '{$_settings->userdata('id')}' and meta_field = 'approver' ");
$is_approver = $meta_qry->num_rows > 0 && $meta_qry->fetch_array()['meta_value'] == 'on' ? true : false;
?>

<div class="card-body">
	<div class="container-fluid" style="background-color: #fff; padding: 20px; border-radius: 5px;">
		<table class="table table-hover table-stripped">
			<?php if($_settings->userdata('type') != 3): ?>
			<colgroup>
				<col width="10%">
				<col width="25%">
				<col width="25%">
				<col width="15%">
				<col width="15%">
				<col width="10%">
			</colgroup>
			<?php else: ?>
			<colgroup>
				<col width="10%">
				<col width="50%">
				<col width="15%">
				<col width="15%">
				<col width="10%">
			</colgroup>
			<?php endif; ?>
			<thead>
				<tr>
					<th>#</th>
					<?php if($_settings->userdata('type') != 3): ?>
					<th>Employee</th>
					<?php endif; ?>
					<th>Leave Type</th>
					<th>Days</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$i = 1;
				$where = '';
				if($_settings->userdata('type') == 3)
					$where = " and u.id = '{$_settings->userdata('id')}' ";
				$qry = $conn->query("SELECT l.*,concat(u.lastname,', ',u.firstname,' ',u.middlename) as `name`,lt.code,lt.name as lname from `leave_applications` l inner join `users` u on l.user_id=u.id inner join `leave_types` lt on lt.id = l.leave_type_id where (date_format(l.date_start,'%Y') = '".date("Y")."' or date_format(l.date_end,'%Y') = '".date("Y")."') {$where} order by FIELD(l.status,0,1,2,3), unix_timestamp(l.date_created) desc ");
				while($row = $qry->fetch_assoc()):
					$lt_qry = $conn->query("SELECT meta_value FROM `employee_meta` where user_id = '{$row['user_id']}' and meta_field = 'employee_id' ");
					$row['employee_id'] = ($lt_qry->num_rows > 0) ? $lt_qry->fetch_array()['meta_value'] : "N/A";
				?>
				<tr>
					<td class="text-center"><?php echo $i++; ?></td>
					<?php if($_settings->userdata('type') != 3): ?>
					<th>
						<small><b>ID: </b><?php echo $row['employee_id'] ?></small><br>
						<small><b>Name: </b><?php echo $row['name'] ?></small>
					</th>
					<?php endif; ?>
					<td><?php echo $row['code'] . ' - '. $row['lname'] ?></td>
					<td><?php echo $row['leave_days'] ?></td>
					<td class="text-center">
						<?php if($row['status'] == 1): ?>
							<span class="badge badge-success">Approved</span>
						<?php elseif($row['status'] == 2): ?>
							<span class="badge badge-danger">Denied</span>
						<?php elseif($row['status'] == 3): ?>
							<span class="badge badge-danger">Cancelled</span>
						<?php else: ?>
							<span class="badge badge-primary">Pending</span>
						<?php endif; ?>
					</td>
				</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Leave Application permanently?","delete_leave_application",[$(this).attr('data-id')])
		})
		$('.view_application').click(function(){
			uni_modal("<i class='fa fa-list'></i> Leave Application Details","leave_applications/view_application.php?id="+$(this).attr('data-id'))
		})
		
		$('.update_status').click(function(){
			uni_modal("<i class='fa fa-check-square'></i> Update Leave Application Status","leave_applications/update_status.php?id="+$(this).attr('data-id'))
		})

		// Disable search, pagination, and info
		$('.table').DataTable({
			paging: false,
			info: false,
			searching: false,
			ordering: false
		});
	})

	function delete_leave_application($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_leave_application",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- Chart Containers -->
<div class="row">
  <div class="col-md-6">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Leave Applications Status</h3>
      </div>
      <div class="card-body d-flex justify-content-center">
        <div style="max-width: 300px; width: 100%;">
          <canvas id="statusChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card card-outline card-success">
      <div class="card-header">
        <h3 class="card-title">Leaves Per Month (<?php echo date('Y'); ?>)</h3>
      </div>
      <div class="card-body">
        <canvas id="monthlyChart"></canvas>
      </div>
    </div>
  </div>
</div>



<?php
// Data for Pie Chart
$pending = $conn->query("SELECT * FROM `leave_applications` WHERE YEAR(date_start) = YEAR(CURDATE()) AND status = 0")->num_rows;
$approved = $conn->query("SELECT * FROM `leave_applications` WHERE YEAR(date_start) = YEAR(CURDATE()) AND status = 1")->num_rows;
$rejected = $conn->query("SELECT * FROM `leave_applications` WHERE YEAR(date_start) = YEAR(CURDATE()) AND status = 2")->num_rows;

// Data for Bar Chart (Leaves per Month)
$leaves_per_month = [];
for($m = 1; $m <= 12; $m++) {
    $count = $conn->query("SELECT * FROM `leave_applications` WHERE status = 1 AND MONTH(date_start) = {$m} AND YEAR(date_start) = YEAR(CURDATE())")->num_rows;
    $leaves_per_month[] = $count;
}
?>

<script>
// Pie Chart - Leave Status

const statusChart = new Chart(document.getElementById('statusChart'), {
    type: 'pie',
    data: {
        labels: ['Pending', 'Approved', 'Rejected'],
        datasets: [{
            data: [<?php echo $pending; ?>, <?php echo $approved; ?>, <?php echo $rejected; ?>],
            backgroundColor: ['#f39c12', '#00a65a', '#dd4b39']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Bar Chart - Leaves Per Month
const monthlyChart = new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Leaves Taken',
            backgroundColor: '#3c8dbc',
            data: <?php echo json_encode($leaves_per_month); ?>
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
<?php endif; ?>
<!-- Done with this page all the edits are done -->