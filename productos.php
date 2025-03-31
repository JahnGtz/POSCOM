<?php
include('db/db.php');

// Consulta para obtener los productos del inventario
$query = "SELECT id, nombre, categoria, precio, cantidad FROM productos";
$resultado = mysqli_query($conexion, $query);

// Verificar si la consulta se ejecutó correctamente
if (!$resultado) {
    die("Error al obtener los productos: " . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: white;
            padding: 50px;
        }
        .container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            margin: auto;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #444;
        }
        .button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            text-decoration: none;
        }
        .button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Inventario de Productos</h2>

        <!-- Tabla para mostrar los productos -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Producto</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['id']) ?></td>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= htmlspecialchars($producto['categoria']) ?></td>
                        <td><?= htmlspecialchars($producto['precio']) ?></td>
                        <td><?= htmlspecialchars($producto['cantidad']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="buttons-container">
            <a href="inventarios.php" class="button">Volver a Inventarios</a>
        </div>
    </div>

</body>
</html>
