<?php
session_start();

// Verifica si el usuario ha iniciado sesión y si es un vendedor
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'vendedor') {
    // Si no está logueado o no es vendedor, redirige al login
    header('Location: login.php');
    exit();
}

// Cierre de sesión
if (isset($_GET['logout'])) {
    session_unset();  // Elimina todas las variables de sesión
    session_destroy();  // Destruye la sesión
    header('Location: login.php');  // Redirige a la página de login
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Vendedor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            background-color: #f4f6f9;
            color: #333;
        }

        /* Estilos para el panel lateral */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
            transition: all 0.3s;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #34495e;
            border-radius: 5px;
        }

        .sidebar .close-btn {
            position: absolute;
            top: 10px;
            right: -30px;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }

        .header {
            background-color: #3498db;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            width: 100%;
            text-align: center;
            font-size: 18px;
            border-radius: 5px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .sidebar-btn {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            font-size: 30px;
            color: #3498db;
            cursor: pointer;
            z-index: 999;
        }

        .sidebar.active {
            left: -250px;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }

            .content {
                margin-left: 0;
            }

            .sidebar-btn {
                display: block;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <span class="close-btn" onclick="toggleSidebar()">&times;</span>
        <a href="ventas.php">Ventas</a>
        <a href="clientes.php">Inventario</a>
        <a href="reportes.php">Reporte Diario</a>
        <a href="login.php" class="logout-btn">Cerrar sesión</a>
    </div>

    <!-- Content Area -->
    <div class="content">
        <div class="header">
            <h1>Panel de Vendedor</h1>
        </div>

        <p>Bienvenido al panel de ventas. Aquí podrás gestionar las ventas, consultar inventario y generar reportes diarios.</p>
    </div>

    <!-- Button to toggle sidebar -->
    <span class="sidebar-btn" onclick="toggleSidebar()">&#9776;</span>

    <script>
        // Función para mostrar/ocultar el panel lateral
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

</body>
</html>
