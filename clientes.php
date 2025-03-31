<?php
session_start();
include('db/db.php');

// Agregar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);

    $query = "INSERT INTO clientes (nombre, telefono, email, direccion) VALUES ('$nombre', '$telefono', '$email', '$direccion')";
    mysqli_query($conexion, $query);
    header("Location: clientes.php");
    exit();
}

// Obtener clientes
$query = "SELECT * FROM clientes ORDER BY id DESC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
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
            width: 80%;
            margin: auto;
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
            background-color: #3498db;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        .button:hover {
            background-color: #2980b9;
        }
        .form-container {
            margin-top: 20px;
        }
        input, textarea {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            background-color: #444;
            color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Clientes Registrados</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cliente = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['id']) ?></td>
                    <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                    <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                    <td><?= htmlspecialchars($cliente['direccion']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="form-container">
            <h3>Agregar Cliente</h3>
            <form method="POST">
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="text" name="telefono" placeholder="Teléfono">
                <input type="email" name="email" placeholder="Correo Electrónico">
                <textarea name="direccion" placeholder="Dirección"></textarea>
                <button type="submit" class="button">Guardar Cliente</button>
            </form>
        </div>

    </div>

</body>
</html>
