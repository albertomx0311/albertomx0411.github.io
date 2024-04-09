<?php
session_start(); // Iniciar sesión si no está iniciada

require_once "main.php";

// Almacenando datos
$usuario = limpiar_cadena($_POST['login_usuario']);
$clave = limpiar_cadena($_POST['login_clave']);

// Verificando campos obligatorios
if (empty($usuario) || empty($clave)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios.
        </div>
    ';
    exit();
}

// Verificando integridad de los datos
if (verificar_datos("[a-zA-Z0-9]{4,20}", $usuario)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El USUARIO no coincide con el formato solicitado.
        </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9$@.-]{8,100}", $clave)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            La CLAVE no coincide con el formato solicitado.
        </div>
    ';
    exit();
}

// Verificando usuario y contraseña
$check_user = conexion()->prepare("SELECT * FROM usuario WHERE usuario_usuario = :usuario");
$check_user->execute(array(':usuario' => $usuario));
$usuario_data = $check_user->fetch(PDO::FETCH_ASSOC);

if ($check_user->rowCount() == 1 && password_verify($clave, $usuario_data['usuario_clave'])) {
    $_SESSION['id'] = $usuario_data['usuario_id'];
    $_SESSION['nombre'] = $usuario_data['usuario_nombre'];
    $_SESSION['apellido'] = $usuario_data['usuario_apellido'];
    $_SESSION['usuario'] = $usuario_data['usuario_usuario'];

    if (headers_sent()) {
        echo "<script> window.location.href='index.php?vista=home'; </script>";
    } else {
        header("Location: index.php?vista=home");
    }
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Usuario o clave incorrectos.
        </div>
    ';
}
?>
