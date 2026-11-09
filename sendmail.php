<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);
    try {
        $mail->setFrom($email, $name);
        $mail->addAddress('vyshalina@gmail.com');
        $mail->Subject = 'New message from My site: ' . $name;
        $mail->Body    = "Name: $name\nE-mail: $email\nMessage: $message";

        if(isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($file['name']);

            if(move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $mail->addAttachment($uploadFile);
            }
        }

        $mail->send();
        echo json_encode(['status' => true, 'message' => 'Your message has been successfully delivered, await a response from our team.']);
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'message' => 'An error occurred during the sending process: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Incorrect request method']);
}
?>
