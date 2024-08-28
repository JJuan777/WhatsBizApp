<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUser'])) {
    // Redirigir al login si no ha iniciado sesión
    header('Location: index.html');
    exit;
}

// Cargar variables de entorno
require_once '../../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); 
$dotenv->load();

// Conectar a la base de datos
require_once '../Config/conn.php';
use Twilio\Rest\Client;

// Configura tus credenciales de Twilio desde el archivo .env
$sid = getenv('TWILIO_SID');
$token = getenv('TWILIO_TOKEN');
$twilio = new Client($sid, $token);

$IdCitas = $_POST['IdCitas'];

// Obtener el ID del usuario asociado a la cita
$sqlIdUser = "SELECT IdUser FROM TCitas WHERE IdCitas = ?";
$stmtIdUser = $conn->prepare($sqlIdUser);
$stmtIdUser->bind_param("i", $IdCitas); // Cambiado a $IdCitas
$stmtIdUser->execute();
$stmtIdUser->bind_result($idUser);
$stmtIdUser->fetch();
$stmtIdUser->close();

// Obtener el número de teléfono del usuario
$sqlTelefono = "SELECT Numero FROM TUsuarios WHERE IdUser = ?";
$stmtTelefono = $conn->prepare($sqlTelefono);
$stmtTelefono->bind_param("i", $idUser);
$stmtTelefono->execute();
$stmtTelefono->bind_result($telefono);
$stmtTelefono->fetch();
$stmtTelefono->close();

// Eliminar la cita
$sqlEliminar = "DELETE FROM TCitas WHERE IdCitas = ?";
$stmtEliminar = $conn->prepare($sqlEliminar);
$stmtEliminar->bind_param("i", $IdCitas);
if ($stmtEliminar->execute()) {
    // Función para enviar el mensaje de WhatsApp
    function enviarMensajeWhatsApp($numero, $mensaje) {
        global $twilio; // Acceder a la instancia global de Twilio
        $twilio->messages->create(
            "whatsapp:$numero", // Número de destino con el prefijo "whatsapp:"
            [
                "from" => "whatsapp:+XXXXXXXXXXXXXXX", // Reemplaza con tu número de WhatsApp habilitado en Twilio
                'body' => $mensaje
            ]
        );
    }

    // Preparar y enviar el mensaje de WhatsApp
    $mensaje = 'Enrique ha cancelado tu cita. Para agendar una nueva cita, puedes ver la disponibilidad dando clic http://localhost/ApiWhatts/app/Views/index.php o enviar un mensaje a él al: +5215528383122.';
    enviarMensajeWhatsApp($telefono, $mensaje);

    // Mensaje de éxito
    $_SESSION['mensaje'] = "Cita eliminada correctamente.";
} else {
    // Mensaje de error
    $_SESSION['mensaje'] = "Error al eliminar la cita.";
}

// Redirigir a la página principal
header("Location: ../Views/indexadmin.php");
exit;
?>
