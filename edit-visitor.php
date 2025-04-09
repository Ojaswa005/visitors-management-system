<?php
session_start();
include('connection.php');
include('mailer_config.php');

$name = $_SESSION['name'];
$id = $_SESSION['id'];
if (empty($id)) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage-visitors.php");
    exit();
}

$visitor_id = $_GET['id'];

// Fetch current visitor data
$visitor_query = mysqli_query($conn, "SELECT * FROM tbl_visitors WHERE id = '$visitor_id'");
$visitor = mysqli_fetch_assoc($visitor_query);

if (!$visitor) {
    echo "Visitor not found.";
    exit();
}

if (isset($_POST['update-visitor'])) {
    $fullname = $_POST['fullname'];
    $emailid = $_POST['emailid'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $meet = $_POST['meet'];
    $department = $_POST['department'];
    $reason = $_POST['reason'];

    $photo = $visitor['photo'];
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo = $target_file;
        }
    }

    $update_query = "UPDATE tbl_visitors SET name='$fullname', emailid='$emailid', mobile='$mobile', address='$address', to_meet='$meet', department='$department', reason='$reason', photo='$photo' WHERE id='$visitor_id'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        $changes = [];
        foreach (['name', 'emailid', 'mobile', 'address', 'to_meet', 'department', 'reason'] as $field) {
            if ($_POST[$field] !== $visitor[$field]) {
                $changes[] = $field;
            }
        }

        if (count($changes) > 0) {
            $dept_result = mysqli_query($conn, "SELECT email FROM tbl_department WHERE department = '$department' LIMIT 1");
            if ($dept_result && mysqli_num_rows($dept_result) > 0) {
                $dept_row = mysqli_fetch_assoc($dept_result);
                $dept_email = $dept_row['email'];

                if (!empty($dept_email)) {
                    $mail = getMailerInstance();
                    $mail->addAddress($dept_email);
                    $mail->Subject = 'Visitor Information Updated';
                    $mail->Body = "Visitor details updated:\nName: $fullname\nReason: $reason\nUpdated Fields: " . implode(', ', $changes);

                    if (!empty($photo)) {
                        $mail->addAttachment($photo);
                    }

                    try {
                        $mail->send();
                    } catch (Exception $e) {
                        error_log("Mailer Error: " . $mail->ErrorInfo);
                    }
                }
            }
        }
        echo "<script>alert('Visitor updated successfully.'); window.location.href='manage-visitors.php';</script>";
    }
}
?>
<?php include('include/header.php'); ?>
<div id="wrapper">
<?php include('include/side-bar.php'); ?>
<div id="content-wrapper">
  <div class="container-fluid">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">Edit Visitor</li>
    </ol>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-edit"></i> Edit Visitor
      </div>
      <form method="post" enctype="multipart/form-data">
        <div class="card-body">
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="fullname" class="form-control" value="<?= $visitor['name'] ?>" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="emailid" class="form-control" value="<?= $visitor['emailid'] ?>" required>
          </div>
          <div class="form-group">
            <label>Mobile</label>
            <input type="text" name="mobile" class="form-control" value="<?= $visitor['mobile'] ?>" required>
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control" required><?= $visitor['address'] ?></textarea>
          </div>
          <div class="form-group">
            <label>Whom to Meet</label>
            <input type="text" name="meet" class="form-control" value="<?= $visitor['to_meet'] ?>" required>
          </div>
          <div class="form-group">
            <label>Department</label>
            <select name="department" class="form-control" required>
              <option value="">Select Department</option>
              <?php
              $dept_query = mysqli_query($conn, "SELECT department FROM tbl_department WHERE status = 1");
              while ($dept = mysqli_fetch_assoc($dept_query)) {
                $selected = $dept['department'] == $visitor['department'] ? 'selected' : '';
                echo "<option value=\"{$dept['department']}\" $selected>{$dept['department']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label>Reason</label>
            <input type="text" name="reason" class="form-control" value="<?= $visitor['reason'] ?>" required>
          </div>
          <div class="form-group">
            <label>Upload Photo (optional)</label>
            <input type="file" name="photo" class="form-control">
            <?php if (!empty($visitor['photo'])): ?>
              <img src="<?= $visitor['photo'] ?>" width="100" class="mt-2" />
            <?php endif; ?>
          </div>
          <button type="submit" name="update-visitor" class="btn btn-primary">Update Visitor</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include('include/footer.php'); ?>
