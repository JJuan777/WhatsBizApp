<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUser'])) {
    header('Location: index.html');
    exit;
}

// Obtener el ID del usuario
$idUser = $_SESSION['idUser'];

// Conectar a la base de datos
require_once '../Config/conn.php';

// Obtener la información del usuario
$stmt = $conn->prepare("SELECT Nombre, Correo, Numero FROM TUsuarios WHERE IdUser = ?");
$stmt->bind_param("i", $idUser);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $nombre = $row['Nombre'];
    $correo = $row['Correo'];
    $numero = $row['Numero'];
} else {
    // En caso de que no se encuentre el usuario, redirigir o mostrar un error
    header('Location: index.html');
    exit;
}

$stmt->close();
$conn->close();
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
                <a class="nav-link" href="myprofile.html"><i class="fas fa-user"></i> Perfil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="appointment.html"><i class="fas fa-calendar-check"></i> Mis citas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../Models/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </li>
        </ul>
    </div>
</nav>


    <!-- Contenedor del perfil -->
    <div class="container">
        <div class="welcome-container">
            <h1>Mi Perfil</h1>
            <p>Aquí puedes actualizar tu información personal.</p>

            <!-- Formulario para actualizar perfil -->
            <form method="POST" action="../Models/update_profile.php">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>" required>
                </div>
                <div class="form-group">
                    <label for="numero">Número:</label>
                    <input type="text" class="form-control" id="numero" name="numero" value="<?php echo htmlspecialchars($numero); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Nueva Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-warning">Actualizar Información</button>
            </form>
        </div>
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
