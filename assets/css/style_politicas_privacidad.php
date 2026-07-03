<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

:root {

    --bg-color: #f8fafc;
    --card-bg: #ffffff;
    --text-color: #475569;
    --text-muted: #64748b;
    --text-title: #0f172a;
    --border-color: #e2e8f0;
    --primary-color: #2563eb;
    --primary-hover: #1d4ed8;
    --primary-light: #eff6ff;
    --header-bg: rgba(255, 255, 255, 0.85);
    --sidebar-active-bg: #eff6ff;
    --sidebar-active-color: #2563eb;
    --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    --shadow-hover: 0 20px 30px -10px rgba(37, 99, 235, 0.15);
    --transition-speed: 0.3s;
    --font-stack: 'Inter', sans-serif;
    --max-width: 1200px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: var(--font-stack);
    scroll-behavior: smooth;
}

body {
    background-color: var(--bg-color);
    color: var(--text-color);
    line-height: 1.6;
}

::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    background: var(--bg-color);
}
::-webkit-scrollbar-thumb {
    background: var(--text-muted);
    border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
    background: var(--primary-color);
}


.banner {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #0f2847 0%, #1a3f6f 40%, #2563a8 100%);
    padding: 90px 20px 110px;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.banner::before {
    content: '';
    position: absolute;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: radial-gradient(ellipse at center, rgba(255,255,255,0.05) 0%, transparent 60%);
    pointer-events: none;
}

.banner::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 6px;
    background: linear-gradient(90deg, #f59e0b, #f97316, #ef4444, #8b5cf6, #3b82f6);
}

.banner-contenido {
    position: relative;
    z-index: 10;
    width: 90%;
    max-width: 800px;
    color: #ffffff;
    animation: fadeInUp 0.8s ease-out;
}

.banner-contenido h1 {
    font-size: 48px;
    font-weight: 800;
    margin-bottom: 15px;
    letter-spacing: -1px;
    color: #ffffff;
}

.banner-contenido p {
    font-size: 18px;
    color: rgba(255,255,255,0.7);
    font-weight: 300;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}


.contenedor {
    max-width: var(--max-width);
    margin: -60px auto 80px auto;
    padding: 0 20px;
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 40px;
}


.sidebar {
    position: sticky;
    top: 100px;
    height: fit-content;
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
}

.sidebar h3 {
    font-size: 16px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-title);
    margin-bottom: 16px;
    letter-spacing: 0.5px;
    border-left: 3px solid var(--primary-color);
    padding-left: 10px;
}

.sidebar-nav {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.sidebar-nav-item a {
    display: block;
    padding: 10px 14px;
    border-radius: 8px;
    color: var(--text-color);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all var(--transition-speed);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar-nav-item a:hover {
    color: var(--primary-color);
    background-color: var(--primary-light);
    transform: translateX(4px);
}

.sidebar-nav-item.active a {
    color: var(--sidebar-active-color);
    background-color: var(--sidebar-active-bg);
    font-weight: 600;
}


.contenido-principal {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.card {
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 35px;
    border-radius: 16px;
    box-shadow: var(--shadow);
    transition: all var(--transition-speed);
    animation: fadeInUp 0.6s ease-out;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-hover);
    border-color: rgba(37, 99, 235, 0.15);
}

.card h2 {
    color: var(--text-title);
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card h2::before {
    content: '';
    display: inline-block;
    width: 6px;
    height: 22px;
    background-color: var(--primary-color);
    border-radius: 4px;
}

.card p {
    color: var(--text-color);
    line-height: 1.8;
    margin-bottom: 20px;
    text-align: justify;
    font-size: 15px;
}

.card p:last-child {
    margin-bottom: 0;
}

.card ul {
    list-style: none;
    margin-bottom: 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.card ul li {
    position: relative;
    padding-left: 24px;
    font-size: 14px;
    color: var(--text-color);
}

.card ul li::before {
    content: '✓';
    position: absolute;
    left: 0;
    top: 0;
    color: var(--primary-color);
    font-weight: bold;
}


.table-container {
    overflow-x: auto;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    margin-top: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 14px;
}

table th {
    background-color: var(--primary-light);
    color: var(--sidebar-active-color);
    padding: 16px;
    font-weight: 600;
}

table td {
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-color);
}

table tr:last-child td {
    border-bottom: none;
}


@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}


@media (max-width: 1024px) {
    .contenedor {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .sidebar {
        position: static;
        width: 100%;
        overflow-x: auto;
    }
    
    .sidebar h3 {
        display: none;
    }

    .sidebar-nav {
        flex-direction: row;
        gap: 12px;
        padding-bottom: 5px;
    }

    .sidebar-nav-item a {
        padding: 8px 16px;
        background-color: var(--bg-color);
    }
}

@media (max-width: 768px) {
    header {
        flex-direction: column;
        gap: 15px;
        padding: 15px 20px;
        text-align: center;
    }

    nav ul {
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }

    .banner {
        height: auto;
        padding: 60px 20px 80px;
    }

    .banner-contenido h1 {
        font-size: 34px;
    }

    .banner-contenido p {
        font-size: 15px;
    }

    .card {
        padding: 24px;
    }

    .card ul {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .banner { padding: 60px 18px 80px; height: auto; }
    .banner-contenido h1 { font-size: 28px; }
    .banner-contenido p { font-size: 14px; }
    .contenedor { padding: 0 16px; margin-top: -40px; }
    .card { padding: 20px 18px; }
    .card h2 { font-size: 19px; }
}

</style>