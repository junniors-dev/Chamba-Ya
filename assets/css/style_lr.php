<style>

body{
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-image: url(<?= BASE_URL ?>assets/img/fondo_login2.png) !important;
    background-position: top left;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-size: cover;
}

/* Zona clickeable sobre el logo del fondo (vuelve al inicio) */
.logo-home-link{
    position: fixed;
    top: 18px;
    left: 22px;
    width: 360px;
    height: 130px;
    z-index: 100;
    cursor: pointer;
}

@media screen and (max-width: 650px){
    .logo-home-link{ top: 10px; left: 12px; width: 190px; height: 78px; }
}

.container{
    position: relative;
    width: 850px;
    max-width: calc(100vw - 40px);
    height: 650px;
    background: #fff;
    border-radius: 30px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
    overflow: hidden;
}

.form_box{
    position: absolute;
    top: 0;
    width: 50%;
    height: 100%;
    background: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #333;
    text-align: center;
    padding: 40px;
    z-index: 1;
    transition: .6s ease-in-out 1.2s, visibility 0s 1s;
}

.form_box.login{
    right: 0;
}

.form_box.register{
    left: 0;
    visibility: hidden;
}

.container.active .form_box{
    right: 50%;
}

.form_box.register{
    visibility: hidden;
}

.container.active .form_box.register{
    visibility: visible;
}

form{
    width: 100%;
}

.container h1{
    font-size: 36px;
    margin: -10px 0;
}

.input_box{
    position: relative;
    margin: 30px 0;
}

.input_box input{
    width: 100%;
    padding: 13px 50px 13px 20px;
    background: #eee;
    border-radius: 8px;
    border: none;
    outline: none;
    font-size: 16px;
    color: #333;
    font-weight: 500;
}

.input_box input::placeholder{
    color: #888;
    font-weight: 400;
}

.input_box i{
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #888;
}

.link_olvido{
    margin: -15px 0 15px;    
}

.link_olvido a{
    font-size: 14.5px;
    color: #333;
    text-decoration: none;
}

.btn_link{ /*BOTONES DE INICIAR SESIÓN Y REGISTRARSE*/
    width: 100%;
    height: 48px;
    background: #1D3D6E;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, .1);
    display: flex;
    justify-content: center;
    align-items: center;
    border: none;
    cursor: pointer;
    font-size: 16px;
    color: #fff;
    font-weight: 600;
    text-decoration: none;
}

.btn_forgot_pass{
    background: none;
    border: none;
    color: #333;
    font-weight: 600;
    font-size: 15.5px;
    cursor: pointer;
    padding: 7px;
}

.btn{ /*BOTONES DE CAMBIO DE APARTADO (PARA CAMBIAR ENTRE LOGIN Y REGISTER)*/
    width: 100%;
    height: 48px;
    background: #1D3D6E;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, .1);
    border: none;
    cursor: pointer;
    font-size: 16px;
    color: #fff;
    font-weight: 600;
    text-decoration: none;
}

.container p{
    font-size: 14.5px;
    margin: 25px 0;
}

.iconos_redes{
    display: flex;
    justify-content: center;
}

.iconos_redes a{
    display: inline;
    padding: 10px;
    border: 2px solid #ccc;
    border-radius: 8px;
    font-size: 24px;
    color: #333;
    text-decoration: none;
    margin: 0 8px;
}

.toggle_box{
    position: absolute;
    width: 100%;
    height: 100%;
}

.toggle_box::before{
    content: '';
    position: absolute;
    left: -250%;
    width: 300%;
    height: 100%;
    background: #1D3D6E;
    border-radius: 150px;
    z-index: 2;
    transition: 1.8s ease-in-out;
}

.container.active .toggle_box::before{
    left: 50%;

}

.toggle_panel{
    position: absolute;
    width: 50%;
    height: 100%;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 2;
    transition: .6s ease-in-out;
}

.toggle_panel.toggle_left{
    left: 0;
    transition-delay: 1.2s;
}

.container.active .toggle_panel.toggle_left{
    left: -50%;
    transition-delay: .6s;
}

.toggle_panel.toggle_right{
    right: -50%;
    transition-delay: .6s;
}

.container.active .toggle_panel.toggle_right{
    right: 0;
    transition-delay: 1.2s;
}

.toggle_panel p{
    margin-bottom: 20px;
}

.toggle_panel .btn{
    width: 160px;
    height: 46px;
    background: transparent;
    border: 2px solid #fff;
    box-shadow: none;
}

@media screen and (max-width:860px) and (min-width:651px) {
    .container{
        width: 95%;
        height: auto;
        min-height: 650px;
    }
}

@media screen and (max-width:650px) {
    .container{
        height: calc(100vh - 40px);
    }

    .form_box{
        bottom: 0;
        width: 100%;
        height: 70%;
    }

    .container.active .form_box{
        right: 0;
        bottom: 30%;
    }

    .toggle_box::before{
        left: 0;
        top: -270%;
        width: 100%;
        height: 300%;
        border-radius: 20vw;
    }

    .container.active .toggle_box::before{
        left: 0;
        top: 70%;
    }

    .toggle_panel{
        width: 100%;
        height: 30%;
    }

    .toggle_panel.toggle_left{
        top: 0;
    }

    .container.active .toggle_panel.toggle_left{
        left: 0;
        top: -30%;
    }

    .toggle_panel.toggle_right{
        right: 0;
        bottom: -30%;
    }

    .container.active .toggle_panel.toggle_right{
        bottom: 0;
    }
}

@media screen and (max-width:400px){
    .form_box{
        padding: 20px;
    }

    .toggle_panel h1{
        font-size: 30px;
    }
}

    .form_msg {
        margin: 8px 0;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        text-align: center;
    }
    .form_msg_error {
        background-color: #fdecea;
        color: #b3261e;
        border: 1px solid #f5c2c0;
    }
</style>