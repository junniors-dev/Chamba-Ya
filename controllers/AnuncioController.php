<?php
    require_once __DIR__ . '/../core/config/config.php';
    require_once __DIR__ . '/../models/anuncioModel.php';


class AnuncioController {
    //correccion desde aqui
    public function explorar() {
        require_once __DIR__ . '/../models/anuncioModel.php';
        $model = new AnuncioModel();
        $tipo = $_GET['tipo'] ?? 'trabajo';
        
        // Recolectamos de la URL (GET) todos los filtros activos
        $filtros = [
            'tipoAnuncio'   => $tipo,
            'search'         => $_GET['search'] ?? '',
            'idCategoria' => $_GET['categoria'] ?? [],
            'precioMin'      => $_GET['precio_min'] ?? '',
            'idDepartamento' => $_GET['departamento'] ?? '',
            'idProvincia'    => $_GET['provincia'] ?? '',
            'idDistrito'     => $_GET['distrito'] ?? ''
        ];

        // Traemos la data filtrada y las listas auxiliares
        $anuncios = $model->obtenerAnuncios($filtros);
        $categorias = $model->obtenerCategorias();
        $departamentos = $model->obtenerDepartamentos();
        
        // Contamos cuántos elementos devolvió la consulta para actualizar el "X resultados"
        $totalResultados = count($anuncios);

        global $base_path;
        $base_path = isset($GLOBALS['base_path']) ? $GLOBALS['base_path'] : '';

        // Carga la interfaz visual pasándole las variables creadas arriba
        require_once __DIR__ . '/../views/anuncios/buscar_anuncios.php';
    }

    public function verDetalle() {
        require_once __DIR__ . '/../models/anuncioModel.php';
        $model = new AnuncioModel();
        
        $idAnuncio = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($idAnuncio === 0) {
            header("Location: index.php");
            exit();
        }
        
        $anuncio = $model->obtenerDetalleAnuncio($idAnuncio);
        if (!$anuncio) {
            die("El anuncio solicitado no existe.");
        }

        global $base_path;
        $base_path = isset($GLOBALS['base_path']) ? $GLOBALS['base_path'] : '';

        // Convertimos a minúsculas 
        $tipoAnuncioLimpio = isset($anuncio['tipoAnuncio']) ? strtolower(trim($anuncio['tipoAnuncio'])) : '';

        /*// Si es un servicio
        if ($tipoAnuncioLimpio === 'servicio') {
            // Traemos otros servicios del mismo usuario para el carrusel inferior
            $otrosServicios = $model->obtenerAnunciosPorUsuario($anuncio['idUsuario'], $idAnuncio);
            require_once __DIR__ . '/../views/auth/detalle_servicio.php';
        } else {
            // Si es trabajo (o cualquier otro), carga tu vista anterior
            require_once __DIR__ . '/../views/auth/detalle_anuncio.php';
        }*/

        if ($tipoAnuncioLimpio === 'servicio') {
            $otrosServicios = $model->obtenerAnunciosPorUsuario($anuncio['idUsuario'], $idAnuncio);
            $testimonios = $model->obtenerCalificacionesPorAnuncio($anuncio['idUsuario']);
            require_once __DIR__ . '/../views/anuncios/detalle_servicio.php';
        } else {
            require_once __DIR__ . '/../views/anuncios/detalle_anuncio.php';
        }
    }
}