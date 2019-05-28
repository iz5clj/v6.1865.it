<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if (isset($_POST['submit'])) {

    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    $result  = array('country'=>'', 'city'=>'');
    
    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));    
    if($ip_data && $ip_data->geoplugin_countryName != null){
        $result['country'] = $ip_data->geoplugin_countryCode;
        $result['city'] = $ip_data->geoplugin_city;
    }

    $body = $_POST['message'] . "<br>";
    $body .= "<div style='background-color: #FFFFF0; padding: 5px 10px; margin-top: 15px; border-top: 1px solid'>";
    $body .= "<p>" . "Nome: " . $_POST['name'] . "</p>";
    $body .= "<p>" . "Email: " . $_POST['email'] . "</p>";
    $body .= "<p>" . "Tel: " . $_POST['phone'] . "</p>";
    $body .= "<p>" . "IP: " . $ip . "</p>";
    $body .= "<p>" . "Codice paese(dal IP): " . $result['country'] . "</p>";
    $body .= "<p>" . "Città(dal IP): " . $result['city'] . "</p>";
    $body .= "</div>";

    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    
    //Server settings
    //$mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'info@1865.it';                     // SMTP username
    $mail->Password = '1865Epoca@@';                      // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom($_POST['email'], $_POST['name']);
    $mail->addAddress('info@1865.it', '1865 Rersidenza epoca');
    $mail->addReplyTo($_POST['email'], $_POST['name']);
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Nuovo messaggio dal sito web 1865.it';
    $mail->Body    = $body;
    $mail->AltBody = $body;

    if ( !$mail->send()) {
        header("Location: /smtperror");
    } 
    else {
        switch ( $_POST['language'] ){
            case "en":
            header("Location: /thank-you");
            break;
    
            case "it":
            header("Location: /it/grazie");
            break;
        }
    }
    
}
else {
    header("Location: /");
}