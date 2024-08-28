<?php
require_once '../Config/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['Nombre'];
    $correo = $_POST['Correo'];
    $numero = $_POST['Numero'];
    $password = $_POST['PasswordU'];

    // Asegurarse de que todos los campos estén llenos
    if (!empty($nombre) && !empty($correo) && !empty($numero) && !empty($password)) {
        // Agregar el prefijo "+521" al número
        $numero = "+521" . $numero;

        // Encriptar la contraseña antes de almacenarla
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Preparar la consulta de inserción
        $sql = "INSERT INTO TUsuarios (Nombre, Correo, Numero, PasswordU) VALUES (?, ?, ?, ?)";

        // Preparar la declaración
        if ($stmt = $conn->prepare($sql)) {
            // Vincular parámetros
            $stmt->bind_param("ssss", $nombre, $correo, $numero, $hashed_password);

            // Ejecutar la consulta
            if ($stmt->execute()) {
               
                // Redirigir o hacer lo que necesites después del registro
            } else {
                echo "Error: " . $stmt->error;
            }
            header("Location: ../Views/index.php");
            // Cerrar la declaración
            $stmt->close();
        }
    } else {
        echo "Por favor, llena todos los campos.";
    }

    // Cerrar la conexión
    $conn->close();
}
?>
