<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$result = [];

if (!empty($_POST['nombre']) && !empty($_POST['telefono']) && !empty($_POST['email']) && !empty($_POST['mensaje'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $mensaje = $_POST['mensaje'];
    
    

    // Validación del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = [
            'success' => false,
            'message' => 'La dirección de correo electrónico no es válida.'
        ];
        echo json_encode($result);
        exit;
    }

    // Escapar las direcciones de correo electrónico
    $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $telefono = htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8');
    $mensaje = htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8');
    
    

    require_once("PHPMailer/PHPMailerAutoload.php");
    require_once("PHPMailer/PHPMailer.php");
    require_once("PHPMailer/SMTP.php");

    $body = "
        <h3>Consulta desde la Web wowmultimedia.com.ar</h3>
        <hr />
        <p>Estos son los datos de la consulta:</p>
        <p>Nombre: $nombre</p>
        <p>Correo: $email</p>
        <p>Telefono: $telefono</p>
        <p>Mensaje: $mensaje</p>    
        
    ";

    $body = str_replace("{{nombre}}", $nombre, $body);
    $body = str_replace("{{telefono}}", $telefono, $body);
    $body = str_replace("{{correo}}", $email, $body);
    $body = str_replace("{{mensaje}}", $mensaje, $body);
   
    

    $mailer = new PHPMailer(true); // 'true' enables exceptions

    try {
        $mailer->isSMTP();
        $mailer->SMTPSecure = "ssl";
        $mailer->Host = 'mail.rdccollections.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'contacto@rdccollections.com';
        $mailer->Password = 'Rdc2022*';
        $mailer->Port = 465;
        $mailer->From = 'contacto@rdccollections.com';
        $mailer->FromName = "rdccollections";
        $mailer->isHTML(true);
        $mailer->Body = $body;
        $mailer->Subject = "Consulta desde la Web";
        $mailer->addAddress('contacto@rdccollections.com');
        $mailer->addReplyTo("$email");
        $mailer->CharSet = 'UTF-8';

        $mailer->smtpConnect(
            array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true
                )
            )
        );

        $mailer->send();

        $result = [
            'success' => true,
            'message' => 'Mensaje enviado correctamente.'
        ];
    } catch (Exception $e) {
        $result = [
            'success' => false,
            'message' => 'Error al enviar el mensaje. Inténtalo de nuevo más tarde. Detalles: ' . $e->getMessage()
        ];
    }
} else {
    $result = [
        'success' => false,
        'message' => 'Por favor, completa todos los campos del formulario.'
    ];
}

header('Content-Type: application/json');
echo json_encode($result);