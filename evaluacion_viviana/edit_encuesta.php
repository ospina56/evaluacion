<?php
session_start();
include("config.php");

// Establecer la conexión a la base de datos
$conn = new mysqli($servidor, $usuario, $clave, $bd);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Definir la consulta SQL
$sql = "SELECT * FROM tb_encuestas"; // Reemplaza con tu consulta real

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}


$id_encuesta = $_GET['id'];

// Obtener datos de la encuesta
$sql = "SELECT * FROM tb_encuestas WHERE id_encuesta = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_encuesta);
$stmt->execute();
$result = $stmt->get_result();
$encuesta = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo_encuesta = $_POST['titulo_encuesta'];
    $descripcion_larga = $_POST['descripcion_larga'];
    $sn_publicar = isset($_POST['sn_publicar']) ? 1 : 0;

    $sql_update = "UPDATE tb_encuestas SET titulo_encuesta = ?, descripcion_larga = ?, sn_publicar = ? WHERE id_encuesta = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssii", $titulo_encuesta, $descripcion_larga, $sn_publicar, $id_encuesta);
    $stmt_update->execute();

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Encuesta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Encuesta</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="titulo_encuesta">Título:</label>
                <input type="text" class="form-control" id="titulo_encuesta" name="titulo_encuesta" value="<?= htmlspecialchars($encuesta['titulo_encuesta']) ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion_larga">Descripción:</label>
                <textarea class="form-control" id="descripcion_larga" name="descripcion_larga"><?= htmlspecialchars($encuesta['descripcion_larga']) ?></textarea>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="sn_publicar" name="sn_publicar" <?= $encuesta['sn_publicar'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="sn_publicar">Publicar Encuesta</label>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
