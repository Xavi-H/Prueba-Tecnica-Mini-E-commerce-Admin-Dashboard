<?php
require_once __DIR__ . '/../includes/db_connect.php'; // Connexió a la base de dades SQLite

// Creació de la taula productos
$db->exec("CREATE TABLE IF NOT EXISTS productos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    descripcio TEXT,
    imatge TEXT,
    preu REAL,
    stock INTEGER
)");

$db->exec("INSERT INTO productos (nom, descripcio, imatge, preu, stock) VALUES
('Conos', 'Set 40 Conos de entrenamiento', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcS5k78EeoDvFhWDm_Kt6U1S4klIokTKnvXFSHmXblsPCs0v-N7aKVlAGX9InMrZdy6w8jW9iDQlGPi6QWu2PYIEw51QXLA7Ne0HUXlWIYKGeQLraka7OnuQ4wcySnc8bE0w7igb3J0Z0Q&usqp=CAc', 15.95, 30),
('Vallas', 'Set de 6 mini vallas para velocidad y salto', 'https://www.dondeporte.com/3614157-home_default/valla-entrenamiento-antilesion-30cm.webp', 6.50, 200),
('Petos', 'Pack de 10 petos deportivos de colores', 'https://www.ofertadeportes.com/62883-large_default/petos-deportivos-asioka-basic-6211.jpg', 29.99, 5),
('Balón', 'Balón de fútbol profesional', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRVeQagrLOoWkEpNYtryFh4wfhwxLKex0vHXC9SiJlofZGHfX6YNTBIxkGyIqclwyhuYn9jN-4Fq9ZCkm27eJW_HqxTEZzTfzJmfhJVtP9C-Mcxc3JH75co3g&usqp=CAc', 75.10, 15),
('Cronómetro digital', 'Cronómetro deportivo resistente al agua', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQOO66nrQfhuf8XCbyaN-vi9c9jHa-ybl3wiEuRBol-roRJyNH4G6pantaXuZGMjwT1ZBanby4uYUu4BEvaefNCBiHSPtaN-eg9TOchI7itZ58Ax3FG_TAS40yz4w&usqp=CAc', 24.99, 65),
('Portería portátil', 'Portería plegable para entrenamientos de fútbol', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcRdngIkDEtB5iTlYm48ah6Hyw0SQ07d8ikqun8CiGoSigC87S0J0Cds8AF2bZarnyjih91t01QTDp7B6h4znYuOeNdWbVpShGjJ5ZQwxxq8HktQWwyTicuMpieWOJICe_voS-cijnxarA&usqp=CAc', 22.99, 18),
('Escalera de agilidad', 'Escalera de coordinación para entrenamiento físico', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcT58qDc4SKPweTZ3KWARH18WbicYVvJU365ghpOy8UNM_jQkbM5dSUkXd0hH56CmozQKXLmh-MaN4fRpiTh5uYZjeSlAA8Jiaqdmrv2RV49m3YE2MdzZC5sqlcg3D0gmzgjAHySntsfEaQ&usqp=CAc', 15.29, 38);");

// TODO: Tabla pedidos y lineas pedidos y relacionar con productos para el carrito de compra
