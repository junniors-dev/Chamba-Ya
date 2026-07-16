<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    /*==============FOOTER==============*/
    .footer{
        background-color: #fff;
        border-top: 1px solid #e2e8f0;
        font-family: 'Inter', system-ui, 'Segoe UI', Arial, sans-serif;
        color: #0f172a;
    }

    .footer .footer_container{
        max-width: 1200px;
        margin: 0 auto;
        padding: 32px 24px 0;
    }

    .footer .footer_top{
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 56px;
        padding-bottom: 22px;
    }
    .footer .footer_brand{ 
        flex: 0 1 360px; 
    }
    
    .footer .footer_cols{
        display: flex;
        gap: 64px;
    }

    /* ---- Columna de marca ---- */
    .footer .footer_logo{ 
        display: inline-block; 
    }

    .footer .footer_logo img{ 
        display: block; height: auto; 
    }

    .footer .footer_tagline{
        font-size: 14px;
        line-height: 1.55;
        color: #64748b;
        margin: 8px 0 12px;
        max-width: 34ch;
    }

    .footer .footer_cta{
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        color: #1d4ed8;
        background: #eff6ff;
        padding: 9px 16px;
        border-radius: 999px;
        transition: background .2s ease;
    }
    .footer .footer_cta:hover{ 
        background: #dbeafe; 
    }

    .footer .footer_social{
        margin-top: 14px; 
    }

    .footer .social_label{
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 8px;
    }
    .footer .social_links{ 
        display: flex; 
        gap: 10px; 
    }

    .footer .social_links a{
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 16px;
        text-decoration: none;
        transition: all .2s ease;
    }
    .footer .social_links a:hover{
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
        transform: translateY(-2px);
    }

    /* ---- Columnas de enlaces ---- */
    .footer .footer_links h4{
        font-size: 15px;
        color: #0f172a;
        margin-bottom: 12px;
        font-weight: 600;
        border-bottom: 2px solid #2563eb;
        display: inline-block;
        padding-bottom: 7px;
    }

    .footer .footer_links ul{
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .footer .footer_links ul li a{
        font-size: 14px;
        text-decoration: none;
        color: #64748b;
        display: inline-block;
        margin-bottom: 8px;
        transition: color .2s ease, transform .2s ease;
    }

    .footer .footer_links ul li a:hover{
        color: #2563eb;
        transform: translateX(5px);
    }

    /* ---- Barra inferior ---- */
    .footer .footer_bottom{
        border-top: 1px solid #e2e8f0;
        padding: 14px 0 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .footer .footer_bottom span{
        font-size: 13px;
        color: #94a3b8;
    }

    .footer .footer_made{
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .footer .footer_made i{ 
        color: #2563eb; 
    }

    .footer a:focus-visible{
        outline: 2px solid #2563eb;
        outline-offset: 3px;
        border-radius: 4px;
    }

    .cookie-banner{
        position: fixed;
        left: 16px;
        right: 16px;
        bottom: 16px;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        max-width: 900px;
        margin: 0 auto;
        background: #15231a;
        color: #fff;
        padding: 16px 20px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,.25);
        opacity: 0;
        transform: translateY(20px);
        transition: opacity .25s ease, transform .25s ease;
        font-family: 'Inter', system-ui, Arial, sans-serif;
    }
    .cookie-banner--visible{ 
        opacity: 1; 
        transform: translateY(0); 
    }
    .cookie-banner__text{ 
        margin: 0; 
        font-size: 14px; 
        line-height: 1.5; 
        flex: 1; 
        min-width: 220px; 
    }
    .cookie-banner__text a{ 
        color: #FFC700; 
        text-decoration: underline; 
    }
    .cookie-banner__btn{
        background: #25C25E;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap;
        transition: background .15s ease;
    }
    .cookie-banner__btn:hover{ background: #18994a; }
    @media (max-width: 560px){
        .cookie-banner{ flex-direction: column; align-items: stretch; text-align: center; }
        .cookie-banner__btn{ width: 100%; }
    }
    
    @media (max-width: 900px){
        .footer .footer_top{ 
            flex-direction: column; 
            gap: 32px; 
        }

        .footer .footer_cols{ 
            gap: 48px; 
        }

        .footer .footer_brand{ 
            flex: 0 1 0px; 
        }
    }

    @media (max-width: 768px){
        .footer .footer_container{ 
            padding: 28px 22px 0; 
        }
        .footer .footer_bottom{ 
            justify-content: center; 
            text-align: center; 
        }
    }

    @media (max-width: 460px){
        .footer .footer_cols{ 
            flex-wrap: wrap; 
            gap: 28px; 
        }
    }
</style>
