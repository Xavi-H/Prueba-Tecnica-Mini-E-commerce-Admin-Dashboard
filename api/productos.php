<?php
require_once __DIR__ . '/../includes/db_connect.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'pedidos') {
            // /api/productos.php?action=pedidos - Obtener todos los pedidos
            $stmt = $db->query('SELECT * FROM pedidos ORDER BY fecha DESC');
            $pedidos = [];
            while ($pedido = $stmt->fetchArray(SQLITE3_ASSOC)) {
                $pedidos[] = $pedido;
                
            }
            echo json_encode($pedidos);
            exit;
        } else {
            // /api/productos.php - Obtener todos los productos
            $stmt = $db->query('SELECT * FROM productos');
            $productos = [];
            while ($producto = $stmt->fetchArray(SQLITE3_ASSOC)) {
                $productos[] = $producto;
            }
            echo json_encode($productos);
        }
        break;
    case 'POST':
        // /api/productos.php - Confirmar compra
        $data = json_decode(file_get_contents('php://input'), true);
        $email = trim($data['email'] ?? '');
        $carrito = $data['carrito'] ?? [];

        // Validación email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Email inválido']);
            exit;
        }
        // Validación carrito
        if (empty($carrito) || !is_array($carrito)) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'El carrito está vacío']);
            exit;
        }

        $db->exec('BEGIN TRANSACTION');
        
        try {
            $precioTotal = 0;
            $lineas_pedido = [];
            foreach ($carrito as $producto){
                if ($producto['cantidad'] <= 0) {
                    throw new Exception('Cantidad inválida para el producto con id: ' . $producto['id']);
                }

                $stmt = $db->prepare('SELECT id, precio, stock FROM productos WHERE id = :id');
                $stmt->bindValue(':id', $producto['id'], SQLITE3_INTEGER);
                $infoProducto = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

                if (!$infoProducto) {
                    throw new Exception('Producto no encontrado con id: ' . $producto['id']);
                }

                if ($infoProducto['stock'] < $producto['cantidad']) {
                    throw new Exception('Stock insuficiente para el producto con id: ' . $producto['id']);
                }

                $subtotal = $infoProducto['precio'] * $producto['cantidad'];
                $precioTotal += $subtotal;
                $lineas_pedido[] = ['producto_id' => $producto['id'], 'cantidad' => $producto['cantidad'], 'precio_unitario' => $infoProducto['precio']];
            }

            // Crear pedido
            $stmt = $db->prepare('INSERT INTO pedidos (email_cliente, total) VALUES (:email, :total)');
            $stmt->bindValue(':email', $email, SQLITE3_TEXT);
            $stmt->bindValue(':total', $precioTotal, SQLITE3_FLOAT);
            $stmt->execute();
            $pedido_id = $db->lastInsertRowID();

            // Insertar líneas y descontar stock
            foreach ($lineas_pedido as $linea) {
                $stmt = $db->prepare('INSERT INTO lineas_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)');
                $stmt->bindValue(':pedido_id', $pedido_id, SQLITE3_INTEGER);
                $stmt->bindValue(':producto_id', $linea['producto_id'], SQLITE3_INTEGER);
                $stmt->bindValue(':cantidad', $linea['cantidad'], SQLITE3_INTEGER);
                $stmt->bindValue(':precio_unitario', $linea['precio_unitario'], SQLITE3_FLOAT);
                $stmt->execute();

                $stmt = $db->prepare('UPDATE productos SET stock = stock - :cantidad WHERE id = :id');
                $stmt->bindValue(':cantidad', $linea['cantidad'], SQLITE3_INTEGER);
                $stmt->bindValue(':id', $linea['producto_id'], SQLITE3_INTEGER);
                $stmt->execute();
            }
            $db->exec('COMMIT'); // Confirmar cambios en la base de datos
            echo json_encode(['ok' => true, 'pedido_id'=> $pedido_id, 'total'    => $precioTotal]);
            
        } catch (Exception $e) {
            $db->exec('ROLLBACK'); // Deshacer cambios en la base de datos en caso de error
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
            exit;
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}