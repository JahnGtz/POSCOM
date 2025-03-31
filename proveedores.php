<?php
// Incluir la conexión a la base de datos
include('db/db.php');

// Función para agregar un proveedor
if (isset($_POST['add_proveedor'])) {
    $nombre_proveedor = $_POST['nombre_proveedor'];
    $telefono = $_POST['telefono'];
    $empresa = $_POST['empresa'];

    // Insertar los datos en la tabla proveedores
    $sql = "INSERT INTO proveedores (nombre_proveedor, telefono, empresa) 
            VALUES ('$nombre_proveedor', '$telefono', '$empresa')";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Proveedor agregado exitosamente');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }
}

// Función para eliminar un proveedor
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Eliminar el proveedor de la base de datos
    $sql = "DELETE FROM proveedores WHERE id_proveedor = '$delete_id'";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Proveedor eliminado exitosamente');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }
}

// Función para modificar un proveedor
if (isset($_POST['update_proveedor'])) {
    $id_proveedor = $_POST['id_proveedor'];
    $nombre_proveedor = $_POST['nombre_proveedor'];
    $telefono = $_POST['telefono'];
    $empresa = $_POST['empresa'];

    // Actualizar los datos del proveedor en la base de datos
    $sql = "UPDATE proveedores 
            SET nombre_proveedor = '$nombre_proveedor', telefono = '$telefono', empresa = '$empresa'
            WHERE id_proveedor = '$id_proveedor'";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Proveedor actualizado exitosamente');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }
}

// Consultar todos los proveedores
$sql = "SELECT * FROM proveedores";
$result = mysqli_query($conexion, $sql);

// Verificar si la consulta ha tenido éxito
if (!$result) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Proveedores</title>
    <!-- Agregar los estilos del archivo CSS -->
    <link rel="stylesheet" href="estilos.css">
    <!-- Bootstrap CSS (si se utiliza) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #1f3c58; color: white;">

<div class="container">
    <h1 class="text-center text-white mt-5">Proveedores</h1>

    <!-- Formulario para agregar proveedor -->
    <form action="proveedores.php" method="POST" class="mt-4">
        <div class="form-group">
            <label for="nombre_proveedor" class="form-label">Nombre del Proveedor</label>
            <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" required>
        </div>

        <div class="form-group">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required>
        </div>

        <div class="form-group">
            <label for="empresa" class="form-label">Empresa</label>
            <input type="text" class="form-control" id="empresa" name="empresa" required>
        </div>

        <button type="submit" name="add_proveedor" class="btn btn-success mt-3">Agregar Proveedor</button>
    </form>

    <h2 class="text-center mt-5 text-white">Lista de Proveedores</h2>
    <table class="table table-striped table-bordered mt-4 text-white">
        <thead>
            <tr>
                <th>Nombre del Proveedor</th>
                <th>Teléfono</th>
                <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['nombre_proveedor']}</td>
                            <td>{$row['telefono']}</td>
                            <td>{$row['empresa']}</td>
                            <td>
                                <a href='?edit_id={$row['id_proveedor']}' class='btn btn-warning'>Modificar</a>
                                <a href='?delete_id={$row['id_proveedor']}' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro de eliminar este proveedor?\")'>Eliminar</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>No hay proveedores registrados</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Si se está modificando un proveedor
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $sql = "SELECT * FROM proveedores WHERE id_proveedor = '$edit_id'";
        $result_edit = mysqli_query($conexion, $sql);
        $row_edit = mysqli_fetch_assoc($result_edit);
        ?>
        
        <!-- Formulario para modificar proveedor -->
        <h3 class="text-center mt-5 text-white">Modificar Proveedor</h3>
        <form action="proveedores.php" method="POST" class="mt-4">
            <input type="hidden" name="id_proveedor" value="<?php echo $row_edit['id_proveedor']; ?>">
            <div class="form-group">
                <label for="nombre_proveedor" class="form-label">Nombre del Proveedor</label>
                <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" value="<?php echo $row_edit['nombre_proveedor']; ?>" required>
            </div>

            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $row_edit['telefono']; ?>" required>
            </div>

            <div class="form-group">
                <label for="empresa" class="form-label">Empresa</label>
                <input type="text" class="form-control" id="empresa" name="empresa" value="<?php echo $row_edit['empresa']; ?>" required>
            </div>

            <button type="submit" name="update_proveedor" class="btn btn-primary mt-3">Actualizar Proveedor</button>
        </form>

        <?php
    }
    ?>
</div>

<!-- Agregar Bootstrap JS (si se utiliza) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
