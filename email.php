<html>
<body>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    E-mail: <input type="text" name="email"><br>
<input type="submit">
</form>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = $_POST['email'];
    echo "Email is " . $email . "\n";

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try
    {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'weatherstationboryssmejda@gmail.com';                     // SMTP username
        $mail->Password   = 'U5q7itU5FE*u';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        $mail->setFrom('weatherstationboryssmejda@gmail.com', 'Smart Weather Station');
        $mail->addAddress('borys.smejda@gmail.com', 'Borys Smejda');     // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Smart Weather Station';
        $mail->Body    = 'Hello Mr.<b>Borys Smejda</b><br>. Rain was detected by the station located in the Garden!';
        //$mail->AltBody = 'Nice that you subscribed to our newsletter!';

        $mail->send();
        echo 'Message has been sent';
    }
    catch (Exception $e)
    {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    echo "All ok\n";
}

?>


</body>
</html>