<style>
        body {
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .container-detalle {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .titulo-seccion-detalle {
            text-align: center;
            font-size: 1.1rem;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .main-titulo-anuncio {
            font-size: 2.4rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: #111;
            text-align: left;
        }

        /* Layout Principal en Flexbox */
        .layout-flex-detalle {
            display: flex;
            gap: 30px;
            align-items: flex-start;
        }

        /* Columna Izquierda (Información del Anuncio) */
        .bloque-izquierdo {
            flex: 2.3;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .fila-encabezados-mini {
            display: flex;
            gap: 20px;
        }

        .caja-info-mini {
            flex: 1;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 18px 24px;
            font-size: 1.25rem;
            color: #444;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            font-weight: 500;
        }

        .caja-info-mini strong {
            color: #111;
            font-weight: 700;
        }

        .tarjeta-principal-cuerpo {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .subtitulo-cuerpo {
            color: #666;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .descripcion-texto {
            font-size: 1.15rem;
            line-height: 1.7;
            color: #2c3e50;
            margin-bottom: 30px;
            white-space: pre-line;
        }

        .divisor-linea {
            border: 0;
            border-top: 1px solid #eaeded;
            margin: 25px 0;
        }

        /* Grid de Detalles Secundarios */
        .grid-detalles-secundarios {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 5px;
        }

        .item-secundario {
            display: flex;
            flex-direction: column;
        }

        .item-secundario .label {
            color: #7f8c8d;
            font-size: 0.95rem;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .item-secundario .valor {
            font-size: 1.1rem;
            color: #1a252f;
            font-weight: 600;
        }

        /* Categorías / Tags */
        .contenedor-tags-categorias {
            margin-top: 10px;
            margin-bottom: 35px;
        }

        .lista-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .tag-categoria {
            background: #e8eaf6;
            color: #3f51b5;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            border: 1px solid #c5cae9;
        }

        /* Botones de Acción */
        .bloque-acciones {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .btn-postular {
            flex: 2.2;
            background: #007bff;
            color: #ffffff;
            border: none;
            padding: 16px;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }

        .btn-postular:hover {
            background: #0056b3;
        }

        .btn-favorito {
            flex: 1;
            background: #f8f9fa;
            color: #495057;
            border: 1px solid #ced4da;
            padding: 16px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-favorito:hover {
            background: #e2e6ea;
            color: #212529;
        }

        /* Columna Derecha (Perfil del Ofertante) */
        .bloque-derecho-perfil {
            flex: 1;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 30px 24px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 20px;
        }

        .titulo-perfil-card {
            font-size: 1rem;
            color: #7f8c8d;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nombre-usuario-card {
            font-size: 1.35rem;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1.3;
            margin-bottom: 15px;
        }

        .datos-contacto-card {
            font-size: 1rem;
            color: #555;
            margin: 12px 0;
            text-align: left;
            padding-left: 5px;
        }

        .datos-contacto-card strong {
            color: #333;
            font-weight: 600;
        }

        .avatar-perfil-img {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 20px;
            border: 3px solid #007bff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .layout-flex-detalle {
                flex-direction: column;
            }
            .bloque-derecho-perfil {
                width: 100%;
                position: static;
                box-sizing: border-box;
            }
            .fila-encabezados-mini {
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>