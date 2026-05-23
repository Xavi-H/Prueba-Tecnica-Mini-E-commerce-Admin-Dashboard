<?php
session_start(); // Para ver si es admin o no
?>
<header>
    <nav>
        <ul>
            <li><a href="/index.php">Home</a></li>
            <li><a href="/view/carrito_compra.php">Carrito</a></li>
            <?php if(isset($_SESSION['es_admin'])): ?>
                <li><a href="/view/panel_admin.php">Panel Admin</a></li>
                <li><a href="/controller/logout.proc.php">Logout</a></li>
                <p><?php echo "Hola, " . $_SESSION['admin']; ?></p>
            <?php else: ?>
                <li><a href="/view/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>