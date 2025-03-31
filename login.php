<?php
// Incluir el archivo de conexión
include('db/db.php');

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password = mysqli_real_escape_string($conexion, $_POST['password']);
    $rol = mysqli_real_escape_string($conexion, $_POST['rol']);

    // Validar si el usuario, la contraseña y el rol no están vacíos
    if (!empty($usuario) && !empty($password) && !empty($rol)) {
        // Consultar si el usuario existe
        $consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $resultado = mysqli_query($conexion, $consulta);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            // Obtener los datos del usuario
            $usuario_bd = mysqli_fetch_assoc($resultado);
            $password_hash = $usuario_bd['password'];  // Corregir 'contrasena' a 'password'

            // Verificar si el rol coincide con el de la base de datos
            if ($usuario_bd['rol'] === $rol) {
                // Verificar si la contraseña es correcta
                if (password_verify($password, $password_hash)) {
                    // Iniciar sesión
                    session_start();
                    $_SESSION['usuario'] = $usuario_bd['usuario'];
                    $_SESSION['rol'] = $usuario_bd['rol'];

                    // Redirigir al panel correspondiente
                    if ($rol === 'admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: vendedor.php");
                    }
                    exit();
                } else {
                    echo "<p class='error'>Contraseña incorrecta.</p>";
                }
            } else {
                echo "<p class='error'>El rol no coincide.</p>";
            }
        } else {
            echo "<p class='error'>Usuario no encontrado.</p>";
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
    <title>Iniciar Sesión</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form action="" method="POST">
            <label for="usuario">Nombre de Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="rol">Selecciona el rol:</label>
            <select id="rol" name="rol" required>
                <option value="admin">Administrador</option>
                <option value="vendedor">Vendedor</option>
            </select>

            <input type="submit" value="Iniciar sesión">
        </form>

        <div class="form-footer">
            <p>¿No tienes cuenta? <a href="registro.php">Registrarse</a></p>
        </div>
    </div>
</body>
</html>
