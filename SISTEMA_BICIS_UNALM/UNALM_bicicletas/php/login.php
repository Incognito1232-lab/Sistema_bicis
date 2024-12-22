<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambiar según tu configuración
$password = ""; // Cambiar según tu configuración
$dbname = "sistemas_bicis_unalm";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$usuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
$contraseña = isset($_GET['contraseña']) ? $_GET['contraseña'] : '';

// Verificar si el usuario existe en la base de datos
$sql = "SELECT * FROM administradores WHERE usuario = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario); // Preparamos la consulta para evitar SQL Injection
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // El usuario existe, verificar la contraseña
    $row = $result->fetch_assoc();
    
    // Verificar la contraseña en texto plano (como la tienes en la base de datos)
    if ($contraseña === $row['contraseña']) {
        // Contraseña correcta, redirigir a index.html
        header("Location: ../templates/index.html");
        exit();
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}

$conn->close();
?>
