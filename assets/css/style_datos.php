<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    padding:40px 20px;

    background:
        linear-gradient(135deg,#f7fff8,#eef8f2,#edf6ff);
}

/*==========================
    CONTENEDOR PRINCIPAL
===========================*/

.form-container{

    width:100%;
    max-width:980px;

    background:#fff;

    border-radius:28px;

    box-shadow:
        0 20px 45px rgba(0,0,0,.08);

    padding:35px;

}

/*==========================
      CABECERA
===========================*/

.form-header{

    display:flex;

    align-items:center;

    gap:22px;

    margin-bottom:35px;

}

.header-icon{

    width:82px;
    height:82px;

    border-radius:50%;

    border:2px solid #27c15a;

    display:flex;

    justify-content:center;

    align-items:center;

    color:#27c15a;

    font-size:36px;

    flex-shrink:0;

}

.form-header h1{

    color:#1b2a47;

    font-size:1.75rem;

    margin-bottom:8px;

}

.form-header h3{

    font-weight:500;

    color:#6d7482;

}

/*==========================
      MENSAJES
===========================*/

.form_msg{

    padding:12px;

    border-radius:10px;

    margin-bottom:25px;

    text-align:center;

}

.form_msg_error{

    background:#fdecec;

    color:#b91c1c;

    border:1px solid #f5bcbc;

}

/*==========================
 FOTO DE PERFIL
===========================*/

.profile-section{

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:30px;

    margin-bottom:30px;

}

.profile-picture{

    flex:1;

}

.profile-picture label{

    display:block;

    font-weight:700;

    margin-bottom:10px;

    color:#23324d;

}

.profile-picture input[type=file]{

    width:100%;

    padding:10px;

    border-radius:12px;

    border:1px solid #d8dde6;

    background:#fff;

}

.profile-picture p{

    margin-top:8px;

    font-size:.85rem;

    color:#666;

}

.profilePictureImage{

    width:120px;
    height:120px;

    border-radius:50%;

    border:2px dashed #27c15a;

    display:flex;

    justify-content:center;

    align-items:center;

    overflow:hidden;

    background:#f7fff9;

}

.profilePictureImage img{

    width:100%;

    height:100%;

    object-fit:cover;

    display:none;

}

/*==========================
   LABELS
===========================*/

label{

    display:block;

    margin-bottom:8px;

    color:#22304c;

    font-weight:700;

}

/*==========================
     INPUTS
===========================*/

input[type=text],
input[type=tel],
textarea,
select{

    width:100%;

    border:1px solid #d8dde6;

    border-radius:14px;

    padding:11px 14px;

    font-size:15px;

    transition:.25s;

    background:#fff;

}

textarea{

    resize:none;

    min-height:95px;

}

input:focus,
textarea:focus,
select:focus{

    outline:none;

    border-color:#28c55b;

    box-shadow:0 0 0 4px rgba(40,197,91,.15);

}

/*==========================
    FILAS
===========================*/

.form-row{

    display:flex;

    gap:18px;

    margin-bottom:18px;

}

.form-group{

    flex:1;

}

/*==========================
      BOTONES
===========================*/

.form_buttons{

    display:flex;

    gap:20px;

    margin-top:35px;

}

.form_buttons button{

    flex:1;

    padding:17px;

    border-radius:14px;

    cursor:pointer;

    font-size:17px;

    font-weight:700;

    transition:.25s;

}

.btn-primary{

    border:none;

    background:#28c55b;

    color:#fff;

}

.btn-primary:hover{

    background:#22a84d;

    transform:translateY(-2px);

}

.btn-secondary{

    background:#fff;

    border:2px solid #28c55b;

    color:#28c55b;

}

.btn-secondary:hover{

    background:#28c55b;

    color:#fff;

}

/*==========================
 RESPONSIVE
===========================*/

@media (max-width:1150px){

    .form-container{

        max-width:760px;

        padding:30px;

    }

    .profile-section{

        flex-direction:column;

        align-items:center;

        text-align:center;

    }

    .profile-picture{

        width:100%;

    }

    .profilePictureImage{

        width:110px;
        height:110px;

    }

    .form-row{

        flex-direction:column;

        gap:15px;

    }

    .form_buttons{

        flex-direction:column;

    }

}

@media (max-width:768px){

    body{

        padding:20px 12px;

    }

    .form-container{

        width:100%;

        padding:22px;

        border-radius:20px;

    }

    .form-header{

        flex-direction:column;

        text-align:center;

        gap:15px;

    }

    .header-icon{

        width:65px;

        height:65px;

        font-size:28px;

    }

    .form-header h1{

        font-size:1.45rem;

    }

    .form-header h3{

        font-size:.95rem;

    }

    .profile-section{

        flex-direction:column;

        align-items:center;

        gap:20px;

    }

    .profile-picture{

        width:100%;

    }

    .profilePictureImage{

        width:100px;

        height:100px;

    }

    .form-row{

        flex-direction:column;

        gap:14px;

    }

    .form_buttons{

        flex-direction:column;

        gap:14px;

    }

    .form_buttons button{

        width:100%;

    }

}

</style>