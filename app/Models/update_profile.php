<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUser'])) {
    header('Location: index.html');
    exit;
}

// Obtener el ID del usuario
$idUser = $_SESSION['idUser'];

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$numero = $_POST['numero'];
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Conectar a la base de datos
require_once '../Config/conn.php';

// Crear la consulta SQL
$sql = "UPDATE TUsuarios SET Nombre = ?, Correo = ?, Numero = ?";

if (!empty($password)) {
    // Si se ha proporcionado una nueva contraseña, añadir el campo al UPDATE
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $sql .= ", PasswordU = ?";
}

$sql .= " WHERE IdUser = ?";

// Preparar la consulta
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die('Error al preparar la consulta: ' . $conn->error);
}

// Bindear los parámetros
if (!empty($password)) {
    // Se proporcionó una nueva contraseña
    $stmt->bind_param("ssssi", $nombre, $correo, $numero, $passwordHash, $idUser);
} else {
    // No se proporcionó una nueva contraseña
    $stmt->bind_param("sssi", $nombre, $correo, $numero, $idUser);
}

// Ejecutar la consulta
if ($stmt->execute()) {
    // Redirigir a la página del perfil con un mensaje de éxito
    $_SESSION['mensaje'] = 'Tu perfil ha sido actualizado exitosamente.';
} else {
    // Redirigir a la página del perfil con un mensaje de error
    $_SESSION['mensaje'] = 'Error al actualizar el perfil. Inténtalo de nuevo.';
}

$stmt->close();
$conn->close();

// Redirigir a la página del perfil
header('Location: ../Views/myprofile.php');
exit;
?>
