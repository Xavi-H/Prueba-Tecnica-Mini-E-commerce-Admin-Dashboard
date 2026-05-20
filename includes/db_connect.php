<?php
$db = new SQLite3(__DIR__ . '/../dataBase/dataBase.db');
$db->exec("PRAGMA foreign_keys = ON"); // Activar las restricciones FOREIGN KEY
?>