<?php
session_start();
include ('connection.php');
$name = $_SESSION['name'];
$id = $_SESSION['id'];
if (empty($id)) {
    header("Location: index.php"); 
}

$id = $_GET['id'];
$fetch_query = mysqli_query($conn, "SELECT * FROM tbl_department WHERE id='$id'");
$row = mysqli_fetch_array($fetch_query);

if (isset($_POST['sv-dpt'])) {
    $department_name = $_POST['deptname'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    $update_department = mysqli_query($conn, "UPDATE tbl_department SET department='$department_name', email='$email', status='$status' WHERE id='$id'");

    if ($update_department > 0) {
        echo "<script>alert('Department Updated successfully.'); window.location.href='view-department.php';</script>";
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
            <li class="breadcrumb-item"><a href="#">Edit Department</a></li>
        </ol>

        <div class="card mb-3">
            <div class="card-header"><i class="fa fa-info-circle"></i> Edit Details</div>
            <form method="post" class="form-valide">
                <div class="card-body">

                    <!-- Department Name -->
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Department Name <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" name="deptname" class="form-control" placeholder="Enter Department Name" required value="<?php echo $row['department']; ?>">
                        </div>
                    </div>

                    <!-- Department Email -->
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Department Email <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="email" name="email" class="form-control" placeholder="Enter Department Email" required value="<?php echo $row['email']; ?>">
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group row">
                        <label class="col-lg-4 col-form-label">Status <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <select class="form-control" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1" <?php if ($row['status'] == 1) echo 'selected'; ?>>Active</option>
                                <option value="0" <?php if ($row['status'] == 0) echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="form-group row">
                        <div class="col-lg-8 ml-auto">
                            <button type="submit" name="sv-dpt" class="btn btn-primary">Save</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>                  
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
<?php include('include/footer.php'); ?>
