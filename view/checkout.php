<?php include_once __DIR__ . '/../includes/head.html'; ?>
<?php include_once __DIR__ . '/../includes/header.html'; ?>

<body>
    <h1>Finalizar compra</h1>

    <div id="resumen-carrito"></div>

    <!-- Formulario de email -->
    <div id="formulario-finalizarCompra">
        <h2>Introduce tu email para confirmar el pedido</h2>
        <input type="email" id="input-email" placeholder="ejemplo@gmail.com" required>
        <p id="error-email" style="color:red; display:none;">Introduce un email válido.</p>
        <button id="btn-confirmar">Confirmar pedido</button>
    </div>

    <!-- Mensaje de éxito (oculto hasta que el pedido se procese) -->
    <div id="confirmacion" style="display:none;">
        <h2>¡Pedido realizado con éxito!</h2>
        <p>Email del cliente: <strong id="email-confirmado"></strong></p>
        <a href="/index.php">Volver a la tienda</a>
    </div>

    <script>
    function mostrarResumen() {
        const carrito = getCarrito();
        const contenedor = document.getElementById('resumen-carrito');

        if (carrito.length === 0) {
            contenedor.innerHTML = '<p>Tu carrito está vacío. <a href="/index.php">Volver a la tienda</a></p>';
            document.getElementById('formulario-finalizarCompra').style.display = 'none';
            return;
        }

        let total = 0;
        let html = '<h2>Resumen</h2><ul>';

        carrito.forEach(p => {
            const subtotal = p.precio * p.cantidad;
            total += subtotal;
            html += `<li>${p.nombre} * ${p.cantidad} = $${subtotal.toFixed(2)}</li>`;
        });

        html += `</ul><p><strong>Total: $${total.toFixed(2)}</strong></p>`;
        contenedor.innerHTML = html;
    }

    // Si confirma el pedido
    document.getElementById('btn-confirmar').addEventListener('click', async () => {
        const emailInput = document.getElementById('input-email');
        const errorEmail = document.getElementById('error-email');
        const email = emailInput.value.trim();

        // Validación básica del correo en el cliente
        if (!email || !emailInput.checkValidity()) {
            errorEmail.style.display = 'block';
            return;
        }
        errorEmail.style.display = 'none';

        const carrito = getCarrito();
        if (carrito.length === 0) return;

        // Deshabilitar botón mientras procesa la compra
        const btn = document.getElementById('btn-confirmar');
        btn.disabled = true;
        btn.textContent = 'Procesando...';

        try {
            const res = await fetch('/api/productos.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, carrito })
            });

            const data = await res.json();

            if (data.ok) {
                // Limpiar carrito y mostrar confirmación
                guardarCarrito([]);
                document.getElementById('formulario-finalizarCompra').style.display = 'none';
                document.getElementById('resumen-carrito').style.display = 'none';
                document.getElementById('email-confirmado').textContent = email;
                document.getElementById('confirmacion').style.display = 'block';
            } else {
                alert('Error al procesar el pedido: ' + data.error);
                btn.disabled = false;
                btn.textContent = 'Confirmar pedido';
            }
        } catch (e) {
            alert('Error de conexión. Inténtalo de nuevo.');
            btn.disabled = false;
            btn.textContent = 'Confirmar pedido';
        }
    });

    document.addEventListener('DOMContentLoaded', mostrarResumen);
    </script>
</body>

<?php include_once __DIR__ . '/../includes/footer.html'; ?>