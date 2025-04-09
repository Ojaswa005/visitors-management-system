<?php
include('connection.php');

if(isset($_POST['department'])) {
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $result = mysqli_query($conn, "SELECT name FROM tbl_teachers WHERE department = '$department' AND status = 1");

    echo '<option value="">-- Select Teacher --</option>';
    while($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
    }
}
?>
