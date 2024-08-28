<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUser'])) {
    // Redirigir al login si no ha iniciado sesión
    header('Location: index.html');
    exit;
}

// Obtener el nombre del usuario
$nombre = $_SESSION['nombre'];
$numero = $_SESSION['Numero'];
// Verificar si hay un mensaje de resultado de agendar cita
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';

// Limpiar el mensaje de la sesión después de obtenerlo
if (!empty($mensaje)) {
    unset($_SESSION['mensaje']);
}

// Obtener la fecha actual en formato YYYY-MM-DD
$fechaHoy = date('Y-m-d');

// Incluir configuración de la base de datos
require_once '../Config/conn.php';

// Inicializar variable para horas ocupadas
$horasOcupadas = [];

// Verificar si se ha seleccionado una fecha
$fechaSeleccionada = isset($_POST['diaMesAnio']) ? $_POST['diaMesAnio'] : $fechaHoy;

if ($fechaSeleccionada) {
    // Preparar la consulta para obtener horas ocupadas
    $stmt = $conn->prepare("SELECT Hora FROM TCitas WHERE DiaMesAnio = ?");
    $stmt->bind_param("s", $fechaSeleccionada);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $horasOcupadas[] = $row['Hora'];
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio/Obsidian Barber</title>
    <!--  CSS -->
    <link rel="stylesheet" href="../../public/css/styleIndex.css">
    <!-- Icon -->
    <link rel="icon" href="../../public/images/icon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../../public/css/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="index.php">
        <img src="../../public/images/icon.ico" alt="Obsidian Barber Icon" style="width: 30px; height: 30px; margin-right: 10px;">
        Obsidian Barber Shop
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="myprofile.php"><i class="fas fa-user"></i> Perfil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="appointment.php"><i class="fas fa-calendar-check"></i> Mis citas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../Models/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Contenedor central -->
<div class="container">
    <div class="welcome-container">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre); ?>!</h1>
        <p>Por favor, seleccione una fecha y hora disponibles para agendar su cita. Una vez que haya reservado su cita, podrá verificar todas las citas agendadas en el apartado "Mis citas". Si necesita modificar o cancelar una cita, podrá hacerlo desde esa sección también.</p>

        <!-- Formulario para seleccionar la fecha -->
        <h2>Consultar Disponibilidad</h2>
        <form id="consulta-form">
            <div class="form-group">
                <label for="fecha">Seleccione una Fecha:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" min="<?php echo $fechaHoy; ?>" required>
            </div>
            <button type="button" class="btn btn-warning" id="consultar">Consultar Disponibilidad</button>
        </form>
    </div>
</div>

<!-- Modal para selección de hora y agendar cita -->
<div class="modal fade" id="modalHorario" tabindex="-1" aria-labelledby="modalHorarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHorarioLabel">Seleccione la Hora</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../Models/agendar_cita.php">
                    <input type="hidden" id="fechaModal" name="diaMesAnio">
                    <div class="form-group">
                        <label for="horaModal">Hora:</label>
                        <select class="form-control" id="horaModal" name="hora" required>
                            <option value="" disabled selected>-- Seleccione una hora --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning">Agendar Cita</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast para mostrar el mensaje -->
<?php if (!empty($mensaje)) { ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Bootstrap JS y Popper.js -->
<script src="../../public/css/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toastEl = document.getElementById('liveToast');
        if (toastEl) {
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    });

    document.getElementById('consultar').addEventListener('click', function () {
        var fecha = document.getElementById('fecha').value;
        if (fecha) {
            fetch('../Models/consultar_disponibilidad.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams('fecha=' + encodeURIComponent(fecha))
            })
            .then(response => response.json())
            .then(data => {
                // Llenar el select de horas en el modal
                var horaSelect = document.getElementById('horaModal');
                horaSelect.innerHTML = '<option value="" disabled selected>-- Seleccione una hora --</option>';
                for (var h = 9; h <= 21; h++) {
                    var formattedHour = ('0' + h).slice(-2) + ':00';
                    if (!data.horasOcupadas.includes(formattedHour)) {
                        var option = document.createElement('option');
                        option.value = formattedHour;
                        option.text = formattedHour;
                        horaSelect.appendChild(option);
                    }
                }
                // Establecer la fecha en el modal
                document.getElementById('fechaModal').value = fecha;
                // Mostrar el modal
                var modalHorario = new bootstrap.Modal(document.getElementById('modalHorario'));
                modalHorario.show();
            });
        }
    });
</script>
</body>
</html>
