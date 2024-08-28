<?php
require_once '../Config/conn.php';

if (isset($_POST['correo']) && isset($_POST['PasswordU'])) {
    $correo = $_POST['correo'];
    $PasswordU = $_POST['PasswordU'];

    // Preparar la consulta para evitar inyección SQL
    $query = "SELECT * FROM TUsuarios WHERE Correo = ?";
    $stmt = $conn->prepare($query);
    
    // Verificar si la consulta fue preparada correctamente
    if ($stmt) {
        // Enlazar parámetros
        $stmt->bind_param("s", $correo);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Obtener los datos del usuario
            $row = $result->fetch_assoc();
            $hashed_password = $row['PasswordU'];
            $idUser = $row['IdUser'];
            $nombre = $row['Nombre'];
            $numero = $row['Numero'];

            // Verificar la contraseña hasheada
            if (password_verify($PasswordU, $hashed_password)) {
                // Iniciar sesión
                session_start();
                $_SESSION['idUser'] = $idUser;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['Numero'] = $numero;

                header('Location: ../Views/index.php');
                exit;
            } else {
                header('Location: ../Views/index.html?error=invalid');
                exit;
            }
        } else {
            header('Location: ../Views/index.html?error=invalid');
            exit;
        }

        // Cerrar la declaración preparada
        $stmt->close();
    } else {
        header('Location: ../Views/index.html?error=invalid');
        exit;
    }
} else {
    header('Location: ../Views/index.html?error=invalid');
    exit;
}
?>
