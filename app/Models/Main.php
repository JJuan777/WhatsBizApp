<?php
require_once 'vendor/autoload.php';
use Dotenv\Dotenv;
use Twilio\Rest\Client;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
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
            "from" => "whatsapp:+XXXXXXXXXXXXXXXX", // Reemplaza con tu número de WhatsApp habilitado en Twilio
            'body' => $mensaje
        ]
    );
}

// Comprobamos si se ha hecho clic en el botón
if (isset($_POST['enviar'])) {
    $numero = '+XXXXXXXXXXXXXXXXX'; // Número de destino
    $mensaje = 'Buenos días Enrique, el cliente Carlos ha programado una cita para el día 20 de agosto de 2024, a las 10:00 am. Su número de teléfono es: +525528382133';
    enviarMensajeWhatsApp($numero, $mensaje);
    echo "Mensaje enviado!";
}
?>

<!-- HTML del botón -->
<form method="post">
    <button type="submit" name="enviar">Enviar mensaje por WhatsApp</button>
</form>
