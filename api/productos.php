<?php
require_once __DIR__ . '/../includes/db_connect.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // /api/productos.php - Obtener todos los productos
        $stmt = $db->query('SELECT * FROM productos');
        $productos = [];
        while ($producto = $stmt->fetchArray(SQLITE3_ASSOC)) {
            $productos[] = $producto;
        }
        echo json_encode($productos);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}