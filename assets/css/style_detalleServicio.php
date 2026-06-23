<style>
        body {
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #334155;
        }

        .container-servicio {
            max-width: 1150px;
            margin: 40px auto;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .wrapper-layout-servicio {
            display: flex;
            gap: 30px;
            align-items: flex-start;
        }

        /* COLUMNA IZQUIERDA: Tarjeta Perfil */
        .col-perfil-principal {
            flex: 2;
            background: #ffffff;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .encabezado-usuario {
            display: flex;
            align-items: center;
            gap: 25px;
            margin-bottom: 30px;
        }

        .foto-perfil-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ffe9a8; /* Detalle amarillo representativo */
        }

        .info-usuario-titulo h1 {
            margin: 0 0 5px 0;
            font-size: 2rem;
            color: #0f172a;
        }

        .rol-tag {
            background-color: #fef9c3;
            color: #713f12;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            display: inline-block;
        }

        .seccion-bloque-info {
            margin-bottom: 28px;
        }

        .seccion-bloque-info h3 {
            font-size: 1.2rem;
            color: #1e293b;
            margin-bottom: 10px;
            border-left: 4px solid #d8a500;
            padding-left: 10px;
        }

        .seccion-bloque-info p {
            font-size: 1.05rem;
            line-height: 1.6;
            color: #475569;
            margin: 0;
            white-space: pre-line;
        }

        /* Lista de Habilidades y Categorías */
        .contenedor-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .badge-item {
            background: #f1f5f9;
            color: #334155;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            border: 1px solid #e2e8f0;
        }

        .badge-item.categoria {
            background: #fff9db;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        /* COLUMNA DERECHA: Datos de contacto fijos */
        .col-sidebar-datos {
            flex: 1;
            background: #ffffff;
            border-radius: 16px;
            padding: 30px 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            position: sticky;
            top: 20px;
        }

        .precio-box {
            background: #fffdf0;
            border: 1px solid #f5e7a4;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: #6d5b00;
        }

        .precio-box strong {
            font-size: 1.6rem;
            display: block;
            color: #d8a500;
            margin-top: 5px;
        }

        .item-contacto-sidebar {
            margin-bottom: 18px;
        }

        .item-contacto-sidebar span {
            font-size: 0.85rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
        }

        .item-contacto-sidebar p {
            margin: 0;
            font-size: 1.05rem;
            color: #0f172a;
            font-weight: 500;
        }

        .btn-solicitar-servicio {
            width: 100%;
            background: #d8a500;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }

        .btn-solicitar-servicio:hover {
            background: #c39300;
        }

        /* SECCIÓN INFERIOR: Servicios que ofrezco */
        .seccion-otros-servicios {
            margin-top: 45px;
            border-top: 1px solid #e2e8f0;
            padding-top: 35px;
        }

        .seccion-otros-servicios h2 {
            font-size: 1.5rem;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .grid-otros-servicios {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .tarjeta-mini-servicio {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .tarjeta-mini-servicio h4 {
            margin: 0 0 10px 0;
            font-size: 1.2rem;
            color: #1e293b;
        }

        .tarjeta-mini-servicio p {
            font-size: 0.95rem;
            color: #64748b;
            margin: 0 0 15px 0;
            height: 40px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .footer-mini-tarjeta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .btn-ver-detalle-mini {
            background: #f1f5f9;
            color: #1e293b;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid #cbd5e1;
            transition: all 0.2s;
        }

        .btn-ver-detalle-mini:hover {
            background: #e2e8f0;
        }

        
    </style>