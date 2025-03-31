<?php
include('db/db.php');
session_start();

if (!isset($_GET['id_venta'])) {
    die("Error: No hay una venta confirmada.");
}

$id_venta = intval($_GET['id_venta']);

// Obtener información de la venta
$queryVenta = "SELECT fecha_venta, total FROM ventas WHERE id_venta = $id_venta";
$resultadoVenta = mysqli_query($conexion, $queryVenta);
$venta = mysqli_fetch_assoc($resultadoVenta);

if (!$venta) {
    die("Error: Venta no encontrada.");
}

// Obtener productos de la venta
$queryProductos = "SELECT p.nombre, dv.cantidad, dv.precio, dv.subtotal 
                   FROM detalle_venta dv
                   INNER JOIN productos p ON dv.id_producto = p.id
                   WHERE dv.id_venta = $id_venta";
$resultadoProductos = mysqli_query($conexion, $queryProductos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .ticket {
            width: 300px;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .ticket h2 {
            margin: 0;
            font-size: 18px;
            border-bottom: 2px dashed black;
            padding-bottom: 5px;
        }
        .ticket p {
            margin: 5px 0;
            font-size: 14px;
        }
        .ticket table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .ticket th, .ticket td {
            border-bottom: 1px dashed black;
            padding: 5px;
            font-size: 14px;
        }
        .ticket th {
            text-align: left;
        }
        .ticket .total {
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
        }
        .ticket .gracias {
            margin-top: 10px;
            font-size: 14px;
            font-style: italic;
        }
        .boton {
            display: block;
            background: black;
            color: white;
            padding: 10px;
            text-decoration: none;
            text-align: center;
            margin-top: 15px;
            border-radius: 5px;
        }
        .boton:hover {
            background: darkgray;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <h2>📜 Ticket de Venta</h2>
        <p><strong>Fecha:</strong> <?php echo $venta['fecha_venta']; ?></p>
        <p><strong>ID Venta:</strong> <?php echo $id_venta; ?></p>

        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($producto = mysqli_fetch_assoc($resultadoProductos)) {
                    echo "<tr>
                            <td>{$producto['nombre']}</td>
                            <td>{$producto['cantidad']}</td>
                            <td>\${$producto['precio']}</td>
                            <td>\${$producto['subtotal']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <p class="total">Total: $<?php echo number_format($venta['total'], 2); ?></p>

        <p class="gracias">¡Gracias por su compra!</p>

        <a href="ventas.php" class="boton">Volver a Ventas</a>
    </div>
</body>
</html>
