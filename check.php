<?php
include 'mailer_config.php';
$mail = getMailerInstance();
$mail->addAddress('vivekyadavpalhana@gmail.com');
$mail->Subject = 'Test Mail';
$mail->Body = 'If you got this, mailing works!';
if ($mail->send()) {
    echo "Mail sent!";
} else {
    echo "Mail failed: " . $mail->ErrorInfo;
}
?>
