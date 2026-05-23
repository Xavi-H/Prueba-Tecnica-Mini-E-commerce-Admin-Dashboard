<?php
session_start();

require_once __DIR__ . '/../includes/head.html';
require_once __DIR__ . '/../includes/header.php';
?>

<h2>Iniciar sesión</h2>

<?php if(isset($_GET['error'])): // Si ha fallado el login muestra el error ?>
    <p class="missatge-error">Nombre o contraseña incorrecta</p>
<?php endif; ?>

<form method="POST" action="../controller/login.proc.php">
    <input type="text" name="admin_nombre" placeholder="Nombre de admin" required>
    <input type="password" name="admin_pass" placeholder="Contraseña" required>
    <button type="submit">Login</button>
</form>

<?php include_once __DIR__ . '/../includes/footer.html'; ?>