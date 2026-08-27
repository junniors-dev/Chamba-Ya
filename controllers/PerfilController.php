<?php
require_once __DIR__ . '/../core/config/autoload.php';
require_once __DIR__ . '/../core/config/config.php';
require_once __DIR__ . '/../core/config/session.php';
require_once __DIR__ . '/../models/HabilidadModel.php';
require_once __DIR__ . '/../models/TrabajadorFavoritoModel.php';

class PerfilController {
    private UserModel $userModel;
    private AnuncioModel $anuncioModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->anuncioModel = new AnuncioModel();
    }

    public function verPerfil(): void {
        iniciarSesion();

        $idUsuario = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($idUsuario <= 0) {
            header('Location: ' . BASE_URL . 'index.php');
            exit();
        }

        $usuario = $this->userModel->getUserById($idUsuario);
        if (!$usuario) {
            header('Location: ' . BASE_URL . 'index.php?error=perfil_no_existe');
            exit();
        }

        $ubicacionText = 'No especificada';
        if (!empty($usuario['idDistrito'])) {
            $loc = $this->userModel->getFullLocationByIdDistrito($usuario['idDistrito']);
            if ($loc) {
                $ubicacionText = $loc['distrito'] . ' - ' . $loc['provincia'] . ' - ' . $loc['departamento'];
            }
        }

        $calificacion = $this->userModel->obtenerCalificacionUsuario($idUsuario);
        $puntaje = round($calificacion['puntaje'] ?? 0);
        $testimonios = $this->anuncioModel->obtenerCalificacionesPorUsuario($idUsuario);
        $habilidades = (new HabilidadModel())->obtenerNombresDeUsuario($idUsuario);
        $servicios = $this->anuncioModel->obtenerServiciosActivosDeUsuario($idUsuario);

        // Trabajos anteriores: completan el perfil junto a la descripción y las
        // habilidades, que ya existían.
        require_once __DIR__ . '/../models/PortafolioModel.php';
        $portafolio = (new PortafolioModel())->obtenerDeUsuario($idUsuario);

        $idUsuarioActivo = obtenerIdUsuarioActivo();
        $esUnoMismo = $idUsuarioActivo > 0 && $idUsuarioActivo === $idUsuario;
        $esTrabajadorFavorito = false;
        if ($idUsuarioActivo > 0 && !$esUnoMismo) {
            $esTrabajadorFavorito = (new TrabajadorFavoritoModel())->esFavorito($idUsuarioActivo, $idUsuario);
        }

        $pageTitle = $usuario['nombres'] . ' ' . $usuario['apellidos'] . ' - Chamba Ya';
        global $base_path;
        $base_path = isset($GLOBALS['base_path']) ? $GLOBALS['base_path'] : '';

        require_once __DIR__ . '/../views/user/perfil_publico.php';
    }
}

// Entry point directo: controllers/PerfilController.php?id=X
(new PerfilController())->verPerfil();
?>
