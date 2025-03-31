<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a POSCOM SITSTEMA Y GESTION DE INVENTARIOS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #1e1e2e;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #282a36;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        h1 {
            font-size: 36px;
            color: #ff79c6;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            color: #bd93f9;
            margin-bottom: 20px;
        }

        .icon-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .icon-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #44475a;
            padding: 15px;
            border-radius: 10px;
            text-decoration: none;
            color: #ffffff;
            font-size: 16px;
            transition: background 0.3s;
        }

        .icon-button:hover {
            background: #6272a4;
        }

        .icon-button i {
            font-size: 30px;
            margin-bottom: 8px;
        }

        footer {
            margin-top: 30px;
            padding: 10px;
            background: #191a21;
            border-radius: 5px;
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>Bienvenido a POSCOM Sistema y gestion de inventarios</h1>
        <p>Tu sistema de gestión de inventarios y ventas. Accede para comenzar.</p>
        
        <div class="icon-buttons">
            <a href="login.php" class="icon-button">
                <i class="fas fa-sign-in-alt"></i>
                Iniciar Sesión
            </a>
            <a href="registro.php" class="icon-button">
                <i class="fas fa-user-plus"></i>
                Registrarse
            </a>
        </div>
    </div>
</body>
</html>
