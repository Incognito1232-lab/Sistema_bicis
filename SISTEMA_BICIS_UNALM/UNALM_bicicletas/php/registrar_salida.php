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
$codigo_bicicleta_salida = $_POST['codigo_bicicleta_salida'];
$dni_salida = $_POST['dni_salida'];
$hora_salida = date("Y-m-d H:i:s"); // Obtiene la hora actual del servidor

// Insertar los datos en la tabla "salida"
$sql = "INSERT INTO salida (codigo_bicicleta, hora_salida, DNI_salida) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $codigo_bicicleta_salida, $hora_salida, $dni_salida);

if ($stmt->execute()) {
    // Si la inserción es exitosa, redirige a la misma página para mantener el formulario visible
    header("Location: ../templates/index.html");
} else {
    echo "Error al registrar la salida: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
