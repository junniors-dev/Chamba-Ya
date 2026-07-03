<style>

    body{ background: var(--cy-paper); color: var(--cy-ink); }

    /*==================================*/
    /*============== HERO ==============*/
    /*==================================*/

    .container_halfs{
        display: flex;
        position: relative;
    }

    .left_half, .right_half{
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 50%;
        padding: 52px 50px 74px;
    }

    .left_half { 
        background-color: var(--cy-green); 
    }
    .right_half { 
        background-color: var(--cy-yellow); 
    }

    /* Apartado de las etiquetas que diferencian que servicio es */
    .hero_eyebrow{
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 999px;
        margin-bottom: 18px;
    }
    .left_half .hero_eyebrow{ 
        background: var(--cy-green-deep); 
        color: #fff; 
    }

    .right_half .hero_eyebrow{ 
        background: var(--cy-ink); 
        color: var(--cy-yellow); 
    }

    .left_half h1, .right_half h1{
        font-size: 42px;
        font-weight: 800;
        margin-bottom: 14px;
        max-width: 12ch;
    }

    .left_half p, .right_half p{
        font-size: 18px;
        min-height: 41.6px;
        line-height: 1.5;
        max-width: 42ch;
        color: rgba(21, 35, 26, 0.82);
    }

    .left_half img, .right_half img{
        width: 100%;
        max-width: 430px;
        margin: 18px 0 10px;
    }

    .btn_right_first, .btn_right_second, .btn_left_first, .btn_left_second{
        border: none;
        padding: 15px 56px;
        cursor: pointer;
        margin-top: 16px;
        border-radius: 14px;
        font-size: 17px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        transition: transform .15s ease, box-shadow .15s ease, background .15s ease, color .15s ease;
    }

    /* Primario = tinta sólida (resalta en verde y en amarillo, acción clara) */
    .btn_left_first, .btn_right_first{
        background-color: var(--cy-ink);
        color: #fff;
        box-shadow: 0 6px 0 rgba(0,0,0,0.18);
    }
    .btn_left_first:hover, .btn_right_first:hover{
        transform: translateY(-2px);
        box-shadow: 0 8px 0 rgba(0,0,0,0.20);
    }
    .btn_left_first:active, .btn_right_first:active{
        transform: translateY(2px);
        box-shadow: 0 3px 0 rgba(0,0,0,0.18);
    }

    /* Secundario = contorno sobre el campo de color */
    .btn_left_second, .btn_right_second{
        background-color: rgba(255,255,255,0.92);
        color: var(--cy-ink);
        border: 2px solid var(--cy-ink);
    }
    .btn_left_second:hover, .btn_right_second:hover{
        transform: translateY(-2px);
        background-color: #fff;
    }

    .btn_left_first:focus-visible, .btn_right_first:focus-visible,
    .btn_left_second:focus-visible, .btn_right_second:focus-visible{
        outline: 3px solid var(--cy-ink);
        outline-offset: 3px;
    }

    /*==================================*/
    /*========= BUSCADOR (firma) =======*/
    /*==================================*/

    .search_band{
        background: var(--cy-mist);
        padding: 0 24px 30px;
        display: flex;
        justify-content: center;
    }

    .search_card{
        width: 100%;
        max-width: 920px;
        background: #fff;
        border: 1px solid var(--cy-line);
        border-radius: 20px;
        margin-top: -44px;
        padding: 26px 32px 24px;
        box-shadow: 0 18px 40px rgba(12, 90, 44, 0.12);
        position: relative;
        overflow: hidden;
    }

    /* Barra bicolor: eco del split verde/amarillo. */
    .search_card::before{
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 6px;
    }

    .search_card h2{
        font-size: 26px;
        font-weight: 700;
        margin: 8px 0 4px;
    }
    .search_card .search_sub{
        font-size: 15px;
        color: #5b6b5f;
        margin-bottom: 18px;
    }

    .search_form{
        display: flex;
        gap: 10px;
    }
    .search_form .field{
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--cy-mist);
        border: 1.5px solid var(--cy-line);
        border-radius: 13px;
        padding: 0 16px;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .search_form .field:focus-within{
        border-color: var(--cy-green);
        box-shadow: 0 0 0 4px rgba(31, 174, 85, 0.15);
    }
    .search_form .field i{ color: #8a978c; font-size: 16px; }
    .search_form .field input{
        border: none;
        background: transparent;
        outline: none;
        flex: 1;
        height: 52px;
        font-size: 16px;
        color: var(--cy-ink);
    }
    .search_form .btn_buscar{
        background: var(--cy-green);
        color: #fff;
        border: none;
        border-radius: 13px;
        padding: 0 30px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: background .15s ease, transform .15s ease;
    }
    .search_form .btn_buscar:hover{ background: #18994a; transform: translateY(-1px); }
    .search_form .btn_buscar:focus-visible{ outline: 3px solid var(--cy-ink); outline-offset: 2px; }

    .search_tags{
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
    }
    .search_tags .label{ font-size: 13px; color: #5b6b5f; }
    .search_tags a{
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        color: var(--cy-green-deep);
        background: rgba(31, 174, 85, 0.12);
        padding: 6px 13px;
        border-radius: 999px;
        transition: background .15s ease;
    }
    .search_tags a:hover{ background: rgba(31, 174, 85, 0.22); }

    /*==================================*/
    /*========= TIRA DE CONFIANZA ======*/
    /*==================================*/

    .trust_strip{
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 14px 40px;
        padding: 0 24px 36px;
        background: var(--cy-mist);
    }
    .trust_item{
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 600;
        color: var(--cy-ink);
    }
    .trust_item i{
        color: var(--cy-green);
        font-size: 20px;
    }

    /*==================================*/
    /*============ CATEGORIAS ==========*/
    /*==================================*/

    .categories{ padding: 48px max(40px, calc((100% - 1240px) / 2)) 18px; }

    .section_head{ margin-bottom: 22px; }
    .section_eyebrow{
        display: block;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--cy-green);
        margin-bottom: 6px;
    }
    .categories h2, .recientes h2, .why_chamba_ya h2{
        font-size: 32px;
        font-weight: 700;
    }

    .categories_carousel{
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }
    .carousel_wrapper{
        display: flex;
        gap: 18px;
        overflow-x: hidden;
        scroll-behavior: smooth;
        padding: 18px 5px;
        width: 100%;
    }

    .categories_card{
        display: flex;
        flex: 0 0 190px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        width: 190px;
        height: 190px;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--cy-line);
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        text-decoration: none;
        color: var(--cy-ink);
        transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    }
    .categories_card:hover{
        transform: translateY(-5px);
        box-shadow: 0 14px 26px rgba(12, 90, 44, 0.16);
        border-color: var(--cy-green);
    }
    .categories_card img{
        width: 92px;
        height: 92px;
        margin-top: 12px;
        object-fit: contain;
        margin-bottom: 6px;
    }
    .categories_card h3{
        font-size: 18px;
        font-weight: 600;
        margin-top: 14px;
    }

    .carousel_btn{
        background-color: var(--cy-ink);
        color: #fff;
        border: none;
        font-size: 20px;
        width: 44px;
        height: 44px;
        cursor: pointer;
        border-radius: 50%;
        position: absolute;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform .15s ease, background .3s;
    }
    .carousel_btn:hover{ background-color: #000; transform: scale(1.06); }
    .prev{ left: -12px; }
    .next{ right: -12px; }

    /*==================================*/
    /*======== AVISOS RECIENTES ========*/
    /*==================================*/

    .recientes{ padding: 34px max(40px, calc((100% - 1240px) / 2)) 56px; }
    .recientes_head{
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 22px;
        gap: 16px;
        flex-wrap: wrap;
    }
    .recientes_head a.ver_todos{
        font-size: 15px;
        font-weight: 700;
        color: var(--cy-green-deep);
        text-decoration: none;
        white-space: nowrap;
    }
    .recientes_head a.ver_todos:hover{ text-decoration: underline; }

    .recientes_grid{
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }
    .aviso_card{
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid var(--cy-line);
        border-radius: 16px;
        padding: 18px 18px 16px;
        text-decoration: none;
        color: var(--cy-ink);
        transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    }
    .aviso_card:hover{
        transform: translateY(-4px);
        box-shadow: 0 14px 26px rgba(12, 90, 44, 0.14);
        border-color: var(--cy-green);
    }
    .aviso_top{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .aviso_tipo{
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 999px;
    }
    .aviso_tipo.trabajo{ background: rgba(11,70,197,0.12); color: #0B46C5; }
    .aviso_tipo.servicio{ background: rgba(31,174,85,0.14); color: var(--cy-green-deep); }
    .aviso_estrellas{ color: var(--cy-yellow); font-size: 14px; letter-spacing: 1px; }
    .aviso_card h3{
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .aviso_meta{
        font-size: 14px;
        color: #5b6b5f;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }
    .aviso_pago{
        margin-top: auto;
        padding-top: 12px;
        font-size: 17px;
        font-weight: 700;
        color: var(--cy-green-deep);
    }
    .recientes_empty{
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        color: #5b6b5f;
        border: 1.5px dashed var(--cy-line);
        border-radius: 16px;
    }

    /*==================================*/
    /*========== WHY CHAMBA YA =========*/
    /*==================================*/

    .why_chamba_ya{
        padding: 56px max(40px, calc((100% - 1240px) / 2));
        background: var(--cy-mist);
        text-align: center;
    }
    .why_chamba_ya .section_eyebrow{ 
        text-align: center; 
    }
    .why_chamba_ya h2{ 
        margin-bottom: 36px; 
    }

    .why_cards_container{
        display: flex;
        align-items: stretch;
        justify-content: center;
        gap: 24px;
        flex-wrap: wrap;
    }
    .why_card{
        width: 320px;
        background: #fff;
        border: 1px solid var(--cy-line);
        border-radius: 18px;
        padding: 32px 26px;
        text-align: center;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .why_card:hover{
        transform: translateY(-4px);
        box-shadow: 0 14px 26px rgba(12, 90, 44, 0.12);
    }
    .why_icon{
        width: 64px;
        height: 64px;
        margin: 0 auto 18px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(31,174,85,0.12);
        color: var(--cy-green-deep);
        font-size: 28px;
    }
    .why_card h3{ 
        font-size: 21px; 
        font-weight: 700; 
        margin-bottom: 10px; 
    }
    .why_card p{
        font-size: 16px; 
        color: #5b6b5f; 
        line-height: 1.55; 
    }

    /*==================================*/
    /*============ RESPONSIVE ==========*/
    /*==================================*/

    @media (max-width: 1000px){
        .recientes_grid{ 
            grid-template-columns: repeat(2, 1fr); 
        }
    }

    @media (max-width: 900px){
        .left_half h1, .right_half h1{ 
            font-size: 34px; 
        }

        .categories h2, .recientes h2, .why_chamba_ya h2{ 
            font-size: 26px; 
        }
    }

    @media (max-width: 768px){
        .container_halfs{ 
            flex-direction: column; 
        }

        .left_half, .right_half{ 
            width: 100%; 
            padding: 44px 26px 64px; 
        }

        .left_half img, .right_half img{ 
            max-width: 340px; 
        }

        .search_card{ 
            margin-top: -40px; 
            padding: 24px 20px 22px; 
        }

        .search_form{ 
            flex-direction: column; 
        }

        .search_form .btn_buscar{ 
            padding: 15px; 
        }

        .categories, .recientes, .why_chamba_ya{ 
            padding-left: 24px; 
            padding-right: 24px; 
        }
    }

    @media (max-width: 560px){
        .recientes_grid{
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px){
        .left_half h1, .right_half h1{ font-size: 27px; }
        .left_half, .right_half{ padding: 36px 18px 54px; }
        .left_half p, .right_half p{ font-size: 16px; }
        .search_card{ padding: 22px 16px 20px; }
        .search_card h2{ font-size: 21px; }
        .categories_card{ flex: 0 0 150px; width: 150px; height: 150px; }
        .categories_card img{ width: 76px; height: 76px; }
        .categories_card h3{ font-size: 16px; }
        .categories h2, .recientes h2, .why_chamba_ya h2{ font-size: 23px; }
        .trust_strip{ gap: 12px 22px; }
    }

    @media (prefers-reduced-motion: reduce){
        *{ transition: none !important; 
        scroll-behavior: auto !important; 
    }
    }
</style>
