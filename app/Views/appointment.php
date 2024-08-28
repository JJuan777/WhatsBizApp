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
  <!-- Contenedor de citas -->
  <div class="container">
        <div class="welcome-container">
            <h1>Mis Citas</h1>
            <p>Aquí puedes ver las citas que has agendado. Las citas pasadas no se mostrarán.</p>

            <!-- Tabla de citas -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    session_start();
                    if (!isset($_SESSION['idUser'])) {
                        header('Location: index.html');
                        exit;
                    }
                    
                    $idUser = $_SESSION['idUser'];
                    $fechaHoy = date('Y-m-d');

                    require_once '../Config/conn.php';

                    $stmt = $conn->prepare("SELECT IdCitas, DiaMesAnio, Hora FROM TCitas WHERE IdUser = ? AND DiaMesAnio >= ? ORDER BY DiaMesAnio, Hora");
                    $stmt->bind_param("ss", $idUser, $fechaHoy);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['DiaMesAnio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Hora']) . "</td>";
                        echo "<td>Confirmada</td>";
                        echo "<td>";
                        echo "<form method='POST' action='../Models/cancelar_cita.php' onsubmit='return confirm(\"¿Estás seguro de que deseas cancelar esta cita?\");'>";
                        echo "<input type='hidden' name='idCita' value='" . htmlspecialchars($row['IdCitas']) . "'>";
                        echo "<button type='submit' class='btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> Cancelar</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }

                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
<!-- Toast para mostrar el mensaje -->
<?php if (!empty($_SESSION['mensaje'])) { ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
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
