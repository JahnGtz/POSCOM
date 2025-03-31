<?php
// Incluir el archivo de conexión
include('db/db.php');

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password = mysqli_real_escape_string($conexion, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conexion, $_POST['confirm_password']);
    $rol = mysqli_real_escape_string($conexion, $_POST['rol']);

    // Validar si el usuario y contraseña no están vacíos y coinciden
    if (!empty($usuario) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            // Hash de la contraseña antes de guardarla
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            // Consultar si el usuario ya existe
            $consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
            $resultado = mysqli_query($conexion, $consulta);

            // Si no existe el usuario, insertarlo en la base de datos
            if (mysqli_num_rows($resultado) == 0) {
                // Insertar usuario en la base de datos
                $insertar = "INSERT INTO usuarios (usuario, password, rol) 
                             VALUES ('$usuario', '$password_hash', '$rol')";
                if (mysqli_query($conexion, $insertar)) {
                    echo "<p class='success'>Usuario registrado exitosamente.</p>";
                } else {
                    echo "<p class='error'>Error al registrar el usuario: " . mysqli_error($conexion) . "</p>";
                }
            } else {
                echo "<p class='error'>El nombre de usuario ya existe.</p>";
            }
        } else {
            echo "<p class='error'>Las contraseñas no coinciden.</p>";
        }
    } else {
        echo "<p class='error'>Por favor, complete todos los campos.</p>";
    }
}

// Cerrar la conexión
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1a1a;
            color: white;
            text-align: center;
            padding: 50px;
        }
        .container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            margin: auto;
            box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .form-footer p {
            margin-top: 15px;
            font-size: 14px;
        }
        .form-footer a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrar Usuario</h2>
        <form action="" method="POST">
            <label for="usuario">Nombre de Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirmar Contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="rol">Selecciona el rol:</label>
            <select id="rol" name="rol" required>
                <option value="admin">Administrador</option>
                <option value="vendedor">Vendedor</option>
            </select>

            <input type="submit" value="Registrar">
        </form>

        <div class="form-footer">
            <p>¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
        </div>
    </div>
</body>
</html>
