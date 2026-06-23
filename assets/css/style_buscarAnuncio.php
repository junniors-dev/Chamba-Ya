<style>
    body {
    font-family: sans-serif;
    background: #f6f8fb;
    margin: 0;
    padding: 0;
}

/* BANNER PRINCIPAL */
.title-banner-trabajo {
    text-align: center;
    background: linear-gradient(135deg, #d4edda, #b7e4c7);
    padding: 25px 15px;
    font-size: 2rem;
    font-weight: 700;
    color: #1f3d2a;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #a3d9b1;
}

/* CONTENEDOR GENERAL */
.wrapper-busqueda {
    display: flex;
    min-height: calc(100vh - 120px);
    background: #f6f8fb;
}

/* SIDEBAR */
.sidebar-filtros {
    width: 25%;
    background: #e9f7ef;
    padding: 30px 20px;
    box-sizing: border-box;
    border-right: 1px solid #dcefe3;
}


/* MAIN */
.main-feed-trabajos{
    width:75%;
    padding:30px 40px;
}

/* TITULOS SIDEBAR */
.sidebar-filtros h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f3d2a;
}

/* CATEGORIAS */
.lista-categorias-ui{
    list-style:none;
    padding:0;
    margin:0 0 30px 0;
}

.item-categoria-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 8px;
    border-radius: 8px;
    transition: 0.2s;
    cursor: pointer;
    font-size: 0.95rem;
}

.item-categoria-label:hover {
    background: #d4edda;
}

/* SELECTS */
.select-filtro-geo {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #cfe3d6;
    margin-bottom: 15px;
    font-size: 0.9rem;
    outline: none;
    transition: 0.2s;
}

.contenedor-filtro-precio{
    margin-top:25px;
    padding-top:20px;
    border-top:1px solid #ced4da;
}

.info-precio{
    display:flex;
    justify-content:space-between;
    font-size:.9rem;
    color:#444;
    margin-bottom:5px;
}

.indicador-precio{
    font-weight:bold;
    color:#007bff;
}

/* SLIDER */
.slider-precio{
    width:100%;
    cursor:pointer;
}

.limpiar-filtros{
    display:block;
    text-align:center;
    color:#dc3545;
    font-size:.9rem;
    margin-top:25px;
    text-decoration:none;
    font-weight:bold;
}


/* BUSCADOR */
.contenedor-buscador{
    display:flex;
    gap:15px;
    margin-bottom:35px;
}

.contenedor-input-buscador{
    position:relative;
    flex-grow:1;
}

.input-buscador{
    width:100%;
    padding:14px 20px;
    border-radius:30px;
    border:1px solid #ced4da;
    font-size:.95rem;
    box-sizing:border-box;
}

/* BOTON */
.btn-buscar {
    background: #2e5a3c;
    color: white;
    border: none;
    padding: 0 35px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}


.resultados-header{
    margin-bottom:25px;
}

.titulo-resultados{
    margin:0;
    font-size:1.35rem;
    font-weight:bold;
    color:#111;
}

.texto-resultados{
    margin:5px 0 0;
    color:#7f7f7f;
    font-size:.95rem;
}

.grid-anuncios-layout{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
    gap:25px;
    margin-top:20px;
}

/* CARDS */
.card-trabajo-ui{
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:12px;
    padding:22px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    box-shadow:0 4px 6px rgba(0,0,0,.02);
}

.titulo-card{
    font-size:1.3rem;
    font-weight:bold;
    margin:0 0 8px;
    color:#111;
}

.ubicacion-card{
    font-size:.95rem;
    color:#555;
    margin:0 0 6px;
    font-weight:500;
}

.pago-card{
    font-size:.95rem;
    color:#7f7f7f;
    margin:0 0 15px;
}

.btn-ver-mas-card{
    background-color:#2e5a3c;
    color:white;
    text-align:center;
    padding:8px 24px;
    border-radius:20px;
    text-decoration:none;
    font-size:.85rem;
    font-weight:500;
    align-self:flex-end;
    border:none;
    cursor:pointer;
}

.sin-resultados{
    grid-column:1 / -1;
    text-align:center;
    padding:40px;
    color:#7f7f7f;
}

.sin-resultados p{
    font-size:1.1rem;
}

/*==========================
COLORES MODO TRABAJO (VERDE)
===========================*/
.title-banner-trabajo.verde{
    background: linear-gradient(135deg,#d4edda,#b7e4c7);
    color:#1f3d2a;
    border-bottom:2px solid #a3d9b1;
}

.sidebar-filtros.verde{
    background:#e9f7ef;
    border-right:1px solid #dcefe3;
}

.verde .btn-buscar,
.btn-ver-mas-card{
    background:#2e5a3c;
}

.verde .item-categoria-label:hover{
    background:#d4edda;
}


/*=============================
COLORES BRINDAR SERVICIOS
==============================*/
.title-banner-trabajo.amarillo{
    background: linear-gradient(135deg,#fff7cc,#ffe9a8);
    color:#6d5b00;
    border-bottom:2px solid #f3db84;
}

.sidebar-filtros.amarillo{
    background:#fffdf0;
    border-right:1px solid #f5e7a4;
}

.amarillo .item-categoria-label:hover{
    background:#fff3bf;
}

.amarillo .btn-buscar{
    background:#d8a500;
    color:white;
}

.amarillo .btn-buscar:hover{
    background:#c39300;
}

/* Botones de las tarjetas de servicios */
.card-servicio{
    background:#fff;
    border:1px solid #ececec;
    border-radius:12px;
    padding:22px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    box-shadow:0 4px 6px rgba(0,0,0,.02);
}

.titulo-servicio{
    font-size:1.3rem;
    margin:0 0 10px;
    color:#222;
}

.nombre-servicio{
    color:#444;
    font-size:.95rem;
    margin-bottom:8px;
}

.ubicacion-servicio{
    color:#666;
    font-size:.9rem;
    margin-bottom:12px;
}

.estrellas{
    color:#f0b800;
    font-size:1.2rem;
    margin-bottom:18px;
}

.btn-ver-mas-servicio{
    background:#d8a500;
    color:white;
    text-decoration:none;
    padding:8px 24px;
    border-radius:20px;
    font-size:.85rem;
    font-weight:500;
    align-self:flex-end;
    transition:.2s;
}

.btn-ver-mas-servicio:hover{
    background:#c39300;
}
.seccion-testimonios {
    margin-top: 40px;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.03);
}

.seccion-testimonios h2 {
    font-size: 1.5rem;
    margin-bottom: 20px;
    color: #333;
    border-bottom: 2px solid #ffe9a8; /* Detalle amarillo */
    padding-bottom: 8px;
}

.lista-testimonios {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.tarjeta-testimonio {
    border-bottom: 1px solid #f1f1f1;
    padding-bottom: 15px;
}

.tarjeta-testimonio:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.testimonio-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.usuario-info strong {
    color: #222;
    font-size: 1.05rem;
}

.testimonio-fecha {
    font-size: 0.85rem;
    color: #888;
    margin-left: 10px;
}

.estrellas i {
    font-size: 0.95rem;
    margin-left: 2px;
}

.testimonio-cuerpo p {
    font-size: 1rem;
    color: #555;
    line-height: 1.4;
}

.sin-testimonios {
    color: #777;
    font-style: italic;
}
</style>