<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUser'])) {
    // Redirigir al login si no ha iniciado sesión
    header('Location: index.html');
    exit;
}

// Incluir configuración de la base de datos
require_once '../Config/conn.php';

// Obtener las citas utilizando una sentencia preparada
$query = "SELECT TCitas.IdCitas, TUsuarios.Nombre, TCitas.DiaMesAnio, TCitas.Hora 
          FROM TCitas 
          INNER JOIN TUsuarios ON TCitas.IdUser = TUsuarios.IdUser";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si hay un mensaje de resultado de alguna acción
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';

// Limpiar el mensaje de la sesión después de obtenerlo
if (!empty($mensaje)) {
    unset($_SESSION['mensaje']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin/Obsidian Barber</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../../public/css/styleIndex.css">
    <!-- Icon -->
    <link rel="icon" href="../../public/images/icon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="../../public/css/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../../public/images/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }
        .table {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="indexadmin.php">
        <img src="../../public/images/icon.ico" alt="Obsidian Barber Icon" style="width: 30px; height: 30px; margin-right: 10px;">
        Obsidian Barber Shop Admin
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="indexadmin.php"><i class="fas fa-home"></i> Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../Models/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Contenedor central -->
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-dark text-white">
            <h3 class="mb-0">Panel de Citas</h3>
        </div>
        <div class="card-body">
            <!-- Tabla de citas -->
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>ID Cita</th>
                        <th>Nombre del Usuario</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['IdCitas']; ?></td>
                                <td><?php echo htmlspecialchars($row['Nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['DiaMesAnio']); ?></td>
                                <td><?php echo htmlspecialchars($row['Hora']); ?></td>
                                <td>
                                    <form method="POST" action="../Models/eliminar_cita.php" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta cita?');">
                                        <input type="hidden" name="IdCitas" value="<?php echo $row['IdCitas']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay citas agendadas.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
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
</script>
</body>
</html>
