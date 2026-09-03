<?php
// Configuración de la conexión a la base de datos
$host     = "localhost";
$dbname   = "mi_base_datos";
$username = "root";       // Cambia por tu usuario de base de datos
$password = "";           // Cambia por tu contraseña de base de datos

// Verificar que los datos se hayan enviado mediante el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Limpieza y validación básica de los campos recibidos
    $nombre    = trim($_POST['nombre'] ?? '');
    $genero    = trim($_POST['genero'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    // Comprobar que ningún campo esté vacío
    if (empty($nombre) || empty($genero) || empty($telefono) || empty($direccion)) {
        die("Por favor, completa todos los campos del formulario.");
    }

    try {
        // Conexión a la base de datos mediante PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Sentencia preparada para prevenir inyección SQL
        $sql = "INSERT INTO usuarios (nombre, genero, telefono, direccion) VALUES (:nombre, :genero, :telefono, :direccion)";
        $stmt = $pdo->prepare($sql);

        // Vincular los parámetros y ejecutar
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);

        if ($stmt->execute()) {
            echo "<h2>¡Registro guardado con éxito!</h2>";
            echo "<p><a href='index.html'>Volver al formulario</a></p>";
        }

    } catch (PDOException $e) {
        // En un entorno de producción es mejor registrar el error en un log y no mostrar $e->getMessage()
        echo "Error en la base de datos: " . $e->getMessage();
    }

} else {
    // Si intentan acceder directamente al archivo sin enviar el formulario
    header("Location: index.html");
    exit();
}
?>