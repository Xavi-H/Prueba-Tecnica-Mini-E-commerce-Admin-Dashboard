-- TODAS LAS CONSULTAS UTILIZADAS (se usan en la API)

-- La creació de taules i els inserts estan a db_init.php

-- Todos los pedidos:
SELECT * FROM pedidos ORDER BY fecha DESC LIMIT 10;

-- Todos los productos:
SELECT * FROM productos;

-- Consulta compleja (Productos mas rentables)

SELECT p.id, p.nombre, SUM(l.cantidad * l.precio_unitario) AS total_vendido, SUM(l.cantidad) AS unidades_vendidas
FROM productos p
JOIN lineas_pedido l ON p.id = l.producto_id 
GROUP BY p.id
ORDER BY total_vendido DESC 
LIMIT 3

-- Coger informacion de un producto

SELECT id, precio, stock FROM productos WHERE id = :id;

-- Inserir un pedido nuevo

INSERT INTO pedidos (email_cliente, total) VALUES (:email, :total)

-- Inserir les lineas de cada pedido (cada producto es una linea nueva) 

INSERT INTO lineas_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario);

-- Actualitzar el stock despres de comprar

UPDATE productos SET stock = stock - :cantidad WHERE id = :id');
