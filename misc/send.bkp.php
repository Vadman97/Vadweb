<?php
for ($i = 0; $i < 10; $i++)
{
    $to = $_GET["name"] . '<' . $_GET["name"].'@mittymonarch.com>';
    $subject = 'You are in trouble...! ' . rand();
    $message = 'You just got sp00ked!';
    //$message = wordwrap($message, 70, "\r\n");

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: Sir Isaac Nooton  <vadwebnoreply@gmail.com>' . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
    echo $to . "<br>";
    echo $headers . "<br>";
    echo $subject . "<br>";
    echo "Sent!<br><br>";
    
    //echo "GET REKT THIS DOESNT WORK ANYMORE L3L"
}
?>
