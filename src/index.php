<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba LAMP en Docker</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; }
        h1 { color: #333; }
        .ok { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        table { border-collapse: collapse; margin-top: 20px; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h1>Prueba funcional del stack LAMP</h1>

    <h2>1. Comprobación de PHP</h2>
    <?php
    echo "<p class='ok'>PHP funciona correctamente. Versión: " . phpversion() . "</p>";
    ?>

    <h2>2. Conexión a MariaDB</h2>
    <?php
    $host = 'mariadb';
    $db   = 'lamp_db';
    $user = 'lamp_user';
    $pass = 'lamp_pass';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<p class='ok'>Conexión a MariaDB establecida correctamente.</p>";

        $stmt = $pdo->query("SELECT id, mensaje, fecha FROM test_table ORDER BY id");
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($filas) > 0) {
            echo "<h2>3. Datos leídos de la base de datos</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Mensaje</th><th>Fecha</th></tr>";
            foreach ($filas as $fila) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($fila['id']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['mensaje']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['fecha']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>La tabla existe pero está vacía.</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='error'>Error de conexión: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>

    <h2>4. Información del entorno</h2>
    <p>Si ves esta página, Apache y PHP-FPM están trabajando juntos correctamente.</p>
</body>
</html>