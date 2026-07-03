<?php
    require_once __DIR__ . '/../core/config/config.php';
    require_once __DIR__ . '/../models/anuncioModel.php';
    require_once __DIR__ . '/../models/userModel.php';

class AnuncioController {
    public function explorar() {
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
        $model = new AnuncioModel();
        $modeloUser = new UserModel();
        
        $idAnuncio = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($idAnuncio === 0) {
            header("Location: " . BASE_URL . "index.php");
            exit();
        }
        
        $anuncio = $model->obtenerDetalleAnuncio($idAnuncio);
        if (!$anuncio) {
            // En vez de cortar con die(), volvemos al inicio de forma amigable
            header("Location: " . BASE_URL . "index.php?error=anuncio_no_existe");
            exit();
        }
        
        // Obtener la calificación promedio del usuario que publicó el anuncio
        $calificacion = $modeloUser->obtenerCalificacionUsuario($anuncio['idUsuario']);
        $puntaje = round($calificacion['puntaje'] ?? 0);

        // ¿Ya lo guardó? (para el texto del botón)
        require_once __DIR__ . '/../models/AnuncioGuardadoModel.php';
        require_once __DIR__ . '/../core/config/session.php';
        $idUsuarioActivo = obtenerIdUsuarioActivo();
        $esFavorito = false;
        if ($idUsuarioActivo > 0) {
            $favModel = new AnuncioGuardadoModel();
            $esFavorito = $favModel->esFavorito($idUsuarioActivo, $idAnuncio);
        }

        // ¿El usuario ya guardó a este trabajador? (para el botón en servicios)
        require_once __DIR__ . '/../models/TrabajadorFavoritoModel.php';
        $esTrabajadorFavorito = false;
        if ($idUsuarioActivo > 0 && $idUsuarioActivo !== (int) $anuncio['idUsuario']) {
            $tfModel = new TrabajadorFavoritoModel();
            $esTrabajadorFavorito = $tfModel->esFavorito($idUsuarioActivo, (int) $anuncio['idUsuario']);
        }

        global $base_path;
        $base_path = isset($GLOBALS['base_path']) ? $GLOBALS['base_path'] : '';


        // Convertimos a minúsculas 
        $tipoAnuncioLimpio = isset($anuncio['tipoAnuncio']) ? strtolower(trim($anuncio['tipoAnuncio'])) : '';

        if ($tipoAnuncioLimpio === 'servicio') {
            $otrosServicios = $model->obtenerAnunciosPorUsuario($anuncio['idUsuario'], $idAnuncio);
            $testimonios = $model->obtenerCalificacionesPorUsuario($anuncio['idUsuario']);
            require_once __DIR__ . '/../models/HabilidadModel.php';
            $habilidadesServicio = (new HabilidadModel())->obtenerNombresDeUsuario($anuncio['idUsuario']);
            require_once __DIR__ . '/../views/anuncios/detalle_servicio.php';
        } else {
            require_once __DIR__ . '/../views/anuncios/detalle_anuncio.php';
        }
    }
}