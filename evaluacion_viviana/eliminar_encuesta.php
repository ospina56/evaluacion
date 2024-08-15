<?php
session_start();
include("config.php");

// Establecer la conexión a la base de datos
$conn = new mysqli($servidor, $usuario, $clave, $bd);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID de la encuesta a eliminar
$id_encuesta = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verificar si se ha proporcionado un ID de encuesta válido
if ($id_encuesta <= 0) {
    die("ID de encuesta inválido.");
}

// Preparar y ejecutar la consulta para eliminar la encuesta
$sql = "DELETE FROM tb_encuestas WHERE id_encuesta = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("i", $id_encuesta);
if ($stmt->execute() === false) {
    die("Error en la ejecución de la consulta: " . $stmt->error);
}

// Cerrar la conexión
$stmt->close();
$conn->close();

// Redirigir al administrador a la página de administración
header("Location: admin_dashboard.php");
exit();
?>
