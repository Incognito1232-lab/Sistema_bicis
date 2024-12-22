<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Bicicletas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #eef2f3, #8e9eab);
            color: #333;
        }
        h1 {
            text-align: center;
            color: #ffffff;
            background-color: #4CAF50;
            padding: 15px 0;
            margin: 0;
            font-size: 2rem;
            text-transform: uppercase;
        }
        .container {
            max-width: 90%;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            font-size: 1.2rem; /* Aumentar el tamaño de las letras */
            font-weight: bold; /* Hacer las letras en negrita */
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
            font-size: 1.1rem;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease-in-out;
        }
        .no-results {
            text-align: center;
            font-size: 1.2rem;
            color: #555;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #ddd;
            background-color: #fafafa;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 768px) {
            table, th, td {
                font-size: 1rem;
            }
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <h1>Consulta de Bicicletas</h1>
    <div class="container">
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

        // Obtener el código de la bicicleta desde el formulario
        $codigo_bicicleta_buscar = $_GET['codigo_bicicleta_buscar'];

        // Consultar la base de datos para obtener los datos de la bicicleta
        $sql = "SELECT bicicletas.codigo_bicicleta, ingreso.hora_ingreso, salida.hora_salida, 
                        IF(salida.hora_salida IS NULL, 'Sigue en las instalaciones', 'Fuera de la U') AS estado,
                        ingreso.DNI_ingreso, salida.DNI_salida
                FROM bicicletas
                LEFT JOIN ingreso ON bicicletas.codigo_bicicleta = ingreso.codigo_bicicleta
                LEFT JOIN salida ON bicicletas.codigo_bicicleta = salida.codigo_bicicleta
                WHERE bicicletas.codigo_bicicleta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $codigo_bicicleta_buscar);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Mostrar la información de la bicicleta en una tabla
            echo "<table>
                    <tr>
                        <th>Código de la bicicleta</th>
                        <th>Hora de Ingreso</th>
                        <th>Hora de Salida</th>
                        <th>Estado</th>
                        <th>DNI del que ingresó</th>
                        <th>DNI del que sacó</th>
                    </tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["codigo_bicicleta"] . "</td>
                        <td>" . $row["hora_ingreso"] . "</td>
                        <td>" . ($row["hora_salida"] ? $row["hora_salida"] : 'N/A') . "</td>
                        <td>" . $row["estado"] . "</td>
                        <td>" . $row["DNI_ingreso"] . "</td>
                        <td>" . ($row["DNI_salida"] ? $row["DNI_salida"] : 'N/A') . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='no-results'>No se encontraron resultados para este código de bicicleta.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
