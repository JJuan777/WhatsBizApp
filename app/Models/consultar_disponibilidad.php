<?php
require_once '../Config/conn.php';

if (isset($_POST['fecha'])) {
    $fechaSeleccionada = $_POST['fecha'];

    $stmt = $conn->prepare("SELECT Hora FROM TCitas WHERE DiaMesAnio = ?");
    $stmt->bind_param("s", $fechaSeleccionada);
    $stmt->execute();
    $result = $stmt->get_result();

    $horasOcupadas = [];
    while ($row = $result->fetch_assoc()) {
        $horasOcupadas[] = $row['Hora'];
    }

    $stmt->close();

    echo json_encode(['horasOcupadas' => $horasOcupadas]);
}
?>
