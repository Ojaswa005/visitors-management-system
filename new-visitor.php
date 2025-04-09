<?php
session_start();
include('connection.php');
include('mailer_config.php');

$name = $_SESSION['name'];
$id = $_SESSION['id'];
if(empty($id)) {
    header("Location: index.php"); 
}

if(isset($_POST['add-visitor'])) {
  $fullname = $_POST['fullname'];
  $emailid = $_POST['emailid'];
  $mobile = $_POST['mobile'];
  $address = $_POST['address'];
  $meet = $_POST['meet'];
  $department = $_POST['department'];
  $reason = $_POST['reason'];
  $photo = '';

  if (!empty($_FILES['photo']['name'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $photo = $target_file;
    }
  }

  $insert_query = "INSERT INTO tbl_visitors (name, emailid, mobile, address, to_meet, department, reason, photo, in_time, status) 
  VALUES ('$fullname', '$emailid', '$mobile', '$address', '$meet', '$department', '$reason', '$photo', now(), 1)";

$insert_visitor = mysqli_query($conn, $insert_query);

if($insert_visitor) {
  $visitor_id = mysqli_insert_id($conn); // STEP 2: Get visitor ID

  // Fetch department email
  $dept_result = mysqli_query($conn, "SELECT email FROM tbl_department WHERE department = '$department' LIMIT 1");
  $dept_email = '';
  if ($dept_result && mysqli_num_rows($dept_result) > 0) {
    $dept_row = mysqli_fetch_assoc($dept_result);
    $dept_email = $dept_row['email'];
  }

  // Fetch teacher email
  $teacher_result = mysqli_query($conn, "SELECT email FROM tbl_teachers WHERE name = '$meet' LIMIT 1");
  $teacher_email = '';
  if ($teacher_result && mysqli_num_rows($teacher_result) > 0) {
    $teacher_row = mysqli_fetch_assoc($teacher_result);
    $teacher_email = $teacher_row['email'];
  }

  // Generate approval links
  $approve_link = "http://localhost/visitors-management-system/visitors-management-system/approve.php?id=$visitor_id";
  $decline_link = "http://localhost/visitors-management-system/visitors-management-system/decline.php?id=$visitor_id";

  // Send mail to both
  $mail = getMailerInstance();
  // if (!empty($dept_email)) {
  //   $mail->addAddress($dept_email);
  // }
  if (!empty($teacher_email)) {
    $mail->addAddress($teacher_email);
  }

  $mail->Subject = 'New Visitor Added';
  $mail->Body = "A new visitor has been added:
Name: $fullname
Email: $emailid
Mobile: $mobile
Reason: $reason

Faculty Approval Required:
✅ Approve: $approve_link
❌ Decline: $decline_link";

  if (!empty($photo)) {
    $mail->addAttachment($photo);
  }

  try {
    $mail->send();
  } catch (Exception $e) {
    error_log("Mailer Error: " . $mail->ErrorInfo);
  }


//   if($insert_visitor) {
//    // Fetch department email
// $dept_result = mysqli_query($conn, "SELECT email FROM tbl_department WHERE department = '$department' LIMIT 1");
// $dept_email = '';
// if ($dept_result && mysqli_num_rows($dept_result) > 0) {
//   $dept_row = mysqli_fetch_assoc($dept_result);
//   $dept_email = $dept_row['email'];
// }

// Fetch teacher email
// $teacher_result = mysqli_query($conn, "SELECT email FROM tbl_teachers WHERE name = '$meet' LIMIT 1");
// $teacher_email = '';
// if ($teacher_result && mysqli_num_rows($teacher_result) > 0) {
//   $teacher_row = mysqli_fetch_assoc($teacher_result);
//   $teacher_email = $teacher_row['email'];
// }

// Send mail to both (if emails exist)
$mail = getMailerInstance();
if (!empty($dept_email)) {
  $mail->addAddress($dept_email);
}

$mail->Subject = 'New Visitor Added';
$mail->Body = "A new visitor has been added:\nName: $fullname\nReason: $reason\nTo Meet: $meet";

if (!empty($photo)) {
  $mail->addAttachment($photo);
}

try {
  $mail->send();
} catch (Exception $e) {
  error_log("Mailer Error: " . $mail->ErrorInfo);
}

    ?>
<script type="text/javascript">
    alert("Visitor added successfully.");
    window.location.href='manage-visitors.php';
</script>
<?php
  }
}
?>
<?php include('include/header.php'); ?>
<div id="wrapper">
<?php include('include/side-bar.php'); ?>
<div id="content-wrapper">
  <div class="container-fluid">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">Add Visitor</li>
    </ol>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-plus"></i> Add New Visitor
      </div>
      <form method="post" enctype="multipart/form-data">
        <div class="card-body">
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="fullname" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="emailid" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Mobile</label>
            <input type="text" name="mobile" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control" required></textarea>
          </div>
          <div class="form-group">
            <label>Department</label>
            <select name="department" class="form-control" required>
              <option value="">-- Select Department --</option>
              <?php
                $departments = mysqli_query($conn, "SELECT department FROM tbl_department WHERE status = 1");
                while ($row = mysqli_fetch_assoc($departments)) {
                    echo "<option value=\"{$row['department']}\">{$row['department']}</option>";
                }
              ?>
            </select>
          </div>
          <div class="form-group">
  <label>Whom to Meet</label>
  <select name="meet" id="meet" class="form-control" required>
    <option value="">-- Select Teacher --</option>
  </select>
</div>
          <div class="form-group">
            <label>Reason</label>
            <input type="text" name="reason" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Upload Photo (optional)</label>
            <input type="file" name="photo" class="form-control">
          </div>
          <button type="submit" name="add-visitor" class="btn btn-primary">Add Visitor</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('select[name="department"]').on('change', function() {
        var department = $(this).val();
        if(department) {
            $.ajax({
                type: 'POST',
                url: 'get_teachers.php',
                data: {department: department},
                success: function(html) {
                    $('#meet').html(html);
                }
            });
        } else {
            $('#meet').html('<option value="">-- Select Teacher --</option>');
        }
    });
});
</script>

<?php include('include/footer.php'); ?>
