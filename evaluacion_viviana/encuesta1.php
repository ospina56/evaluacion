<?php
include 'conexion.php';  // Incluye tu archivo de conexión a la base de datos

$id_encuesta = $_GET['id_encuesta'];  // Obtén el ID de la encuesta de la URL

// Consulta para obtener las preguntas y opciones de respuesta
$sql_preguntas = "SELECT * FROM tb_preguntas WHERE id_encuesta = ?";
$stmt = $conn->prepare($sql_preguntas);
$stmt->bind_param("i", $id_encuesta);
$stmt->execute();
$result_preguntas = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Encuesta</title>
</head>
<body>

<h1>Encuesta</h1>
<form action="guardar_respuestas.php" method="post">
    <input type="hidden" name="id_encuesta" value="<?php echo htmlspecialchars($id_encuesta); ?>">
    
    <?php while ($pregunta = $result_preguntas->fetch_assoc()): ?>
        <fieldset>
            <legend><?php echo htmlspecialchars($pregunta['texto_pregunta']); ?></legend>
            
            <?php
            // Consulta para obtener las opciones de respuesta para la pregunta actual
            $sql_opciones = "SELECT * FROM tb_posibles_respuestas WHERE id_pregunta = ?";
            $stmt_opciones = $conn->prepare($sql_opciones);
            $stmt_opciones->bind_param("i", $pregunta['id_pregunta']);
            $stmt_opciones->execute();
            $result_opciones = $stmt_opciones->get_result();
            
            while ($opcion = $result_opciones->fetch_assoc()): ?>
                <div>
                    <input type="radio" id="opcion_<?php echo htmlspecialchars($opcion['id_posible_respuesta']); ?>" name="respuesta[<?php echo htmlspecialchars($pregunta['id_pregunta']); ?>]" value="<?php echo htmlspecialchars($opcion['id_posible_respuesta']); ?>">
                    <label for="opcion_<?php echo htmlspecialchars($opcion['id_posible_respuesta']); ?>"><?php echo htmlspecialchars($opcion['texto_respuesta']); ?></label>
                </div>
            <?php endwhile; ?>
        </fieldset>
    <?php endwhile; ?>
    
    <button type="submit">Enviar Respuestas</button>
</form>

</body>
</html>

<?php
$stmt->close();
$stmt_opciones->close();
$conn->close();
?>
