<?php
session_start();
include ('connection.php');
$name = $_SESSION['name'];
$id = $_SESSION['id'];
if(empty($id))
{
    header("Location: index.php"); 
    exit();
}

// Toggle visitor status
if (isset($_GET['toggle_status'])) {
    $visitor_id = $_GET['toggle_status'];
    $get_visitor = mysqli_query($conn, "SELECT status FROM tbl_visitors WHERE id='$visitor_id'");
    if ($row = mysqli_fetch_assoc($get_visitor)) {
        if ($row['status'] == 1) { // Only allow toggling from In (1) to Out (0)
            $update_query = "UPDATE tbl_visitors SET status=0, out_time=NOW() WHERE id='$visitor_id'";
            mysqli_query($conn, $update_query);
        }
        header("Location: manage-visitors.php");
        exit();
    }
}
?>
<?php include('include/header.php'); ?>
<div id="wrapper">
  <?php include('include/side-bar.php'); ?>
  <div id="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">View Visitors</a>
        </li> 
      </ol>
<form method="post">
<div class="form-group row" style="padding: 20px;">
  <label class="col-lg-0 col-form-label-report" for="from">From</label>
  <div class="col-lg-3">
      <input type="text" class="form-control" id="from_date" name="from_date" placeholder="Select Date" required>
  </div>

  <label class="col-lg-0 col-form-label" for="from">To</label>
  <div class="col-lg-3">
      <input type="text" class="form-control" id="to_date" name="to_date" placeholder="Select Date" required>
  </div>

  <div class="col-lg-3">
      <select class="form-control" id="department" name="department" >
          <option value="">Select Department</option>
          <?php 
           $fetch_department = mysqli_query($conn, "select * from tbl_department");
           while($row = mysqli_fetch_array($fetch_department)){
          ?>
          <option value="<?php echo $row['department']; ?>"><?php echo $row['department']; ?></option>
      <?php } ?>
       </select>
  </div>
<div class="col-lg-2">
    <button type="submit" name="srh-btn" class="btn btn-primary search-button">Search</button>
</div>
</div>
</form>
<div class="card mb-3">
  <div class="card-header">
    <i class="fa fa-info-circle"></i>
    View Details</div>
    <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
      <thead>
<tr>
  <th>S.No.</th>
  <th>Name</th>
  <th>EmailId</th>
  <th>Mobile</th>
  <th>In Time</th>
  <th>Out Time</th>
  <th>To Meet</th>
  <th>Meeting Status</th>
  <th>Status</th>
  <th>Action</th>
</tr>
</thead>
<tbody>
<?php
if(isset($_REQUEST['srh-btn']))
{
  $from_date = $_POST['from_date'];
  $to_date = $_POST['to_date'];
  $dept = $_POST['department'];
  $from_date = date('Y-m-d', strtotime($from_date));
  $to_date = date('Y-m-d', strtotime($to_date));

  $search_query = mysqli_query($conn, "select * from tbl_visitors where (DATE(in_time)>='$from_date' and DATE(in_time)<='$to_date') or department='$dept'");
  $sn = 1;
  while($row = mysqli_fetch_array($search_query)) {
?>
<tr>
  <td><?php echo $sn; ?></td>
  <td><?php echo $row['name']; ?></td>
  <td><?php echo $row['emailid']; ?></td>
  <td><?php echo $row['mobile']; ?></td>
  <td><?php echo date('d-m-Y h:i A', strtotime($row['in_time'])); ?></td>
  <td><?php echo (!empty($row['out_time'])) ? date('d-m-Y h:i A', strtotime($row['out_time'])) : "<span class='text-muted'>--</span>"; ?></td>
  <td><?php echo $row['to_meet']; ?></td>
  <td>
    <?php 
      if ($row['meeting_status'] == 'approved') {
        echo "<span class='badge badge-success'>Approved</span>";
      } elseif ($row['meeting_status'] == 'pending') {
        echo "<span class='badge badge-warning'>Pending</span>";
      } else {
        echo "<span class='badge badge-danger'>Decline</span>";
      }
    ?>
  </td>
  <td>
    <?php if ($row['status'] == 1) { ?>
      <a href="?toggle_status=<?php echo $row['id']; ?>">
        <span class="badge badge-success">In</span>
      </a>
    <?php } else { ?>
      <span class="badge badge-danger">Out</span>
    <?php } ?>
  </td>
  <td>
    <a href="edit-visitor.php?id=<?php echo $row['id']; ?>">
      <?php echo ($row['status']==1) ? '<i class="fa fa-pencil m-r-5"></i> Edit' : 'Edit'; ?>
    </a>
    <a href="manage-visitors.php?ids=<?php echo $row['id']; ?>" onclick="return confirmDelete()">
      <i class="fa fa-trash-o m-r-5"></i> Delete
    </a>
  </td>
</tr>
<?php $sn++; } 
} else {
  if(isset($_GET['ids'])){
    $id = $_GET['ids'];
    $delete_query = mysqli_query($conn, "delete from tbl_visitors where id='$id'");
  }
  $select_query = mysqli_query($conn, "select * from tbl_visitors");
  $sn = 1;
  while($row = mysqli_fetch_array($select_query)) {
?>
<tr>
  <td><?php echo $sn; ?></td>
  <td><?php echo $row['name']; ?></td>
  <td><?php echo $row['emailid']; ?></td>
  <td><?php echo $row['mobile']; ?></td>
  <td><?php echo date('d-m-Y h:i A', strtotime($row['in_time'])); ?></td>
  <td><?php echo (!empty($row['out_time'])) ? date('d-m-Y h:i A', strtotime($row['out_time'])) : "<span class='text-muted'>--</span>"; ?></td>
  <td><?php echo $row['to_meet']; ?></td>
  <td>
    <?php 
      if ($row['meeting_status'] == 'approved') {
        echo "<span class='badge badge-success'>Approved</span>";
      } elseif ($row['meeting_status'] == 'pending') {
        echo "<span class='badge badge-warning'>Pending</span>";
      } else {
        echo "<span class='badge badge-danger'>Decline</span>";
      }
    ?>
  </td>
  <td>
    <?php if ($row['status'] == 1) { ?>
      <a href="?toggle_status=<?php echo $row['id']; ?>">
        <span class="badge badge-success">In</span>
      </a>
    <?php } else { ?>
      <span class="badge badge-danger">Out</span>
    <?php } ?>
  </td>
  <td>
    <a href="edit-visitor.php?id=<?php echo $row['id']; ?>">
      <?php echo ($row['status']==1) ? '<i class="fa fa-pencil m-r-5"></i> Edit' : 'Edit'; ?>
    </a>
    <a href="manage-visitors.php?ids=<?php echo $row['id']; ?>" onclick="return confirmDelete()">
      <!-- <i class="fa fa-trash-o m-r-5"></i> -->Delete 
    </a>
  </td>
</tr>
<?php $sn++; } } ?>
</tbody>
</table>
</div>
</div>                   
</div>
</div> 
</div>
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>
<?php include('include/footer.php'); ?>
<script language="JavaScript" type="text/javascript">
function confirmDelete(){
    return confirm('Are you sure want to delete this Visitor?');
}
</script>
