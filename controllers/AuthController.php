<?php
    require_once __DIR__ . '/../core/config/autoload.php';
    require_once __DIR__ . '/../core/config/config.php';
    require_once __DIR__ . '/../models/userModel.php';

    class UserController{

        private $userModel;

        public function __construct(){
            $this->userModel = new UserModel();
        }

        public function registerFirst(){
            session_start();

            $email = $_POST['emailInput'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            if($this->userModel->emailExists($email)){
                die('El correo ya está registrado');
            }

            if($password !== $confirmPassword){
                die('Las contraseñas no coinciden');
            }

            $_SESSION['registro_email'] = $email;
            $_SESSION['registro_password'] = $password;

            header('Location: AuthController.php?action=showFormDatos');
            exit();
        }

        public function showDatosForm(){
            session_start();

            if(!isset($_SESSION['registro_email']) || !isset($_SESSION['registro_password'])){
                header('Location: login.php');
                exit();
            }

            $departamentos = $this->userModel->getDepartamentos();

            require_once __DIR__ . '/../views/auth/form_datos_user.php';
        }

        public function login(){
            session_start();

            $correo = $_POST['emailInput'] ?? '';
            $password = $_POST['passwordInput'] ?? '';

            $usuario = $this->userModel->getUserByEmail($correo);

            if(!$usuario){
                die('Usuario no registrado');
            }

            if(password_verify($password, $usuario['password'])){
                $_SESSION['nombres'] = $usuario['nombres'];
                $_SESSION['idUsuario'] = $usuario['idUsuario'];
                $_SESSION['emailUsuario'] = $usuario['correo'];
                header('Location: ../index.php');
            } else {
                die('Contraseña incorrecta');
            }
        }

        public function completeRegister(){
            session_start();

            if(!isset($_SESSION['registro_email']) || !isset($_SESSION['registro_password'])){
                header('Location: ../views/auth/login.php');
                exit();
            }

            //DATOS DEL PRIMER PASO DE REGISTRO (REGISTER INICIAL)
            $correo = $_SESSION['registro_email'];
            $password = $_SESSION['registro_password'];

            //DATOS DEL FORMULARIO
            $fotoPerfil = $_FILES['fotoPerfil'] ?? null;
            $descripcionPerfil = $_POST['descripcionPerfil'] ?? '';
            $nombres = $_POST['nombres'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $direccionDomicilio = $_POST['direccionDomicilio'] ?? '';
            $codigoPostal = $_POST['codigoPostal'] ?? '';
            $fechaRegistro = date('Y-m-d H:i:s');
            $estado = 'Activo';
            $idDistrito = $_POST['distrito'] ?? '';

            //GUARDADO DE LA FOTO DE PERFIL

            $nombreFoto = 'default.png';

            if(isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === 0){
                $extension = pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION);
                $permitidos = ['jpg', 'jpeg', 'png'];

                if(!in_array(strtolower($extension), $permitidos)){
                    die('Formato de imagen no permitido. Solo se permiten JPG, JPEG y PNG.');
                }

                $nombreFoto = uniqid() . '.' . $extension;

                $rutaDestino = __DIR__ . '/../assets/uploads/img_perfiles/' . $nombreFoto;

                move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $rutaDestino);

                //INSERCIÓN DEL USUARIO EN LA BASE DE DATOS

                $resultado = $this->userModel->createUser(
                    $nombreFoto,
                    $nombres,
                    $apellidos,
                    $descripcionPerfil,
                    $telefono,
                    $correo,
                    $password,
                    $direccionDomicilio,
                    $codigoPostal,
                    $fechaRegistro,
                    $estado,
                    $idDistrito
                );

                if($resultado){
                    $usuario = $this->userModel->getUserByEmail($correo);
                    
                    $_SESSION['idUsuario'] = $usuario['idUsuario'];
                    $_SESSION['nombres'] = $usuario['nombres'];
                    $_SESSION['correo'] = $usuario['correo'];

                    unset($_SESSION['registro_email']);
                    unset($_SESSION['registro_password']);

                    header('Location: ../index.php');
                    exit();
                }else{
                    die('Error al registrarse el usuario');   
                }
            }
        }
    }

    $controller = new UserController();

    $action = $_GET['action'] ?? '';

    switch($action){
        case 'registerFirst':
            $controller->registerFirst();
            break;
        case 'showFormDatos':
            $controller->showDatosForm();
            break;
        case 'login':
            $controller->login();
            break;
        case 'completeRegister':
            $controller->completeRegister();
            break;
        default:
            die('Acción no válida');
    }
?>