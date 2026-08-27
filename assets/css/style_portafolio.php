<?php
// Estilos del portafolio de trabajos.
// Van en su propio archivo porque los usan dos vistas con hojas distintas:
// mis_datos.php (style_profile) y perfil_publico.php (style_detalleServicio).
?>
<style>
/* ===== Portafolio de trabajos ===== */
.portafolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    gap: 14px;
    margin-top: 14px;
}
.portafolio-item {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
    display: flex;
    flex-direction: column;
}
.portafolio-item img {
    width: 100%;
    height: 130px;
    object-fit: cover;
    display: block;
    background: #f1f5f9;
}
.portafolio-sinimg {
    height: 130px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 26px;
}
.portafolio-cuerpo { padding: 10px 12px; flex: 1; }
.portafolio-cuerpo h4 { margin: 0 0 4px; font-size: 14px; color: #0f172a; }
.portafolio-cuerpo p { margin: 0; font-size: 12.5px; color: #64748b; line-height: 1.45; }
.portafolio-cat {
    display: inline-block;
    margin-top: 8px;
    padding: 2px 8px;
    border-radius: 999px;
    background: #eef2ff;
    color: #4f46e5;
    font-size: 11px;
    font-weight: 600;
}
.portafolio-pie {
    padding: 8px 12px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
}
.portafolio-eliminar {
    border: 0;
    background: transparent;
    color: #dc2626;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}
.portafolio-vacio {
    margin-top: 12px;
    padding: 18px;
    border: 1px dashed #cbd5e1;
    border-radius: 10px;
    color: #64748b;
    text-align: center;
    font-size: 13.5px;
}
</style>
