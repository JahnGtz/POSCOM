<?php
include('db/db.php');

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    // Recibir datos del formulario
    $nombre_producto = mysqli_real_escape_string($conexion, $_POST['nombre_producto']);
    $categoria = ($_POST['categoria'] === 'nueva') ? mysqli_real_escape_string($conexion, $_POST['nueva_categoria']) : mysqli_real_escape_string($conexion, $_POST['categoria']);
    $precio = mysqli_real_escape_string($conexion, $_POST['precio']);
    $stock = mysqli_real_escape_string($conexion, $_POST['stock']);

    // Verificar si hay algún campo vacío
    if (empty($nombre_producto) || empty($categoria) || empty($precio) || empty($stock)) {
        echo "<script>alert('Por favor, complete todos los campos.');</script>";
    } else {
        // Realizar la consulta de inserción
        $query = "INSERT INTO productos (nombre, categoria, precio, cantidad) VALUES ('$nombre_producto', '$categoria', '$precio', '$stock')";
        
        // Ejecutar la consulta
        $resultado = mysqli_query($conexion, $query);

        // Verificar si la consulta se ejecutó correctamente
        if ($resultado) {
            echo "<script>alert('Producto agregado exitosamente'); window.location.href='productos.php';</script>";
        } else {
            // Mostrar el error de MySQL
            echo "<script>alert('Error al agregar el producto: " . mysqli_error($conexion) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
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
        label {
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: none;
            background: #555;
            color: white;
            border-radius: 5px;
            margin-top: 5px;
        }
        .button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
        }
        .button:hover {
            background-color: #2980b9;
        }
        .buttons-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Agregar Nuevo Producto</h2>

        <!-- Formulario para agregar producto -->
        <form method="POST">
            <label for="nombre_producto">Nombre del Producto</label>
            <input type="text" id="nombre_producto" name="nombre_producto" required>

            <label for="categoria">Categoría</label>
            <select name="categoria" id="categoria" required>
                <option value="">Seleccionar categoría</option>
                <!-- Mostrar categorías existentes -->
                <?php
                $queryCategorias = "SELECT DISTINCT categoria FROM productos";
                $resultadoCategorias = mysqli_query($conexion, $queryCategorias);
                while ($fila = mysqli_fetch_assoc($resultadoCategorias)) {
                    echo "<option value='" . htmlspecialchars($fila['categoria']) . "'>" . htmlspecialchars($fila['categoria']) . "</option>";
                }
                ?>
                <option value="nueva">Nueva Categoría</option>
            </select>

            <input type="text" name="nueva_categoria" id="nueva_categoria" placeholder="Nombre de la nueva categoría" style="display: none;">

            <label for="precio">Precio</label>
            <input type="number" id="precio" name="precio" step="0.01" required>

            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" required>

            <button type="submit" name="add_product">Agregar Producto</button>
        </form>

        <div class="buttons-container">
            <button onclick="window.location.href='productos.php'">Ver Productos</button>
        </div>
    </div>

    <script>
        document.getElementById('categoria').addEventListener('change', function() {
            let nuevaCategoriaInput = document.getElementById('nueva_categoria');
            if (this.value === 'nueva') {
                nuevaCategoriaInput.style.display = 'inline-block';
            } else {
                nuevaCategoriaInput.style.display = 'none';
            }
        });
    </script>

</body>
</html>
