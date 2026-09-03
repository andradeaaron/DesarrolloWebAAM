<?php
require_once 'catalogo.php';

$todo = obtenerCatalogo();

$categoriasConConteo = [];
foreach ($todo as $p) {
    $cat = $p->getCategoria();
    if (isset($categoriasConConteo[$cat])) {
        $categoriasConConteo[$cat] = $categoriasConConteo[$cat] + 1;
    } else {
        $categoriasConConteo[$cat] = 1;
    }
}

$categoriaSeleccionada = $_GET['categoria'] ?? "";
if ($categoriaSeleccionada !== "" && !isset($categoriasConConteo[$categoriaSeleccionada])) {
    $categoriaSeleccionada = "";
}

$productosFiltrados = [];
foreach ($todo as $prod) {
    if ($categoriaSeleccionada == "" || $prod->getCategoria() == $categoriaSeleccionada) {
        $productosFiltrados[] = $prod;
    }
}

$totalListados = 0;
$unidadesStock = 0;
$sumaPrecios = 0;
$precioMasAlto = 0;
$precioMasBajo = 0;

foreach ($productosFiltrados as $prod) {
    $totalListados++;
    $unidadesStock = $unidadesStock + $prod->getStock();
    $sumaPrecios = $sumaPrecios + $prod->getPrecio();

    if ($totalListados == 1) {
        $precioMasAlto = $prod->getPrecio();
        $precioMasBajo = $prod->getPrecio();
    } else {
        if ($prod->getPrecio() > $precioMasAlto) {
            $precioMasAlto = $prod->getPrecio();
        }
        if ($prod->getPrecio() < $precioMasBajo) {
            $precioMasBajo = $prod->getPrecio();
        }
    }
}

$precioPromedio = 0;
if ($totalListados > 0) {
    $precioPromedio = $sumaPrecios / $totalListados;
}

$totalCategoriasGlobal = 0;
foreach ($categoriasConConteo as $c) {
    $totalCategoriasGlobal++;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoStore USFX - Catalogo</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<header class="top-navbar">
    <div>
        <h1>TecnoStore USFX - Catalogo</h1>
        <p>Ejercicio 1 - Clases, objetos y arreglos en PHP</p>
    </div>
    <div class="nav-buttons">
        <a href="index.php">Ver todo</a>
        <a href="#">Ir a la tienda</a>
    </div>
</header>

<main class="main-content">
    <section>
        <h2 class="section-title">1. Filtro de productos</h2>
        <div class="dashed-line"></div>
        <div class="filter-panel">
            <form method="get" action="index.php">
                <label class="filter-label" for="categoria">Categoria</label>
                <div class="filter-controls">
                    <select name="categoria" id="categoria">
                        <option value="">-- Todas las categorias --</option>
                        <?php foreach ($categoriasConConteo as $nombreCat => $cantidad): ?>
                            <option value="<?= htmlspecialchars($nombreCat) ?>" <?= ($categoriaSeleccionada === $nombreCat) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nombreCat) ?> (<?= $cantidad ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Filtrar</button>
                </div>
            </form>
            <div class="url-generated">
                URL generada: <?= htmlspecialchars($_SERVER["REQUEST_URI"]) ?>
            </div>
        </div>
    </section>

    <section>
        <h2 class="section-title">2. Productos (<?= $totalListados ?> encontrados)</h2>
        <div class="dashed-line"></div>
        
        <?php if ($totalListados > 0): ?>
            <div class="cards-grid">
                <?php foreach ($productosFiltrados as $prod): ?>
                    <?= $prod->mostrarTarjeta() ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="padding: 15px; background: #fff; margin-bottom: 30px;">No se encontraron productos en esta categoría.</p>
        <?php endif; ?>
    </section>

    <section class="stats-container">
        <h2 class="section-title">3. Resumen calculado con funciones de arreglo</h2>
        <div class="dashed-line"></div>

        <p class="stats-subtitle">Estadisticas de los productos mostrados</p>
        <table class="stats-table">
            <thead>
                <tr>
                    <th>INDICADOR</th>
                    <th style="text-align: right;">VALOR</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Productos listados</td>
                    <td><?= $totalListados ?></td>
                </tr>
                <tr>
                    <td>Unidades en stock</td>
                    <td><?= $unidadesStock ?></td>
                </tr>
                <tr>
                    <td>Precio promedio</td>
                    <td>Bs <?= number_format($precioPromedio, 2) ?></td>
                </tr>
                <tr>
                    <td>Precio mas alto</td>
                    <td>Bs <?= number_format($precioMasAlto, 2) ?></td>
                </tr>
                <tr>
                    <td>Precio mas bajo</td>
                    <td>Bs <?= number_format($precioMasBajo, 2) ?></td>
                </tr>
                <tr class="highlight-row">
                    <td>Categorias del catalogo completo</td>
                    <td><?= $totalCategoriasGlobal ?></td>
                </tr>
            </tbody>
        </table>

        <p class="stats-subtitle">Contenido del arreglo $_GET</p>
        <pre class="debug-panel"><?php print_r($_GET); ?></pre>
    </section>
</main>

<footer>
    SIS 256 - Tecnologia y Desarrollo Web - Ing. Carlos David Montellano Barriga
</footer>

</body>
</html>