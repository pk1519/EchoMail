<?php
function send_verification_email($email, $code) {
    $encodedEmail = urlencode(base64_encode($email));
    $link = "http://localhost:8000/verify.php?email=$encodedEmail&code=$code";

    $subject = "Verify your subscription";
    $message = "
        <html>
        <body>
            <p>Click the link below to verify your subscription:</p>
            <a href='$link'>Verify Email</a>
        </body>
        </html>
    ";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: verify@task-scheduler.com\r\n";

    return mail($email, $subject, $message, $headers);
}
?>
