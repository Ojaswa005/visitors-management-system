<?php
// Include PHPMailer classes
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create and configure PHPMailer
function getMailerInstance() {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';       // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'vivekyadavpalhana@gmail.com'; // Your email
        $mail->Password   = 'mkad tnwn gxir gbzc';    // App Password (not your real one)
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // From email
        $mail->setFrom('vivekyadavpalhana@gmail.com', 'Visitor Management System');
    } catch (Exception $e) {
        echo "Mailer Error: " . $e->getMessage();
    }

    return $mail;
}
