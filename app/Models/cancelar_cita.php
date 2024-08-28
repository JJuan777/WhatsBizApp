<?php
session_start();
if (!isset($_SESSION['idUser'])) {
    header('Location: ../index.html');
    exit;
}

require_once '../Config/conn.php';
require_once '../Config/twilio_config.php';

$nombre = $_SESSION['nombre'];
$telefono = $_SESSION['Numero'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCita'])) {
    $idCita = $_POST['idCita'];

    // Obtén los detalles de la cita
    $stmt = $conn->prepare("SELECT DiaMesAnio, Hora FROM TCitas WHERE IdCitas = ?");
    $stmt->bind_param("i", $idCita);
    $stmt->execute();
    $stmt->bind_result($diaMesAnio, $hora);
    $stmt->fetch();
    $stmt->close();

    // Elimina la cita
    $stmt = $conn->prepare("DELETE FROM TCitas WHERE IdCitas = ?");
    $stmt->bind_param("i", $idCita);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Cita cancelada con éxito.";

        // Envía el mensaje por WhatsApp
        $mensaje = "Buen día, el cliente $nombre ha cancelado su cita del día $diaMesAnio a las $hora, su contacto es $telefono.";
        enviarMensajeWhatsApp('+XXXXXXXXXXXXX', $mensaje);
    } else {
        $_SESSION['mensaje'] = "Error al cancelar la cita. Por favor, inténtalo de nuevo.";
    }

    $stmt->close();
}

header('Location: ../Views/appointment.php');
exit;
?>
