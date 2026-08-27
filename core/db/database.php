<?php
/**
 * Conexión a la base de datos.
 *
 * Las credenciales ya no viven aquí: se leen de core/config/env.php, que está
 * en .gitignore. Así el repositorio público no expone usuario ni contraseña.
 */
class Database{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $modoDev;
    public $conn;

    public function __construct(){
        $rutaEnv = __DIR__ . '/../config/env.php';
        // Si no existe env.php (recién clonado), se usan los valores por defecto de XAMPP.
        $cfg = is_file($rutaEnv) ? require $rutaEnv : [];

        $this->host     = $cfg['db_host']     ?? 'localhost';
        $this->db_name  = $cfg['db_nombre']   ?? 'bd_chamba_ya';
        $this->username = $cfg['db_usuario']  ?? 'root';
        $this->password = $cfg['db_password'] ?? '';
        $this->modoDev  = (bool) ($cfg['modo_dev'] ?? false);
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    // Desactiva la emulación: las consultas preparadas se envían
                    // realmente al motor, que separa la sentencia de los datos.
                    // Es la defensa de fondo contra la inyección SQL.
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $exception) {
            // El mensaje real puede contener rutas, usuario y contraseña: solo se
            // registra en el log del servidor, nunca se imprime al visitante.
            error_log("Error de conexión a la BD: " . $exception->getMessage());
            http_response_code(500);
            if ($this->modoDev) {
                exit("Error de conexión (modo dev): " . $exception->getMessage());
            }
            exit("No se pudo conectar con el servidor. Inténtalo más tarde.");
        }
        return $this->conn;
    }

    /** Indica si el proyecto corre en modo desarrollo (leído de env.php). */
    public static function modoDev(): bool {
        $rutaEnv = __DIR__ . '/../config/env.php';
        $cfg = is_file($rutaEnv) ? require $rutaEnv : [];
        return (bool) ($cfg['modo_dev'] ?? false);
    }
}
