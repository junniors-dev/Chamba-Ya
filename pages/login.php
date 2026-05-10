<?php
    require_once __DIR__ . '/../assets/db/conexion.php';

    session_start();

    //REGISTRO DE USUARIOS

    if(isset($_POST['registro'])){

        //Limpiamos los datos de registro anteriores
        unset($_SESSION['registro_email']);
        unset($_SESSION['registro_password']);

        //Aqui empieza un nuevo registro

        $email = $_POST['emailInput'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        //VALIDAR SI EL USUARIO (CORREO) YA EXISTE EN LA BASE DE DATOS

        $sqlVerificar = "SELECT idUsuario FROM usuario WHERE correo = '$email'";

        $resultVerificacionEmail = $enlace->query($sqlVerificar);

        if($resultVerificacionEmail->num_rows > 0){
            echo "
            <script>
            alert('El correo ingresado ya está registrado. Por favor, utiliza otro correo.');
            window.history.back();
            </script>"
            ;
            exit();
        }


        //VALIDAR QUE LAS CONTRASEÑAS COINCIDAN
        if($password === $confirmPassword){
            //guardamos temporalmente los datos, para luego usarlos 
            //en el formulario de datos adicionales

            $_SESSION['registro_email'] = $email;
            $_SESSION['registro_password'] = password_hash($password, PASSWORD_DEFAULT);

            header('Location: form_datos_user.php');
            exit();
        }else{
            echo "<script>alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');</script>";
            unset($_SESSION['registro']);
        }
    }

    //INICIO DE SESIÓN DE USUARIOS

    //Si el usuario ya tiene una sesión activa, lo redirigimos al index
    if(isset($_SESSION['idUsuario'])){
        header("Location: ../index.php");
        exit();
    }

    if(isset($_POST['login'])){
        $email = mysqli_real_escape_string($enlace, $_POST['emailInput']);
        $password = $_POST['passwordInput'];

        //BUSCAR USUARIO EN LA BASE DE DATOS
        $sqlLogin = "SELECT idUsuario, nombres, correo, password FROM usuario WHERE correo = '$email'";

        $resultado = $enlace->query($sqlLogin);

        if($resultado->num_rows > 0){
            $usuario = $resultado->fetch_assoc();

            //VALIDAR CONTRASEÑA
            if(password_verify($password, $usuario['password'])){
                //CREAR SESIÓN
                $_SESSION['idUsuario'] = $usuario['idUsuario'];
                $_SESSION['email'] = $usuario['correo'];
                $_SESSION['nombres'] = $usuario['nombres'];

                header('Location: ../index.php');
                exit();
            }else{
                echo "
                <script>
                alert('Contraseña incorrecta. Por favor, inténtalo de nuevo.');
                window.history.back();
                </script>";
            }
            }else{
                echo "
                <script>
                alert('No se encontró una cuenta con ese correo. Por favor, verifica tu correo o regístrate.');
                window.history.back();
                </script>";
            }
    }
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <?php require_once '../assets/css/style.php';?>
    <?php require_once '../assets/css/styles.php';?>
    <?php require_once '../assets/css/style_lr.php';?>
    <script src="../assets/js/function_lr.js"></script>
    <title>Clinica_Centenario</title>
</head>
<body>
    <!-- Apartado de inicio de sesión -->
    <div class="container">
        <div class="form_box login">
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" id="loginForm">
                <h1>Iniciar Sesión</h1>
                <div class="input_box">
                    <input type="email" placeholder="Email" required name="emailInput" >
                    <i class='bx bxs-user' ></i>
                </div>
                <div class="input_box">
                    <input type="password" placeholder="Contraseña" required name="passwordInput">
                    <i class='bx bxs-lock-alt' ></i>
                </div>
                <div class="link_olvido">   
                    <button type="button" class="btn_forgot_pass">¿Olvidó su contraseña?</button>
                </div>
                <button type="submit" class="btn_link" name="login">Iniciar Sesión</button>
                <p>o inicie sesión con</p>
                <div class="iconos_redes">
                    <a href="#"><i class='bx bxl-google' ></i></a>
                    <a href="#"><i class='bx bxl-facebook' ></i></a>
                    <a href="#"><i class='bx bxl-instagram' ></i></a>
                    <a href="#"><i class='bx bxl-linkedin-square' ></i></a>
                </div>
            </form>
        </div>

        <!-- Apartado de registro de datos -->

        <div class="form_box register">
            <form action="" method="POST" id="registerForm">
                <h1>Registrarse</h1>
                <div class="input_box">
                    <input type="email" placeholder="Email" required name="emailInput">
                    <i class='bx bxs-envelope' ></i>
                </div>
                <div class="input_box">
                    <input type="password" placeholder="Contraseña" required name="password">
                    <i class='bx bxs-lock-alt' ></i>
                </div>
                <div class="input_box">
                    <input type="password" placeholder="Confirme su contraseña" required name="confirmPassword">
                    <i class='bx bxs-lock-alt' ></i>
                </div>
                <button type="submit" name="registro" class="btn_link">Registrarse</button>
                <p>o registrese con</p>
                <div class="iconos_redes">
                    <a href="#"><i class='bx bxl-google' ></i></a>
                    <a href="#"><i class='bx bxl-facebook' ></i></a>
                    <a href="#"><i class='bx bxl-instagram' ></i></a>
                    <a href="#"><i class='bx bxl-linkedin-square' ></i></a>
                </div>
            </form>
        </div>

        <div class="toggle_box">
            <div class="toggle_panel toggle_left">
                <h1>Bienvenido/a denuevo!</h1>
                <p>No tienes cuenta con nosotros?</p>
                <button class="btn register_btn">Registrarse</button>
            </div>
            <div class="toggle_panel toggle_right">
                <h1>Hola, Bienvenido/a!</h1>
                <p>Ya tienes cuenta con nosotros?</p>
                <button class="btn login_btn">Inicia Sesión</button>
            </div>
        </div>
    </div>
</body>

</html>
