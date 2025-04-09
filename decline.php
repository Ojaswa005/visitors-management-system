<?php
include('connection.php');
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($conn, "UPDATE tbl_visitors SET meeting_status = 'declined' WHERE id = $id");
    echo "<script>alert('You have declined the meeting.'); window.close();</script>";
}
?>