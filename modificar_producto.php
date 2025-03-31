<?php
include('db/db.php');  // Asegúrate de que este archivo esté correctamente configurado

// Verificar si se pasa un ID por la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del producto desde la base de datos
    $query = "SELECT * FROM productos WHERE d_producto = $id";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $producto = mysqli_fetch_assoc($resultado);
    } else {
        echo "Producto no encontrado.";
        exit;
    }
} else {
    echo "ID de producto no especificado.";
    exit;
}

// Código para actualizar el producto si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $update_query = "UPDATE productos SET 
                        nombre_producto = '$nombre', 
                        categoria = '$categoria', 
                        precio = '$precio', 
                        stock = '$stock' 
                    WHERE d_producto = $id";

    if (mysqli_query($conexion, $update_query)) {
        header("Location: productos.php");  // Redirigir a la lista de productos
        exit;
    } else {
        echo "Error al actualizar el producto: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Producto</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #2c3e50;
            color: white;
        }
        .container {
            width: 60%;
            margin: 0 auto;
            padding-top: 20px;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            background-color: #34495e;
            color: white;
        }
        .btn {
            padding: 10px 15px;
            color: white;
            background-color: #3498db;
            border: none;
            cursor: pointer;
            margin: 10px 0;
        }
        .btn-warning {
            background-color: #f39c12;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modificar Producto</h2>
        <form action="modificar_producto.php?id=<?php echo $producto['d_producto']; ?>" method="POST">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $producto['nombre_producto']; ?>" required>
            
            <label for="categoria">Categoría</label>
            <input type="text" id="categoria" name="categoria" value="<?php echo $producto['categoria']; ?>" required>
            
            <label for="precio">Precio</label>
            <input type="text" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" required>
            
            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" required>

            <button type="submit" class="btn btn-warning">Actualizar Producto</button>
        </form>
    </div>
</body>
</html>
