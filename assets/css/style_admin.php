<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root{
        --a-bg:      #f4f7f3;   /* mismo fondo mist del sitio */
        --a-panel:   #ffffff;   /* cards y sidebar */
        --a-panel-2: #f1f5f9;   /* hover suave */
        --a-border:  #e4e9e4;
        --a-text:    #15231a;   /* tinta de marca */
        --a-muted:   #64748b;
        --a-accent:  #2563eb;   /* azul profesional (igual que páginas legales) */
        --a-accent-d:#1d4ed8;
        --a-success: #16a34a;
        --a-danger:  #dc2626;
        --a-warning: #d97706;
        --a-blue:    #2563eb;
    }

    *{ margin:0; padding:0; box-sizing:border-box; }

    .admin-body{
        font-family:'Inter',system-ui,Arial,sans-serif;
        background:var(--a-bg);
        color:var(--a-text);
        min-height:100vh;
        display:flex;
    }

    /* ===================== SIDEBAR ===================== */
    .admin-sidebar{
        width:230px;
        flex:0 0 230px;
        background:var(--a-panel);
        border-right:1px solid var(--a-border);
        min-height:100vh;
        position:sticky;
        top:0;
        display:flex;
        flex-direction:column;
        padding:20px 0;
    }
    .admin-logo{ padding:0 20px 20px; border-bottom:1px solid var(--a-border); }
    .admin-logo img{ width:150px; display:block; }
    .admin-logo span{ display:block; margin-top:8px; font-size:11px; color:var(--a-muted); letter-spacing:1px; text-transform:uppercase; font-weight:600; }

    .admin-nav{ list-style:none; padding:16px 12px; flex:1; }
    .admin-nav li{ margin-bottom:4px; }
    .admin-nav a{
        display:flex; align-items:center; gap:12px;
        padding:11px 14px; border-radius:10px;
        color:var(--a-muted); text-decoration:none;
        font-size:14px; font-weight:500;
        transition:background .15s ease, color .15s ease;
    }
    .admin-nav a i{ width:18px; text-align:center; font-size:16px; }
    .admin-nav a:hover{ background:var(--a-panel-2); color:var(--a-text); }
    .admin-nav a.activo{ background:var(--a-accent); color:#fff; }
    .admin-nav .badge-pendiente{
        margin-left:auto; background:var(--a-danger); color:#fff;
        font-size:11px; font-weight:700; padding:2px 8px; border-radius:999px;
    }

    .admin-sidebar-footer{ padding:16px 20px 0; border-top:1px solid var(--a-border); }
    .admin-sidebar-footer a{ color:var(--a-muted); text-decoration:none; font-size:13px; display:flex; align-items:center; gap:8px; margin-bottom:10px; }
    .admin-sidebar-footer a:hover{ color:var(--a-accent); }
    .admin-sidebar-footer .admin-name{ font-size:13px; color:var(--a-text); font-weight:600; }

    /* ===================== MAIN ===================== */
    .admin-main{ flex:1; padding:28px 32px; min-width:0; }
    .admin-header{ margin-bottom:26px; }
    .admin-header h1{ font-size:26px; font-weight:800; letter-spacing:-.5px; }
    .admin-header p{ color:var(--a-muted); font-size:14px; margin-top:4px; }

    /* ===================== KPI CARDS ===================== */
    .kpi-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(190px, 1fr));
        gap:18px;
        margin-bottom:28px;
    }
    .kpi-card{
        background:var(--a-panel);
        border:1px solid var(--a-border);
        border-radius:14px;
        padding:20px;
        display:flex;
        align-items:center;
        gap:16px;
        box-shadow:0 1px 3px rgba(0,0,0,.04);
        transition:transform .15s ease, box-shadow .15s ease;
    }
    .kpi-card:hover{ transform:translateY(-3px); box-shadow:0 12px 24px rgba(12,90,44,.10); }
    .kpi-icon{
        width:48px; height:48px; border-radius:12px;
        display:flex; align-items:center; justify-content:center;
        font-size:20px; color:#fff; flex-shrink:0;
    }
    .kpi-num{ font-size:26px; font-weight:800; line-height:1; }
    .kpi-label{ font-size:12.5px; color:var(--a-muted); margin-top:4px; }

    /* ===================== GRÁFICAS ===================== */
    .chart-grid{ display:grid; grid-template-columns:2fr 1fr; gap:18px; }
    .chart-card{
        background:var(--a-panel);
        border:1px solid var(--a-border);
        border-radius:14px;
        padding:20px;
        box-shadow:0 1px 3px rgba(0,0,0,.04);
    }
    .chart-card h3{ font-size:15px; font-weight:600; margin-bottom:16px; }

    /* ===================== TOOLBAR ===================== */
    .admin-toolbar{
        display:flex; align-items:center; gap:12px; flex-wrap:wrap;
        margin-bottom:18px;
    }
    .admin-toolbar input[type="text"], .admin-toolbar select{
        background:var(--a-panel);
        border:1px solid var(--a-border);
        color:var(--a-text);
        padding:10px 14px;
        border-radius:10px;
        font-size:14px;
        font-family:inherit;
        outline:none;
        transition:border-color .15s ease, box-shadow .15s ease;
    }
    .admin-toolbar input[type="text"]{ min-width:240px; }
    .admin-toolbar input:focus, .admin-toolbar select:focus{ border-color:var(--a-accent); box-shadow:0 0 0 3px rgba(37,99,235,.12); }
    .admin-toolbar button{
        background:var(--a-accent); color:#fff; border:none;
        padding:10px 20px; border-radius:10px; font-weight:600; font-size:14px;
        cursor:pointer; font-family:inherit;
        transition:background .15s ease;
    }
    .admin-toolbar button:hover{ background:var(--a-accent-d); }
    .admin-toolbar .limpiar{ color:var(--a-muted); text-decoration:none; font-size:13px; }

    /* Tabs (reportes) */
    .admin-tabs{ display:flex; gap:8px; margin-bottom:18px; flex-wrap:wrap; }
    .admin-tabs a{
        padding:8px 16px; border-radius:999px; text-decoration:none;
        font-size:13px; font-weight:600; color:var(--a-muted);
        background:var(--a-panel); border:1px solid var(--a-border);
    }
    .admin-tabs a:hover{ border-color:var(--a-accent); color:var(--a-accent); }
    .admin-tabs a.activo{ background:var(--a-accent); color:#fff; border-color:var(--a-accent); }

    /* ===================== TABLA ===================== */
    .admin-table-wrap{
        background:var(--a-panel);
        border:1px solid var(--a-border);
        border-radius:14px;
        overflow-x:auto;
        box-shadow:0 1px 3px rgba(0,0,0,.04);
    }
    table.admin-table{ width:100%; border-collapse:collapse; font-size:13.5px; }
    .admin-table th{
        text-align:left; padding:14px 16px;
        color:var(--a-muted); font-weight:600; font-size:12px;
        text-transform:uppercase; letter-spacing:.5px;
        border-bottom:1px solid var(--a-border);
        white-space:nowrap;
        background:#fafbfc;
    }
    .admin-table td{ padding:13px 16px; border-bottom:1px solid var(--a-border); vertical-align:middle; }
    .admin-table tr:last-child td{ border-bottom:none; }
    .admin-table tbody tr:hover{ background:var(--a-panel-2); }
    .admin-table .avatar-mini{ width:36px; height:36px; border-radius:50%; object-fit:cover; }

    /* Badges de estado (texto oscuro para contraste en fondo claro) */
    .badge{ display:inline-block; padding:4px 10px; border-radius:999px; font-size:11.5px; font-weight:700; }
    .badge-verde{ background:#dcfce7; color:#15803d; }
    .badge-gris{ background:#f1f5f9; color:#475569; }
    .badge-rojo{ background:#fee2e2; color:#b91c1c; }
    .badge-amarillo{ background:#fef3c7; color:#b45309; }
    .badge-azul{ background:#dbeafe; color:#1d4ed8; }
    .badge-naranja{ background:#ffedd5; color:#c2410c; }

    /* Botones de acción en tabla */
    .btn-accion{
        background:var(--a-panel); color:var(--a-text);
        border:1px solid var(--a-border); border-radius:8px;
        padding:6px 12px; font-size:12.5px; font-weight:600;
        cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px;
        font-family:inherit;
        transition:background .15s ease, border-color .15s ease;
    }
    .btn-accion:hover{ background:var(--a-panel-2); border-color:var(--a-accent); }
    .btn-accion.peligro{ color:var(--a-danger); border-color:#fecaca; }
    .btn-accion.peligro:hover{ background:#fee2e2; border-color:var(--a-danger); }
    .btn-accion.exito{ color:var(--a-success); border-color:#bbf7d0; }
    .btn-accion.exito:hover{ background:#dcfce7; border-color:var(--a-success); }
    .admin-table td .acciones{ display:flex; gap:6px; flex-wrap:wrap; align-items:center; }

    /* Mensajes de estado */
    .admin-alert{ padding:12px 18px; border-radius:10px; margin-bottom:18px; font-weight:600; font-size:14px; }
    .admin-alert.ok{ background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; }
    .admin-alert.err{ background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; }
    .admin-alert.warn{ background:#fef3c7; color:#b45309; border:1px solid #fde68a; }

    /* Paginación */
    .admin-paginacion{ display:flex; gap:6px; margin-top:20px; justify-content:center; flex-wrap:wrap; }
    .admin-paginacion a, .admin-paginacion span{
        padding:8px 13px; border-radius:8px; text-decoration:none; font-size:13px;
        background:var(--a-panel); border:1px solid var(--a-border); color:var(--a-muted);
    }
    .admin-paginacion a:hover{ border-color:var(--a-accent); color:var(--a-accent); }
    .admin-paginacion .actual{ background:var(--a-accent); color:#fff; border-color:var(--a-accent); }

    .admin-vacio{ text-align:center; padding:50px 20px; color:var(--a-muted); }
    .admin-vacio i{ font-size:40px; margin-bottom:12px; display:block; color:#cbd5e1; }

    /* ===================== CATEGORÍAS GRID ===================== */
    .cat-grid{ display:grid; grid-template-columns:repeat(auto-fill, minmax(220px,1fr)); gap:18px; }
    .cat-card{
        background:var(--a-panel); border:1px solid var(--a-border); border-radius:14px;
        padding:20px; text-align:center; box-shadow:0 1px 3px rgba(0,0,0,.04);
        transition:transform .15s ease, box-shadow .15s ease;
    }
    .cat-card:hover{ transform:translateY(-3px); box-shadow:0 12px 24px rgba(12,90,44,.10); }
    .cat-card img{ width:70px; height:70px; object-fit:contain; margin-bottom:12px; }
    .cat-card h4{ font-size:16px; margin-bottom:6px; }
    .cat-card .cat-count{ font-size:12.5px; color:var(--a-muted); margin-bottom:14px; }
    .cat-card .cat-acciones{ display:flex; gap:8px; justify-content:center; }

    /* ===================== MODAL ===================== */
    .admin-modal-overlay{
        display:none; position:fixed; inset:0; z-index:1000;
        background:rgba(15,23,42,.45); align-items:center; justify-content:center; padding:20px;
    }
    .admin-modal-overlay.abierto{ display:flex; }
    .admin-modal{
        background:var(--a-panel); border:1px solid var(--a-border); border-radius:16px;
        padding:26px; max-width:440px; width:100%;
        box-shadow:0 20px 50px rgba(0,0,0,.2);
        animation:modalIn .2s ease;
    }
    @keyframes modalIn{ from{ opacity:0; transform:translateY(20px);} to{ opacity:1; transform:translateY(0);} }
    .admin-modal h3{ font-size:19px; margin-bottom:8px; }
    .admin-modal p{ color:var(--a-muted); font-size:14px; margin-bottom:20px; line-height:1.5; }
    .admin-modal label{ display:block; font-size:13px; color:var(--a-muted); margin:12px 0 6px; font-weight:600; }
    .admin-modal input[type="text"], .admin-modal input[type="file"], .admin-modal textarea{
        width:100%; background:var(--a-bg); border:1px solid var(--a-border); color:var(--a-text);
        padding:10px 14px; border-radius:10px; font-size:14px; font-family:inherit; outline:none;
    }
    .admin-modal input:focus, .admin-modal textarea:focus{ border-color:var(--a-accent); box-shadow:0 0 0 3px rgba(37,99,235,.12); }
    .admin-modal-acciones{ display:flex; gap:10px; justify-content:flex-end; margin-top:22px; }
    .btn-modal{ padding:10px 20px; border-radius:10px; font-weight:600; font-size:14px; cursor:pointer; border:none; font-family:inherit; }
    .btn-modal.cancelar{ background:var(--a-panel-2); color:var(--a-text); border:1px solid var(--a-border); }
    .btn-modal.confirmar{ background:var(--a-accent); color:#fff; }
    .btn-modal.confirmar.peligro{ background:var(--a-danger); }

    .admin-menu-toggle{ display:none; }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width:900px){
        .chart-grid{ grid-template-columns:1fr; }
    }
    @media (max-width:768px){
        .admin-sidebar{
            position:fixed; left:-240px; z-index:900; transition:left .25s ease; height:100vh;
            box-shadow:0 0 30px rgba(0,0,0,.15);
        }
        .admin-sidebar.abierto{ left:0; }
        .admin-menu-toggle{
            display:inline-flex; align-items:center; justify-content:center;
            background:var(--a-panel); border:1px solid var(--a-border); color:var(--a-text);
            width:44px; height:44px; border-radius:10px; cursor:pointer; margin-bottom:16px; font-size:18px;
        }
        .admin-main{ padding:20px; }
    }
</style>
