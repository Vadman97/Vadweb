<?php
for ($i = 0; $i < 10; $i++)
{
    $to      = 'Qimeng Xiao' . '<qimengxiao16@mittymonarch.com>';
    $subject = 'You are in trouble...! ' . rand();
    $message = 'You just got sp00ked!';
    //$message = wordwrap($message, 70, "\r\n");

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: Jimmy the Swagmaster  <vadwebnoreply@gmail.com>' . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
    echo $headers . "<br>";
    echo $subject . "<br>";
    echo "Sent!";
}
?>
