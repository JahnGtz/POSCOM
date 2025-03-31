<?php
include('db/db.php');
session_start();

// Si no existe el carrito en la sesión, crearlo
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// 🔹 Agregar producto al carrito
if (isset($_POST['agregar'])) {
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);

    // Obtener información del producto desde la base de datos
    $query = "SELECT id, nombre, categoria, precio, cantidad as stock FROM productos WHERE id = $id_producto";
    $resultado = mysqli_query($conexion, $query);
    $producto = mysqli_fetch_assoc($resultado);

    if (!$producto || $producto['precio'] <= 0) {
        die("Error: Producto no encontrado o precio inválido.");
    }

    // Verificar stock disponible
    if ($cantidad > $producto['stock']) {
        die("Error: Stock insuficiente.");
    }

    // Agregar o actualizar producto en el carrito
    $existe = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] == $producto['id']) {
            $item['cantidad'] += $cantidad;
            $item['total'] = $item['precio'] * $item['cantidad'];
            $existe = true;
            break;
        }
    }

    if (!$existe) {
        $producto['cantidad'] = $cantidad;
        $producto['total'] = $producto['precio'] * $cantidad;
        $_SESSION['carrito'][] = $producto;
    }
}

// 🔹 Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $id_producto = intval($_GET['eliminar']);
    foreach ($_SESSION['carrito'] as $index => $item) {
        if ($item['id'] == $id_producto) {
            unset($_SESSION['carrito'][$index]);
            $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar array
            break;
        }
    }
}

// 🔹 Confirmar compra
if (isset($_POST['confirmar'])) {
    $totalVenta = 0;

    // Insertar la venta en la base de datos
    $queryVenta = "INSERT INTO ventas (fecha_venta, total) VALUES (NOW(), 0)";
    if (mysqli_query($conexion, $queryVenta)) {
        $idVenta = mysqli_insert_id($conexion);
    } else {
        die("Error al registrar la venta: " . mysqli_error($conexion));
    }

    foreach ($_SESSION['carrito'] as $item) {
        $id_producto = intval($item['id']);
        $cantidad = intval($item['cantidad']);
        $precio = floatval($item['precio']);
        $subtotal = $cantidad * $precio;
        $totalVenta += $subtotal;

        // Insertar en detalle de venta
        $queryDetalle = "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio, subtotal) 
                         VALUES ($idVenta, $id_producto, $cantidad, $precio, $subtotal)";
        if (!mysqli_query($conexion, $queryDetalle)) {
            die("Error al insertar detalle de venta: " . mysqli_error($conexion));
        }

        // Actualizar el stock
        $queryStock = "UPDATE productos SET cantidad = cantidad - $cantidad WHERE id = $id_producto";
        if (!mysqli_query($conexion, $queryStock)) {
            die("Error al actualizar stock: " . mysqli_error($conexion));
        }
    }

    // Actualizar el total de la venta
    $queryActualizarVenta = "UPDATE ventas SET total = $totalVenta WHERE id_venta = $idVenta";
    if (!mysqli_query($conexion, $queryActualizarVenta)) {
        die("Error al actualizar el total de la venta: " . mysqli_error($conexion));
    }

    $_SESSION['ultima_venta'] = $idVenta;
    $_SESSION['carrito'] = [];

    echo "<script>alert('Venta confirmada. Inventario actualizado correctamente.');
          window.location.href = 'tiket_venta.php?id_venta=$idVenta';</script>";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1a1a;
            color: white;
            padding: 20px;
        }
        .container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            margin: auto;
            box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
        }
        h2 {
            text-align: center;
            color: #f39c12;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        select, input, button {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        .button {
            padding: 8px 15px;
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: darkred;
        }
        .confirm-button {
            background-color: green;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            display: block;
            margin: auto;
        }
        .confirm-button:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrar Venta</h2>

        <form method="POST">
            <select name="id_producto" required>
                <option value="">Seleccione un producto</option>
                <?php
                $queryProductos = "SELECT id, nombre, categoria FROM productos";
                $resultadoProductos = mysqli_query($conexion, $queryProductos);
                while ($producto = mysqli_fetch_assoc($resultadoProductos)) {
                    echo "<option value='{$producto['id']}'>{$producto['nombre']} ({$producto['categoria']})</option>";
                }
                ?>
            </select>
            <input type="number" name="cantidad" placeholder="Cantidad" min="1" required>
            <button type="submit" name="agregar">Agregar</button>
        </form>

        <h2>Carrito de Compras</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($_SESSION['carrito'])) {
                    foreach ($_SESSION['carrito'] as $item) {
                        echo "<tr>
                                <td>{$item['nombre']}</td>
                                <td>{$item['categoria']}</td>
                                <td>{$item['cantidad']}</td>
                                <td>{$item['total']}</td>
                                <td><a href='ventas.php?eliminar={$item['id']}' class='button'>Eliminar</a></td>
                              </tr>";
                    }
                }
                ?>
            </tbody>
        </table>

        <form method="POST">
            <button type="submit" name="confirmar" class="confirm-button">Confirmar Venta</button>
        </form>
    </div>
</body>
</html>
