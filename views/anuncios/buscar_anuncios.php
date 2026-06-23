<?php
//buscar_anuncios.php
    require_once __DIR__ . '/../../assets/css/style_buscarAnuncio.php';
    require_once __DIR__ . '/../templates/header.php';
    //corregir desde aqui
    $tipo = $_GET['tipo'] ?? 'trabajo';
    $tituloPagina = ($tipo == 'trabajo')
        ? 'BUSCAR TRABAJO'
        : 'BRINDAR SERVICIOS';

    $claseColor = ($tipo == 'trabajo')
        ? 'verde'
        : 'amarillo';

    // Conservamos de manera estricta el valor del slider que viene por URL ($_GET)
    $precioActualSlider = !empty($_GET['precio_min']) ? $_GET['precio_min'] : 0;
    $categoriaSeleccionada = $_GET['categoria'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-witdth, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="<?= $claseColor ?>">
    <div class="title-banner-trabajo <?= $claseColor ?>">
        <?= $tituloPagina ?>
    </div>
    
    <form method="GET" action="index.php" id="formFiltrosGlobal">
        <input type="hidden" name="action" value="buscar-trabajo">
        <input type="hidden" name="tipo" value="<?= $tipo ?>">
        <div class="wrapper-busqueda">
            <aside class="sidebar-filtros <?= $claseColor ?>">
                <h3>Categorías:</h3>
                <ul class="lista-categorias-ui">
                    <?php foreach ($categorias as $cat): ?>
                        <li>
                            <label class="item-categoria-label">
                                <div>
                                    <input type="checkbox"
                                    name="categoria[]"
                                    value="<?= $cat['idCategoria']; ?>"
                                    
                                    <?= (isset($_GET['categoria']) && is_array($_GET['categoria']) && in_array($cat['idCategoria'], $_GET['categoria'])) ? 'checked' : ''; ?>
                                    onchange="enviarFormularioConScroll()">
                                    <?= htmlspecialchars($cat['nombre']); ?>
                                </div>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h3>Ubicación:</h3>
                <select id="filtroDepartamento" name="departamento" class="select-filtro-geo">
                    <option value="">Departamento</option>
                    <?php foreach ($departamentos as $dep): ?>
                        <option value="<?= $dep['idDepartamento']; ?>" 
                        <?= ((isset($_GET['departamento']) && $_GET['departamento'] == $dep['idDepartamento']) ? 'selected' : ''); ?>>
                            <?= htmlspecialchars($dep['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <select id="filtroProvincia" name="provincia" class="select-filtro-geo">
                    <option value="">Provincia</option>
                </select>
                
                <select id="filtroDistrito" name="distrito" class="select-filtro-geo" onchange="window.enviarFormularioConScroll();">
                    <option value="">Distrito</option>
                </select>
                
                <div class="contenedor-filtro-precio">
                    <h3 class="titulo-filtro-secundario">Monto mínimo deseado:</h3>
                    <div class="info-precio">
                        <span>S/. 0</span>
                        <span class="indicador-precio" id="indicadorPrecio">
                            S/. <?= htmlspecialchars($precioActualSlider); ?>
                        </span>
                    </div>
                    
                    <input type="range"
                    name="precio_min"
                    min="0"
                    max="500"
                    step="5"
                    value="<?= htmlspecialchars($precioActualSlider); ?>"
                    class="slider-precio"
                    id="rangoPrecioSlider"
                    oninput="document.getElementById('indicadorPrecio').innerText = 'S/. ' + this.value;"
                    onchange="document.getElementById('formFiltrosGlobal').submit();">
                </div>
                <a href="index.php?action=buscar-trabajo&tipo=<?= urlencode($tipo) ?>" class="limpiar-filtros"> Limpiar Filtros </a>
            </aside>
            
            <main class="main-feed-trabajos">
                <div class="contenedor-buscador">
                    <div class="contenedor-input-buscador">
                        <input type="text"
                        name="search"
                        id="inputBuscadorTexto"
                        class="input-buscador"
                        placeholder="<?= ($tipo=='trabajo')
                        ? 'Nombre del trabajo'
                        : 'Nombre del servicio...' ?>"
                        value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                    <button type="submit" class="btn-buscar"> BUSCAR </button>
                </div>
                
                <div class="resultados-header">
                    <h2 class="titulo-resultados"> Resultados de búsqueda </h2>
                    <p class="texto-resultados">
                        <?= $totalResultados; ?>
                        <?= $totalResultados === 1 ? 'resultado encontrado' : 'resultados encontrados'; ?>
                    </p>
                </div>
                
                
                <div class="grid-anuncios-layout">
                    <?php if(!empty($anuncios)): ?>
                        <?php foreach($anuncios as $anuncio): ?>
                            <!-- TRABAJO -->
                            <?php if($tipo == 'trabajo'): ?>
                                <div class="card-trabajo-ui">
                                    <div>
                                        <h3 class="titulo-card"><?= htmlspecialchars($anuncio['titulo']) ?></h3>
                                        <p class="ubicacion-card"><?= htmlspecialchars($anuncio['ubicacion']) ?></p>
                                        <p class="pago-card"> S/. <?= $anuncio['pagoReferencia'] ?></p>
                                    </div>
                                    <a class="btn-ver-mas-card" href="index.php?action=detalle-anuncio&id=<?= $anuncio['idAnuncio'] ?>&tipo=trabajo"> Ver más</a>
                                </div>
                            <!-- SERVICIO -->
                            <?php else: ?>
                                <div class="card-servicio">
                                    <h2 class="titulo-servicio"><?= htmlspecialchars($anuncio['titulo']) ?></h2>
                                    <p class="nombre-servicio">
                                        <?= htmlspecialchars($anuncio['nombres']) ?>
                                        <?= htmlspecialchars($anuncio['apellidos']) ?>
                                    </p>
                                    <p class="ubicacion-servicio"><?= htmlspecialchars($anuncio['ubicacion']) ?></p>
                                    <div class="estrellas">
                                        <?php
                                            $promedio = round($anuncio['promedio']);
                                            for($i=1;$i<=5;$i++){
                                                echo ($i <= $promedio)? "★" : "☆";
                                            }
                                        ?>
                                    </div>
                                    <a class="btn-ver-mas-servicio" href="index.php?action=detalle-anuncio&id=<?= $anuncio['idAnuncio'] ?>&tipo=servicio">Ver más </a>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="sin-resultados">
                            <p>No se encontraron resultados.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </form>
    
    <script>
        // Enlazar los filtros activos de PHP hacia variables globales leídas por buscar_Trabajo.js
        const BASE_URL = "<?= BASE_URL ?>";
        const idDepartamentoSeleccionado = "<?= $_GET['departamento'] ?? '' ?>";
        const idProvinciaSeleccionada = "<?= $_GET['provincia'] ?? '' ?>";
        const idDistritoSeleccionado = "<?= $_GET['distrito'] ?? '' ?>";
        
        // Simula comportamiento desmarcable único para los checkboxes de categorías
        function alternarCategoriaUnica(checkbox) {
            if (checkbox.checked) {
                const checkboxes = document.querySelectorAll('.chk-categoria-filtro');
                checkboxes.forEach((item) => {
                    if (item !== checkbox) item.checked = false;
                });
            } if (typeof window.enviarFormularioConScroll === 'function') {
                window.enviarFormularioConScroll();
            } else {
                document.getElementById('formFiltrosGlobal').submit();
            }
        }
        
        let temporizadorBusqueda;
        const buscadorInput = document.getElementById("inputBuscadorTexto");
        if (buscadorInput) {
            buscadorInput.addEventListener("input", function () {
                clearTimeout(temporizadorBusqueda);
                temporizadorBusqueda = setTimeout(() => {
                    if (typeof window.enviarFormularioConScroll === 'function') {
                        window.enviarFormularioConScroll();
                    } else {
                        document.getElementById("formFiltrosGlobal").submit();
                    }
                }, 500); // espera medio segundo después de dejar de escribir
            });
        }
        
    </script>
    <script src="<?= BASE_URL?>assets/js/buscar_Anuncio.js"></script>
    <?php require_once __DIR__ . '/../../views/templates/footer.php'; ?>
</body>
</html>
