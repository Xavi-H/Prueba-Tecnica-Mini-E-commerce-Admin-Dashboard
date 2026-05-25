# Mini E-commerce · Prueba Técnica

E-commerce funcional con tienda pública y panel de administración, construido con PHP, SQLite y JavaScript vanilla.

---

## Video de demostración del funcionamiento de la web (clica la imagen)
[![Demo de la web](https://img.youtube.com/vi/62p6DM1bdyc/maxresdefault.jpg)](https://youtu.be/62p6DM1bdyc)


## Stack tecnológico

| Capa | Tecnología | Motivo |
|---|---|---|
| Frontend | HTML, CSS, JavaScript vanilla | Sin dependencias, carga inmediata |
| Backend | PHP | Sencillo de levantar, sin instalación |
| Base de datos | SQLite | Fichero local, sin servidor externo |

El motivo real aparte de los beneficios que tiene, es porque es con lo que mas comodo me siento, ya que es lo que más hemos utilizado en los proyectos de clase. 

---

## Estructura del proyecto

```
├── api/
│   └── productos.php  # Endpoints REST (GET productos, GET pedidos, POST checkout)
├── controller/
│   ├── carrito_compra.proc.js  # Lógica del carrito en localStorage
│   ├── login.proc.php  # Procesa el formulario de login
│   └── logout.proc.php  # Cierra la sesión del admin
├── dataBase/
│   ├── db_init.php  # Crea las tablas e inserta datos
│   ├── dataBase.db  # Fichero SQLite (se genera al inicializar)
│   └── consultas.sql  # Todas las consultas utilizadas
├── includes/
│   ├── db_connect.php  # Conexión a SQLite
│   ├── head.html  # <head> compartido (enlace a CSS + JS)
│   ├── header.php  # Navegación
│   ├── footer.html  # Pie de página
│   └── css/style.css
├── view/
│   ├── carrito_compra.php  # Página del carrito
│   ├── checkout.php  # Formulario de email y confirmación
│   ├── login.php  # Login del admin
│   └── panel_admin.php  # Dashboard con pedidos y estadísticas
└── index.php  # Catálogo de productos
```

---

## Instalación y puesta en marcha

**Requisitos:** PHP 8.0 o superior con la extensión `sqlite3` activada.

**1. Clonar el repositorio**
```bash
git clone https://github.com/Xavi-H/Prueba-Tecnica-Mini-E-commerce-Admin-Dashboard.git
```

**2. Inicializar la base de datos**

Ejecuta este comando una sola vez. Crea las tablas y carga los productos de prueba:
```bash
php dataBase/db_init.php
```

**3. Levantar el servidor local**
```bash
php -S localhost:8000
```

**4. Abrir en el navegador**

| URL | Descripción |
|---|---|
| `http://localhost:8000` | Tienda pública |
| `http://localhost:8000/view/panel_admin.php` | Panel de administración (requiere login)|

---

## Credenciales del administrador

```
Usuario: xavi
Contraseña: 1234
```

---

## Diseño de la base de datos

```
productos          pedidos               lineas_pedido         admins
─────────          ───────               ─────────────         ──────
id (PK)            id (PK)               id (PK)               id (PK)
nombre             email_cliente         pedido_id  (FK)       admin_nombre
descripcion        total                 producto_id (FK)      admin_pass
imagen             fecha                 cantidad
precio                                   precio_unitario
stock
```

**Relaciones:**
- Un `pedido` tiene una o más `lineas_pedido`.
- Cada `linea_pedido` apunta a un `producto`.
- `precio_unitario` se guarda en la linea_pedido (no en el producto) para preservar el precio histórico aunque el producto cambie de precio.

**Consulta SQL de los 3 productos más rentables** (usada en el panel admin):

```sql
SELECT p.id, p.nombre, SUM(l.cantidad * l.precio_unitario) AS total_vendido, SUM(l.cantidad) AS unidades_vendidas
FROM productos p
JOIN lineas_pedido l ON p.id = l.producto_id 
GROUP BY p.id
ORDER BY total_vendido DESC 
LIMIT 3
```

---

## Seguridad implementada

- **Anti SQL injection:** todas las queries usan `bindValue()` con tipo explícito.
- **Validación en servidor:** el checkout valida email con `filter_var`, rechaza cantidades negativas y comprueba stock antes de insertar.
- **Transacciónes:** el pedido usa `BEGIN / COMMIT / ROLLBACK`; si falla cualquier línea, no se guarda nada.
- **Precio desde la BBDD:** el servidor ignora el precio enviado por el cliente y lo lee siempre de la base de datos.
- **Panel protegido:** `/view/panel_admin.php` comprueba `$_SESSION['es_admin']` y redirige al login si no está autenticado.
