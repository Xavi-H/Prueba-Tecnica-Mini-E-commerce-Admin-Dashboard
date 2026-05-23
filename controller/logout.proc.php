<?php
// Cierra la session del admin y redirige a la pagina de login
session_start();

session_unset();

session_destroy();

header("Location: /login.php");

exit;