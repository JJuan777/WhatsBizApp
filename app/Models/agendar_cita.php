<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUser'])) {
    // Redirigir al login si no ha iniciado sesión
    header('Location: index.html');
    exit;
}

// Conectar a la base de datos
require_once '../Config/conn.php';
require_once '../../vendor/autoload.php';
use Twilio\Rest\Client;

// Configura tus credenciales de Twilio
$sid = '';
$token = '';
$twilio = new Client($sid, $token);

// Obtener el ID del usuario actual
$idUser = $_SESSION['idUser'];
$telefono = $_SESSION['Numero'];

// Obtener el nombre del usuario (ajusta esta consulta según tu base de datos)
$sqlNombre = "SELECT nombre FROM TUsuarios WHERE IdUser = ?";
$stmtNombre = $conn->prepare($sqlNombre);
$stmtNombre->bind_param("i", $idUser);
$stmtNombre->execute();
$stmtNombre->bind_result($nombre);
$stmtNombre->fetch();
$stmtNombre->close();

// Manejar la solicitud de agendar cita
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $diaMesAnio = $_POST['diaMesAnio'];
    $hora = $_POST['hora'];

    // Insertar la cita en la base de datos
    $sql = "INSERT INTO TCitas (IdUser, DiaMesAnio, Hora) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $idUser, $diaMesAnio, $hora);

    if ($stmt->execute()) {
       
        // Función para enviar el mensaje de WhatsApp
        function enviarMensajeWhatsApp($numero, $mensaje) {
            global $twilio;
            $twilio->messages->create(
                "whatsapp:$numero", // Número de destino con el prefijo "whatsapp:"
                [
                    "from" => "whatsapp:+XXXXXXXXXXXX",
                    'body' => $mensaje
                ]
            );
        }

        // Preparar y enviar el mensaje de WhatsApp
        $numeroDestino = $telefono; // Número de destino
        $mensaje = "Buenos días, el cliente $nombre ha programado una cita para el día $diaMesAnio, a las $hora. Su número de teléfono es: $telefono";
        enviarMensajeWhatsApp($numeroDestino, $mensaje);

         // Mensaje de éxito
         $_SESSION['mensaje'] = "Cita agendada correctamente.";
        
        
    } else {
        // Mensaje de error
        $_SESSION['mensaje'] = "Error al agendar la cita.";
    }

    // Redirigir a la página principal
    header("Location: ../Views/index.php");
    exit;
}
?>
