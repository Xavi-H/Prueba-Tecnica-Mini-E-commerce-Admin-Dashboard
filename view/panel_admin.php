<?php
include_once __DIR__ . '/../includes/head.html';
include_once __DIR__ . '/../includes/header.php'; 

if (!isset($_SESSION['es_admin'])) {
    header('Location: /index.php');
    exit;
}
?>
<h1>Panel de Administración</h1>

<h2>Ultimos Pedidos:</h2>

<div id="listado-pedidos"></div>

<script>
    const url = "/api/productos.php?action=pedidos"; // Obtener todos los pedidos
    fetch(url)
        .then(response => response.json())
        .then(data => {
        const container = document.getElementById("listado-pedidos");

        if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = "<p>No hay pedidos todavía.</p>";
            return;
        }
        let html = `
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
        `;
        data.forEach(pedido => {
            html += `
                <tr>
                    <td>${pedido.id}</td>
                    <td>${pedido.email_cliente}</td>
                    <td>$${parseFloat(pedido.total).toFixed(2)}</td>
                    <td>${pedido.fecha}</td>
                </tr>
            `;
        });
        html += `</tbody></table>`;
        container.innerHTML = html;
    })
    .catch(error => {
    document.getElementById("listado-pedidos").innerHTML = "<p>Error de conexión con la API.</p>";
    });
</script>
<?php include_once __DIR__ . '/../includes/footer.html'; ?>