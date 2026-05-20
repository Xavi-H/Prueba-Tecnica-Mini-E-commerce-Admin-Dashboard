<?php include_once __DIR__ . '/includes/head.html'; ?>
<?php include_once __DIR__ . '/includes/header.html'; ?>

<body>
    <h1>Productos</h1>
    <div id="listado-productos"></div>

    <script>
        const url = "/api/productos.php"; // Mostra tots els productes
        fetch(url)
            .then(response => response.json())
            .then(data => {
            const container = document.getElementById("listado-productos");

            if (Array.isArray(data)) {
                data.forEach(producto => {
                const div = document.createElement("div");
                div.className = "producto";
                div.innerHTML = `
                    <img src="${producto.imagen}" alt="Imagen del Producto">
                    <div><button>Añadir al Carrito</button></div>
                    <div class="nom">${producto.nombre}</div>
                    <div class="descripcio">${producto.descripcion}</div>
                    <div class="preu">$${producto.precio}</div>
                    <div class="stock">Stock: ${producto.stock}</div>
                    <div id="mensaje-carrito"></div>
                    <div id="listado-productos"></div>
                `;
                const boton = div.querySelector("button");
                boton.addEventListener("click", () => {
                    addProductoAlCarrito(producto);
                });
                
                container.appendChild(div);
                });
            } else {
                container.innerHTML = "<p>Error al obtenir les dades de l'API.</p>";
            }
        })
        .catch(error => {
        document.getElementById("listado-productos").innerHTML = "<p>Error de conexión con la API.</p>";
        });
    </script>

<?php include_once __DIR__ . '/includes/footer.html'; ?>