<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

.terms-page {
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
    min-height: 100vh;
}

/* Barra de progreso de lectura */
.reading-progress {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: transparent;
    z-index: 1100;
}

.reading-progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #f59e0b, #f97316, #ef4444, #8b5cf6, #3b82f6);
    transition: width 0.1s linear;
}

.terms-hero {
    background: linear-gradient(135deg, #0f2847 0%, #1a3f6f 40%, #2563a8 100%);
    padding: 80px 40px 60px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.terms-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(ellipse at 30% 50%, rgba(255,255,255,0.04) 0%, transparent 60%);
    pointer-events: none;
}

.terms-hero::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, #f59e0b, #f97316, #ef4444, #8b5cf6, #3b82f6);
}

.terms-hero .breadcrumb {
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    margin-bottom: 20px;
    letter-spacing: 0.5px;
}

.terms-hero .breadcrumb a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: color 0.3s;
}

.terms-hero .breadcrumb a:hover {
    color: #fff;
}

.terms-hero h1 {
    font-size: 42px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 12px;
    letter-spacing: -1px;
}

.terms-hero p {
    font-size: 17px;
    color: rgba(255,255,255,0.65);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
    font-weight: 300;
}

.terms-hero .last-updated {
    display: inline-block;
    margin-top: 24px;
    padding: 6px 16px;
    background: rgba(255,255,255,0.1);
    border-radius: 20px;
    color: rgba(255,255,255,0.6);
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 0.5px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.08);
}

.terms-layout {
    display: flex;
    max-width: 1200px;
    margin: -30px auto 60px;
    padding: 0 24px;
    gap: 36px;
    position: relative;
    z-index: 2;
}

.terms-sidebar {
    flex: 0 0 280px;
    position: sticky;
    top: 24px;
    align-self: flex-start;
}
.terms-sidebar-card {
    background: #fff;
    border-radius: 16px;
    padding: 28px 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.04);
    border: 1px solid rgba(0,0,0,0.04);
}

.terms-sidebar-card h3 {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #94a3b8;
    margin: 0 0 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
}

.terms-sidebar-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.terms-sidebar-card li {
    margin-bottom: 4px;
}

.terms-sidebar-card li a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.terms-sidebar-card li a:hover {
    color: #1e3a5f;
    background: #f1f5f9;
}

.terms-sidebar-card li a.active {
    color: #1e3a5f;
    background: #eff6ff;
    font-weight: 600;
}

.terms-sidebar-card li a.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 20px;
    background: linear-gradient(180deg, #3b82f6, #1d4ed8);
    border-radius: 0 4px 4px 0;
}

.terms-sidebar-card li a .sidebar-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 14px;
    background: #f8fafc;
    color: #94a3b8;
    transition: all 0.25s;
    flex-shrink: 0;
}

.terms-sidebar-card li a:hover .sidebar-icon,
.terms-sidebar-card li a.active .sidebar-icon {
    background: #dbeafe;
    color: #2563eb;
}

.terms-content {
    flex: 1;
    min-width: 0;
}

.terms-card {
    background: #fff;
    border-radius: 16px;
    padding: 48px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.04);
    border: 1px solid rgba(0,0,0,0.04);
}

.terms-section {
    padding-bottom: 40px;
    margin-bottom: 40px;
    border-bottom: 1px solid #f1f5f9;
    scroll-margin-top: 30px;
    animation: termsFadeIn 0.5s ease both;
}

@keyframes termsFadeIn {
    from { 
        opacity: 0; 
        transform: translateY(16px); 
    }to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.terms-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.terms-section .section-head {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 16px;
}

.terms-section .section-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: #fff;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(37,99,235,0.25);
}

.terms-section h2 {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.3px;
}

.terms-section p {
    font-size: 15px;
    line-height: 1.8;
    color: #475569;
    margin: 0 0 14px;
}

.terms-section .highlight-box {
    background: linear-gradient(135deg, #eff6ff, #f0f9ff);
    border-left: 4px solid #3b82f6;
    border-radius: 0 12px 12px 0;
    padding: 20px 24px;
    margin: 20px 0;
}

.terms-section .highlight-box p {
    margin: 0;
    color: #1e40af;
    font-weight: 500;
    font-size: 14px;
}

.terms-section .highlight-box.warning {
    background: linear-gradient(135deg, #fef2f2, #fff7ed);
    border-left-color: #ef4444;
}

.terms-section .highlight-box.warning p {
    color: #b91c1c;
}

/* Lista de recomendaciones con check */
.terms-section .check-list {
    list-style: none;
    padding: 0;
    margin: 8px 0 18px;
}

.terms-section .check-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 15px;
    line-height: 1.7;
    color: #475569;
    padding: 10px 16px;
    margin-bottom: 8px;
    background: #f8fafc;
    border-radius: 10px;
    transition: background 0.2s, transform 0.2s;
}

.terms-section .check-list li:hover {
    background: #f0fdf4;
    transform: translateX(4px);
}

.terms-section .check-list li i {
    color: #16a34a;
    font-size: 16px;
    margin-top: 3px;
    flex-shrink: 0;
}

@media (max-width: 900px) {
    .terms-layout {
        flex-direction: column;
    }

    .terms-sidebar {
        flex: none;
        position: static;
    }

    .terms-sidebar-card ul {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .terms-sidebar-card li a {
        padding: 8px 14px;
        font-size: 13px;
    }

    .terms-sidebar-card li a .sidebar-icon {
        display: none;
    }

    .terms-card {
        padding: 28px 20px;
    }
    
    .terms-hero h1 {
        font-size: 28px;
    }
}

@media (max-width: 480px) {
    .terms-hero { padding: 60px 18px 70px; }
    .terms-hero h1 { font-size: 24px; }
    .terms-card { padding: 24px 16px; }
    .terms-section h2 { font-size: 19px; }
    .terms-section .section-number { width: 32px; height: 32px; font-size: 13px; }
}
</style>
