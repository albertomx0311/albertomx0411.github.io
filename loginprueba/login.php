<?php
session_start();

// Verificar si el formulario de inicio de sesión fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que se enviaron datos de inicio de sesión
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Conectar a la base de datos (debes reemplazar con tus propios detalles de conexión)
        $servername = "localhost";
        $username = "tu_usuario";
        $password = "tu_contraseña";
        $dbname = "tu_base_de_datos";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Sanitizar y obtener los datos del formulario
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Consultar la base de datos para verificar las credenciales
        $sql = "SELECT * FROM usuarios WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verificar la contraseña
            if (password_verify($password, $row['password'])) {
                // Inicio de sesión exitoso, establecer variables de sesión
                $_SESSION['id'] = $row['id'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['email'] = $row['email'];
                // Redireccionar al usuario a una página de bienvenida o a donde desees
                header("Location: welcome.php");
                exit();
            } else {
                // Contraseña incorrecta
                echo "Contraseña incorrecta";
            }
        } else {
            // Usuario no encontrado
            echo "Usuario no encontrado";
        }

        // Cerrar la conexión
        $conn->close();
    } else {
        echo "Por favor, proporcione su correo electrónico y contraseña";
    }
}
?>
