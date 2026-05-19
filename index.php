<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PruebaTecnica</title>
</head>
<body>
    <h1>Productos</h1>
    <div id="listado-productos"></div>

    <script>
        const url = "http://localhost:3000/api/productos.php"; // Mostra tots els productes
        fetch(url)
            .then(response => response.json())
            .then(data => {
            const container = document.getElementById("listado-productos");

            if (Array.isArray(data)) {
                data.forEach(producto => {
                const div = document.createElement("div");
                div.className = "producto";
                div.innerHTML = `
                    <img src="${producto.imatge}" alt="Imagen del Producto">
                    <div><button onclick="añadirAlCarrito(${producto.id})">Añadir al Carrito</button></div>
                    <div class="nom">${producto.nom}</div>
                    <div class="descripcio">${producto.descripcio}</div>
                    <div class="preu">$${producto.preu}</div>
                    <div class="stock">Stock: ${producto.stock}</div>
                `;
                container.appendChild(div);
                });
            } else {
                container.innerHTML = "<p>Error al obtenir les dades de l'API.</p>";
            }
        })
        .catch(error => {
        document.getElementById("listado-productos").innerHTML = "<p>Error de conexión con la API.</p>";
        });
        // TODO: Funcion para implementar producto al carro
    </script>
</body>
</html>