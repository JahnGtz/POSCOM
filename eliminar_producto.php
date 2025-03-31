<?php
include('db/db.php');  // Asegúrate de que este archivo esté correctamente configurado

// Verificar si se pasa un ID por la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el producto
    $delete_query = "DELETE FROM productos WHERE d_producto = $id";
    if (mysqli_query($conexion, $delete_query)) {
        header('Location: productos.php');  // Redirigir a la lista de productos
        exit;
    } else {
        echo "Error al eliminar el producto: " . mysqli_error($conexion);
    }
} else {
    echo "ID de producto no especificado.";
}
?>
