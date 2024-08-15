<?php
session_start();
include("config.php");


// Establecer la conexión a la base de datos
$conn = new mysqli($servidor, $usuario, $clave, $bd);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo_encuesta = $_POST['titulo_encuesta'];
    $descripcion_larga = $_POST['descripcion_larga'];
    $sn_publicar = isset($_POST['sn_publicar']) ? 1 : 0;

    $sql = "INSERT INTO tb_encuestas (titulo_encuesta, descripcion_larga, sn_publicar) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $titulo_encuesta, $descripcion_larga, $sn_publicar);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        die("Error al agregar encuesta: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Encuesta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Agregar Nueva Encuesta</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="titulo_encuesta">Título:</label>
                <input type="text" class="form-control" id="titulo_encuesta" name="titulo_encuesta" required>
            </div>
            <div class="form-group">
                <label for="descripcion_larga">Descripción:</label>
                <textarea class="form-control" id="descripcion_larga" name="descripcion_larga" required></textarea>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="sn_publicar" name="sn_publicar">
                <label class="form-check-label" for="sn_publicar">Publicar Encuesta</label>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Encuesta</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
