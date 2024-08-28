<?php
require_once '../../vendor/autoload.php';
use Twilio\Rest\Client;

// Configura tus credenciales de Twilio
$sid = 'AC671de183e66f662666c75b939151eb7a';
$token = '29c290104a5fe6d3b1f2e5f4b1f42387';
$twilio = new Client($sid, $token);

// Función para enviar el mensaje de WhatsApp
function enviarMensajeWhatsApp($numero, $mensaje) {
    global $twilio;
    $twilio->messages->create(
        "whatsapp:$numero", // Número de destino con el prefijo "whatsapp:"
        [
           "from" => "whatsapp:+14155238886",
            'body' => $mensaje
        ]
    );
}
?>
