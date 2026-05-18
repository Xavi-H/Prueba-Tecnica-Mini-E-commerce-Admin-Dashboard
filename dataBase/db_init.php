<?php
require_once __DIR__ . '/../includes/db_connect.php'; // Connexió a la base de dades SQLite

// Creació de la taula productes
$db->exec("CREATE TABLE IF NOT EXISTS productes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    descripcio TEXT,
    imatge TEXT,
    preu REAL,
    stock INTEGER
)");