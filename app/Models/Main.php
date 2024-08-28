<?php
require_once 'vendor/autoload.php';
use Twilio\Rest\Client;

// Configura tus credenciales de Twilio
$sid = '';
$token = '';
$twilio = new Client($sid, $token);

// Función para enviar el mensaje de WhatsApp
function enviarMensajeWhatsApp($numero, $mensaje) {
    global $twilio;
    $twilio->messages->create(
        "whatsapp:$numero", // Número de destino con el prefijo "whatsapp:"
        [
           "from" => "whatsapp:+XXXXXXXXXXXXXXXX",
            'body' => $mensaje
        ]
    );
}

// Comprobamos si se ha hecho clic en el botón
if (isset($_POST['enviar'])) {
    $numero = '+XXXXXXXXXXXXXXXXX'; // Número de destino
    $mensaje = 'Buenas días enrique, el cliente Carlos ha progmado una cita para el día 20 de agosto 2024, a las 10:00 am. Su número de teléfono es: +525528382133';
    enviarMensajeWhatsApp($numero, $mensaje);
    echo "Mensaje enviado!";
}
?>

<!-- HTML del botón -->
<form method="post">
    <button type="submit" name="enviar">Enviar mensaje por WhatsApp</button>
</form>
