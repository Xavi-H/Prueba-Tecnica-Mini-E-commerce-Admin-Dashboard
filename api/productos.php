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
    case 'POST':
        // /api/productos.php - Confirmar compra
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'];
        $carrito = $data['carrito'];

        if (!$email || !$carrito) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            exit;
        }

        $db->beginTransaction();
        try {
            $precioTotal = 0;
            $lineas_pedido = [];
            foreach ($carrito as $producto){
                $stmt = $db->prepare('SELECT precio, stock FROM productos WHERE id = :id');
                $stmt->bindValue(':id', $producto['id'], SQLITE3_INTEGER);
                $infoProducto = $stmt->fetch();

                if (!$infoProducto || $infoProducto['stock'] < $producto['cantidad']) {
                    throw new Exception('Stock insuficiente para el producto con id: ' . $producto['id']);
                }

                $subtotal = $infoProducto['precio'] * $producto['cantidad'];
                $total += $subtotal;
                $lineas_pedido[] = ['producto_id' => $producto['id'], 'cantidad' => $producto['cantidad'], 'precio_unitario' => $infoProducto['precio']];
            }

            // Crear pedido
            $stmt = $db->prepare('INSERT INTO pedidos (email_cliente, total) VALUES (:email, :total)');
            $stmt->bindValue(':email', $email, SQLITE3_TEXT);
            $stmt->bindValue(':total', $precioTotal, SQLITE3_FLOAT);
            $stmt->execute();
            $pedidoId = $db->lastInsertId();

            // Insertar líneas y descontar stock
            foreach ($lineas_pedido as $linea) {
                $stmt = $db->prepare('INSERT INTO lineas_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?,?,?,?)');
                $stmt->execute([$pedidoId, $linea['producto_id'], $linea['cantidad'], $linea['precio_unitario']]);

                $stmt = $db->prepare('UPDATE productos SET stock = stock - ? WHERE id = ?');
                $stmt->execute([$linea['cantidad'], $linea['producto_id']]);
            }
            $db->commit(); // Confirmar cambios en la base de datos
            echo json_encode(['ok' => true]);

        } catch (Exception $e) {
            $db->rollback(); // Deshacer cambios en la base de datos en caso de error
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
            exit;
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}