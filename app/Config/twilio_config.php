<?php
require_once '../../vendor/autoload.php';
use Dotenv\Dotenv;
use Twilio\Rest\Client;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Configura tus credenciales de Twilio desde el archivo .env
$sid = getenv('TWILIO_SID');
$token = getenv('TWILIO_TOKEN');
$twilio = new Client($sid, $token);

// Función para enviar el mensaje de WhatsApp
function enviarMensajeWhatsApp($numero, $mensaje) {
    global $twilio;
    $twilio->messages->create(
        "whatsapp:$numero", // Número de destino con el prefijo "whatsapp:"
        [
            "from" => "whatsapp:+14155238886", // Número de WhatsApp habilitado en Twilio
            'body' => $mensaje
        ]
    );
}
?>
