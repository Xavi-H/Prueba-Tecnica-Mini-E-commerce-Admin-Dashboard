<?php
session_start();
// Si se accede por POST
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../includes/db_connect.php';

    $admin = $_POST['admin_nombre'];
    $pass = $_POST['admin_pass'];

    $stmt = $db->prepare("SELECT * FROM admins WHERE admin_nombre = :a AND admin_pass = :p");
    $stmt->bindValue(':a', $admin);
    $stmt->bindValue(':p', $pass);

    $dadasAdmin = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if($dadasAdmin) {
        $_SESSION['admin'] = $admin; // Guarda el nombre del admin en la sesión
        $_SESSION['es_admin'] = true;
        header("Location: /view/panel_admin.php");
        exit();
    } else {
        // Si falla, redirige al login con mensaje de error
        header("Location: /view/login.php?error=1");
        exit();
    }
} else {
    // Si se entra por GET o otro metodo, redirige al login
    header("Location: /view/login.php");
    exit();
}