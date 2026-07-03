<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root{
        --by-green: #25C25E;
        --by-green-deep: #0C5A2C;
        --by-yellow: #FFC700;
        --by-amber-deep: #9A7400;
        --by-ink: #15231A;
        --by-blue: #0B46C5;
        --by-mist: #F4F7F3;
        --by-line: #E4E9E4;
        --by-muted: #5b6b5f;
    }

    body{
        font-family: 'Inter', system-ui, 'Segoe UI', Arial, sans-serif;
        background: var(--by-mist);
        margin: 0;
        padding: 0;
        color: var(--by-ink);
    }

    /* ===================== BANNER ===================== */
    .title-banner-trabajo{
        text-align: center;
        padding: 40px 20px;
    }
    .title-banner-trabajo h1{
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0 0 8px;
        color: var(--by-ink);
    }
    .title-banner-trabajo p{
        margin: 0;
        font-size: 1.05rem;
        color: rgba(21, 35, 26, 0.78);
    }

    .title-banner-trabajo.verde{
        background: var(--by-green);
    }
    .title-banner-trabajo.amarillo{
        background: var(--by-yellow);
    }

    /* ===================== LAYOUT ===================== */
    .wrapper-busqueda{
        display: flex;
        align-items: flex-start;
        gap: 28px;
        max-width: 1280px;
        margin: 0 auto;
        padding: 28px 28px 40px;
        min-height: calc(100vh - 200px);
    }

    /* ===================== SIDEBAR ===================== */
    .sidebar-filtros{
        width: 280px;
        flex: 0 0 280px;
        background: #fff;
        padding: 24px 22px;
        box-sizing: border-box;
        border: 1px solid var(--by-line);
        border-radius: 16px;
        position: sticky;
        top: 16px;
    }
    .sidebar-filtros.verde{ 
        border-top: 4px solid var(--by-green); 
    }

    .sidebar-filtros.amarillo{ 
        border-top: 4px solid var(--by-yellow); 
    }

    .sidebar-filtros h3{
        font-size: 1rem;
        font-weight: 700;
        color: var(--by-ink);
        margin: 0 0 12px;
    }

    .lista-categorias-ui{
        list-style: none;
        padding: 0;
        margin: 0 0 24px 0;
    }
    .item-categoria-label{
        display: flex;
        align-items: center;
        padding: 9px 10px;
        border-radius: 9px;
        transition: background .15s ease;
        cursor: pointer;
        font-size: 0.93rem;
    }
    .item-categoria-label input{ 
        margin-right: 9px; 
        accent-color: var(--by-green); 
    }

    .verde .item-categoria-label:hover{ 
        background: rgba(31,174,85,0.12); 
    }

    .amarillo .item-categoria-label:hover{ 
        background: rgba(255,199,0,0.20); 
    }

    .amarillo .item-categoria-label input{ 
        accent-color: var(--by-amber-deep); 
    }

    .select-filtro-geo{
        width: 100%;
        padding: 11px 12px;
        border-radius: 11px;
        border: 1px solid var(--by-line);
        margin-bottom: 12px;
        font-size: 0.9rem;
        font-family: inherit;
        outline: none;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .select-filtro-geo:focus{
        border-color: var(--by-green);
        box-shadow: 0 0 0 3px rgba(31,174,85,0.15);
    }
    .amarillo .select-filtro-geo:focus{
        border-color: var(--by-amber-deep);
        box-shadow: 0 0 0 3px rgba(255,199,0,0.25);
    }

    .contenedor-filtro-precio{
        margin-top: 22px;
        padding-top: 20px;
        border-top: 1px solid var(--by-line);
    }
    .titulo-filtro-secundario{ 
        margin-bottom: 10px; 
    }

    .info-precio{
        display: flex;
        justify-content: space-between;
        font-size: .88rem;
        color: var(--by-muted);
        margin-bottom: 6px;
    }

    .indicador-precio{ 
        font-weight: 700; 
        color: var(--by-blue); 
    }

    .slider-precio{ 
        width: 100%; 
        cursor: pointer; 
        accent-color: var(--by-green); 
    }

    .amarillo .slider-precio{ 
        accent-color: var(--by-amber-deep); 
    }

    .limpiar-filtros{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        color: var(--by-muted);
        font-size: .9rem;
        margin-top: 22px;
        padding: 10px;
        border: 1px solid var(--by-line);
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all .15s ease;
    }

    .limpiar-filtros:hover{ 
        background: var(--by-mist); 
        color: var(--by-ink); 
    }

    /* ===================== MAIN ===================== */
    .main-feed-trabajos{ 
        flex: 1 1 auto; 
        width: 100%;
        min-width: 0; 
    }

    .contenedor-buscador{
        display: flex;
        gap: 12px;
        margin-bottom: 26px;
    }
    .contenedor-input-buscador{ 
        position: relative; 
        flex-grow: 1; 
    }
    
    .input-buscador{
        width: 100%;
        padding: 14px 20px;
        border-radius: 14px;
        border: 1px solid var(--by-line);
        font-size: .95rem;
        font-family: inherit;
        box-sizing: border-box;
        outline: none;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .input-buscador:focus{
        border-color: var(--by-green);
        box-shadow: 0 0 0 4px rgba(31,174,85,0.15);
    }
    .amarillo .input-buscador:focus{
        border-color: var(--by-amber-deep);
        box-shadow: 0 0 0 4px rgba(255,199,0,0.25);
    }

    .btn-buscar{
        color: #fff;
        border: none;
        padding: 0 34px;
        border-radius: 14px;
        font-weight: 700;
        font-size: .95rem;
        font-family: inherit;
        cursor: pointer;
        transition: transform .15s ease, filter .15s ease;
    }
    .btn-buscar:hover{ 
        transform: translateY(-1px); 
        filter: brightness(1.05); 
    }

    .verde .btn-buscar{
        background: var(--by-green-deep); 
    }

    .amarillo .btn-buscar{ 
        background: var(--by-amber-deep); 
    }

    .resultados-header{ 
        margin-bottom: 20px; 
    }

    .titulo-resultados{
        margin: 0;
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--by-ink);
    }
    .texto-resultados{
        margin: 4px 0 0;
        color: var(--by-muted);
        font-size: .92rem;
    }

    .grid-anuncios-layout{
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 16px;
    }

    /* ===================== CARDS ===================== */
    .card-trabajo-ui, .card-servicio{
        background: #fff;
        border: 1px solid var(--by-line);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    }

    .card-trabajo-ui:hover, .card-servicio:hover{
        transform: translateY(-4px);
        box-shadow: 0 14px 26px rgba(12,90,44,0.14);
    }

    .verde .card-trabajo-ui:hover{ 
        border-color: var(--by-green); 
    }

    .amarillo .card-servicio:hover{ 
        border-color: var(--by-yellow); 
    }

    .card-badge{
        align-self: flex-start;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 4px 11px;
        border-radius: 999px;
        margin-bottom: 12px;
    }
    .card-badge.trabajo{ 
        background: rgba(31,174,85,0.14); 
        color: var(--by-green-deep); 
    }

    .card-badge.servicio{ 
        background: rgba(255,199,0,0.22); 
        color: var(--by-amber-deep); 
    }

    .titulo-card, .titulo-servicio{
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0 0 8px;
        color: var(--by-ink);
        line-height: 1.25;
    }

    .ubicacion-card, .ubicacion-servicio, .nombre-servicio{
        font-size: .92rem;
        color: var(--by-muted);
        margin: 0 0 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .nombre-servicio{ font-weight: 600; color: #3a463d; }

    .pago-card{
        margin: 6px 0 16px;
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--by-green-deep);
    }

    .estrellas{
        color: var(--by-yellow);
        font-size: 1rem;
        margin: 4px 0 16px;
        display: flex;
        align-items: center;
        gap: 2px;
    }
    .estrellas .sin-resenas{
        color: var(--by-muted);
        font-size: .85rem;
        font-style: italic;
    }

    .btn-ver-mas-card, .btn-ver-mas-servicio{
        color: #fff;
        text-align: center;
        padding: 9px 22px;
        border-radius: 12px;
        text-decoration: none;
        font-size: .88rem;
        font-weight: 700;
        align-self: flex-end;
        margin-top: auto;
        border: none;
        cursor: pointer;
        transition: filter .15s ease, transform .15s ease;
    }
    .btn-ver-mas-card{ 
        background: var(--by-green-deep); 
    }

    .btn-ver-mas-servicio{ 
        background: var(--by-amber-deep); 
    }

    .btn-ver-mas-card:hover, .btn-ver-mas-servicio:hover{ 
        filter: brightness(1.08); 
        transform: translateY(-1px); 
    }

    /* ===================== EMPTY STATE ===================== */
    .sin-resultados{
        grid-column: 1 / -1;
        text-align: center;
        padding: 56px 24px;
        color: var(--by-muted);
        background: #fff;
        border: 1.5px dashed var(--by-line);
        border-radius: 16px;
    }

    .sin-resultados i{ 
        font-size: 2rem; 
        color: #c2cdc6; 
        margin-bottom: 12px; 
    }

    .sin-resultados p{ 
        font-size: 1.05rem; 
        margin: 0 0 4px; 
        font-weight: 600; 
        color: var(--by-ink); 
    }

    .sin-resultados span{ 
        font-size: .92rem; 
    }

    form{ 
        margin: 0; 
    }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 900px){
        .wrapper-busqueda{ 
            flex-direction: column; 
            padding: 20px 18px 32px; 
            gap: 20px; 
        }

        .sidebar-filtros{
            width: 100%;
            flex: 1 1 auto;
            position: static;
        }

        .title-banner-trabajo h1{ 
            font-size: 1.7rem; 
        }

        .lista-categorias-ui{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2px 12px;
            margin-bottom: 18px;
        }
    }

    @media (max-width: 560px){
        .contenedor-buscador{ 
            flex-direction: column; 
        }

        .btn-buscar{ 
            padding: 13px; 
        }

        .lista-categorias-ui{ 
            grid-template-columns: 1fr; 
        }
    }

    /* ===================== ZONA TABLET INTERMEDIA ===================== */
    @media (max-width: 1024px){
        .wrapper-busqueda{ gap: 20px; padding: 24px 20px 36px; }
    }

    @media (max-width: 700px){
        .title-banner-trabajo h1{ font-size: 1.7rem; }
        .title-banner-trabajo p{ font-size: .95rem; }
        .titulo-resultados{ font-size: 1.2rem; }
    }

    @media (max-width: 400px){
        .grid-anuncios-layout{ grid-template-columns: 1fr; gap: 14px; }
    }
</style>
