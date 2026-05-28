<style>  
    /* Estilos para el contenido principal */

    /*==================================*/
    /*==========DOBLE APARTADO==========*/
    /*==================================*/

    .container_halfs{
        display: flex;
    }

    .left_half, .right_half{
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 50%;
        padding: 50px;
    }
    
    .left_half {
        background-color: #24b55080;
    }

    .right_half {
        background-color: #FFCC0080;
    }

    .left_half h1, .right_half h1{
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .left_half p, .right_half p{
        font-size: 18px;
        height: 41.6px;
    }

    .left_half img, .right_half img{
        width: 350px;
    }

    .btn_right_first, .btn_right_second, .btn_left_first, .btn_left_second{
        border: none;
        padding: 15px 80px;
        cursor: pointer;
        margin-top: 20px;
        border-radius: 16px;
    }

    .btn_left_first{
        font-size: 18px;
        font-weight: bold;
        background-color: #0B46C5;
        color: #fff;
    }

    .btn_left_second{
        font-size: 16px;
        font-weight: bold;
        background-color: #32da64a2;
        border: 1px solid  #000;
        color:#000;
    }

    .btn_right_first{
        font-size: 18px;
        font-weight: bold;
        background-color: #FFCB00;
        color: #000
    }

    .btn_right_second{
        font-size: 16px;
        font-weight: bold;
        background-color: #FFE57F;
        border: 1px solid  #000;
        color:#000;
    }

    @media (max-width: 768px) {
        .container_halfs{ 
            display: flex;
            flex-direction: column;
        }

        .left_half, .right_half {
            width: 100%;
            padding: 30px;
        }
    }

    /*==================================*/
    /*============CATEGORIAS============*/
    /*==================================*/

    .categories{
        padding: 50px;
    }

    .categories_carousel{
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }   

    .carousel_wrapper{
        display: flex;
        gap: 20px;
        overflow-x: hidden;
        scroll-behavior: smooth;
        padding: 20px 5px;
        width: 100%;
    }

    .categories h2{
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .categories_card{
        display: flex;
        flex: 0 0 200px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        width: 200px;
        height: 200px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.6);
    }

    .categories_card{
        text-decoration: none;
        color: inherit;
    }

    .categories_card img{
        width: 100px;
        margin-top: 10px;
        transform: scale(1.8);
        overflow: hidden;
        object-fit: contain;
        margin-bottom: 10px;
    }

    .categories_card h3{
        font-size: 20px;
        font-weight: bold;
        margin-top: 20px;
    }

    .carousel_btn{
        background-color: rgba(0, 0, 0, 0.5);
        color: #fff;
        border: none;
        font-size: 24px;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 70%;
        position: absolute;
        z-index: 10;
        transition: background 0.3s;
    }

    .carousel_btn:hover{
        background-color: rgba(0, 0, 0, 0.8);
    }

    .prev{
        left: -10px;
    }

    .next{
        right: -10px;
    }

    /*==================================*/
    /*===========WHY CHAMBA YA==========*/
    /*==================================*/

    .why_chamba_ya{
        padding: 50px;
        background: #0088ff4f;
        text-align: center;
    }

    .why_chamba_ya h1{
        font-weight: bold;
        margin-bottom: 3vh;
    }

    .why_cards_container{
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        gap: 150px;
    }
    
    .why_card {
        width: 400px;
    }

    .why_card img{
        width: 200px;
    }

    .why_card h2{
        font-size: 26px;
        font-weight: bold;
        margin-top: 20px;
    }

    .why_card p{
        font-size: 18px;
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .why_cards_container{
            display: flex;
            flex-direction: column;
            gap: 50px;
        }

        .why_card {
            width: 100%;
        }
    }

    /*==================================*/
    /*==============FOOTER==============*/
    /*==================================*/

    .footer{
        background-color: #fff;
        padding: 40px 0;
    }

    .footer_container{
        max-width: 1200px;
        margin: 0 auto;
    }

    .footer_row{
        display: flex;
        justify-content: center;
        gap: 350px;
    }

    .footer_links{
        flex: none;
        width: auto;
        text-align: left;
        padding: 0 15px;
    }

    .footer_links h4{
        font-size: 1.13em;
        color: black;
        margin-bottom: 25px;
        font-weight: 500;
        border-bottom: 3px solid #015eafee;
        display: inline-block;
        padding-bottom: 10px;
    }

    .footer_links ul li a{
        font-size: 1.06em;
        text-decoration: none;
        color: rgb(85, 85, 85);
        display: block;
        margin-bottom: 15px;
        transition: all .3s ease;
    }

    .footer_links ul li a:hover{
        color: black;
        transform: translateX(6px);
    }

    .social_links a{
        display: inline-block;
        min-height: 40px;
        width: 40px;
        margin: 0 10px 10px 0;
        text-align: center;
        line-height: 40px;
        border-radius: 50%;
        color: black;
        transition: all .5s ease;
    }
    @media (max-width: 768px) {
        .footer_row{
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        .footer_links{
            text-align: center;
        }
        
        .footer_links h4{
            font-size: 1.3em;
        }

        .footer_links ul li a{
            font-size: 1.2em;
        }

        .social_links a{
            font-size: 1.2em;
        }
    }
</style>