<?php
// Incluir el archivo de conexión a la base de datos
include('db/db.php');
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Consultas para obtener datos de las tablas
$productos_query = "SELECT * FROM productos";
$productos_result = mysqli_query($conexion, $productos_query);

$ventas_query = "SELECT * FROM ventas";
$ventas_result = mysqli_query($conexion, $ventas_query);

$inventarios_query = "SELECT * FROM inventarios";
$inventarios_result = mysqli_query($conexion, $inventarios_query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="estilo_moderno.css">
<style>
/* ====== ESTILOS GENERALES ====== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    display: flex;
    background-color: #f8f9fa;
    color: #343a40;
}

/* ====== SIDEBAR (Menú Lateral) ====== */
.sidebar {
    width: 270px;
    background: linear-gradient(135deg, #2c3e50, #1a252f);
    color: white;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    padding-top: 20px;
    transition: all 0.3s ease-in-out;
    box-shadow: 3px 0 10px rgba(0, 0, 0, 0.2);
}

.sidebar h2 {
    text-align: center;
    font-size: 22px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    padding: 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    cursor: pointer;
    transition: 0.3s;
}

.sidebar ul li a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    display: flex;
    align-items: center;
}

.sidebar ul li:hover {
    background: rgba(255, 255, 255, 0.1);
    padding-left: 12px;
}

/* ====== CONTENIDO PRINCIPAL ====== */
.main-content {
    margin-left: 270px;
    padding: 20px;
    width: calc(100% - 270px);
    transition: all 0.3s ease-in-out;
}

.header {
    background: linear-gradient(135deg, #2980b9, #3498db);
    color: white;
    padding: 18px;
    text-align: center;
    font-size: 22px;
    font-weight: bold;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

/* ====== TABLAS ====== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 14px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

th {
    background: #2980b9;
    color: white;
}

tr:nth-child(even) {
    background: #f2f2f2;
}

tr:hover {
    background: #ecf0f1;
    transition: 0.3s;
}

/* ====== BOTONES ====== */
.btn {
    padding: 12px 18px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-transform: uppercase;
    font-size: 14px;
    font-weight: bold;
    transition: 0.3s;
}

.btn-primary {
    background: #3498db;
    color: white;
    box-shadow: 0px 4px 6px rgba(0, 123, 255, 0.3);
}

.btn-danger {
    background: #e74c3c;
    color: white;
}

.btn-success {
    background: #2ecc71;
    color: white;
}

.btn:hover {
    opacity: 0.85;
    transform: scale(1.05);
}

/* ====== FORMULARIOS ====== */
form {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
}

input, select {
    width: 100%;
    padding: 12px;
    margin-top: 8px;
    border: 1px solid #bdc3c7;
    border-radius: 6px;
    transition: 0.3s;
}

input:focus, select:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0px 0px 5px rgba(52, 152, 219, 0.5);
}

/* ====== RESPONSIVE ====== */
@media (max-width: 768px) {
    .sidebar {
        width: 220px;
    }

    .main-content {
        margin-left: 220px;
        width: calc(100% - 220px);
    }
}

@media (max-width: 576px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .main-content {
        margin-left: 0;
        width: 100%;
    }
}

</style>
    
</head>
<body>


<!-- Barra superior -->
<div class="navbar">
    <h2></h2>
    <button onclick="window.location.href='admin.php?logout=true'">Cerrar Sesión</button>
</div>

<!-- Contenedor principal -->
<div class="container">
    <!-- Panel lateral -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <h2>Menú</h2>
        </div>
        <ul class="sidebar-menu">
            <!--<li><a href="ventas.php"><i class="fas fa-shopping-cart"></i> Ventas</a></li>-->
            <li><a href="inventarios.php"><i class="fas fa-box"></i> Inventarios</a></li>
            <li><a href="productos.php"><i class="fas fa-cogs"></i> Productos</a></li>
            <li><a href="proveedores.php"><i class="fas fa-industry"></i> Proveedores</a></li>
            <li><a href="reportes.php"><i class="fas fa-chart-line"></i> Reportes</a></li>
        </ul>
    </nav>

    <!-- Contenido principal -->
    <div class="content">
        

        <?php
        // Cerrar sesión
        if (isset($_GET['logout'])) {
            session_destroy();
            header('Location: login.php');
        }
        ?>
    </div>
</div>

<!-- Añadir Font Awesome para los iconos -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
