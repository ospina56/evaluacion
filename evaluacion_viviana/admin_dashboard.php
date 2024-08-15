<?php
// Incluir archivo de configuración
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

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
    <form method="POST" action="logout.php" class="mt-3">
            <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
    </form>
        <h1 class="my-4">Panel Administrador</h1>

        <?php
        // Verificar si la consulta devolvió resultados
        if ($result->num_rows > 0) {
            echo "<table class='table table-striped'>";
            echo "<thead><tr><th>ID Encuesta</th><th>Nombre Encuesta</th><th>Acciones</th></tr></thead>";
            echo "<tbody>";

            // Mostrar cada fila de resultados
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id_encuesta"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["titulo_encuesta"]) . "</td>";
                echo "<td>
                        <a href='edit_encuesta.php?id=" . urlencode($row["id_encuesta"]) . "' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='eliminar_encuesta.php?id=" . urlencode($row["id_encuesta"]) . "' class='btn btn-danger btn-sm'>Eliminar</a>
                        <a href='agregar_encuesta.php?id=" . urlencode($row["id_encuesta"]) . "' class='btn btn-success btn-sm'>Agregar</a>
                      </td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<div class='alert alert-info'>No se encontraron resultados.</div>";
        }

        // Cerrar la conexión
        $conn->close();
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
