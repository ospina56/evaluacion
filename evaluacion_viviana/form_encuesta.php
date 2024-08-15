<?php
include("conexion.php");  // Archivo para conexión a la base de datos

// Función para obtener preguntas y opciones de la base de datos
function obtener_preguntas_y_opciones($id_encuesta) {
    global $conn;
    
    // Obtener las preguntas
    $sql_preguntas = "SELECT id_pregunta, texto_pregunta FROM tb_preguntas WHERE id_encuesta = ?";
    $stmt_preguntas = $conn->prepare($sql_preguntas);
    $stmt_preguntas->bind_param("i", $id_encuesta);
    $stmt_preguntas->execute();
    $result_preguntas = $stmt_preguntas->get_result();

    $preguntas = [];
    while ($pregunta = $result_preguntas->fetch_assoc()) {
        $id_pregunta = $pregunta['id_pregunta'];

        // Obtener opciones de respuesta para cada pregunta
        $sql_opciones = "SELECT id_posible_respuesta, texto_respuesta FROM tb_posibles_respuestas WHERE id_pregunta = ?";
        $stmt_opciones = $conn->prepare($sql_opciones);
        $stmt_opciones->bind_param("i", $id_pregunta);
        $stmt_opciones->execute();
        $result_opciones = $stmt_opciones->get_result();

        $opciones = [];
        while ($opcion = $result_opciones->fetch_assoc()) {
            $opciones[] = $opcion;
        }

        $preguntas[] = [
            'pregunta' => $pregunta['texto_pregunta'],
            'opciones' => $opciones
        ];
    }

    return $preguntas;
}

// ID de encuesta (puedes obtenerlo dinámicamente o definirlo aquí)
$id_encuesta = 1;  // Cambia esto según sea necesario
$preguntas_y_opciones = obtener_preguntas_y_opciones($id_encuesta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Encuesta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Encuesta</h1>
        <form action="guardar_encuesta.php" method="POST">
            <input type="hidden" name="id_encuesta" value="<?= $id_encuesta ?>">
            <input type="hidden" name="documento_invisible" value="<?= $_GET['cc'] ?>">

            <?php foreach ($preguntas_y_opciones as $index => $pregunta): ?>
                <fieldset class="form-group">
                    <legend><?= htmlspecialchars($pregunta['pregunta']) ?></legend>
                    <?php foreach ($pregunta['opciones'] as $opcion): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="respuesta[<?= $index ?>]" value="<?= $opcion['id_posible_respuesta'] ?>" required>
                            <label class="form-check-label"><?= htmlspecialchars($opcion['texto_respuesta']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </fieldset>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary">Enviar Encuesta</button>
        </form>
    </div>
</body>
</html>
