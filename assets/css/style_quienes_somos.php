<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

.about-page {
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
    min-height: 100vh;
}

/* ===== HERO SECTION ===== */
.about-hero {
    background: linear-gradient(135deg, #0f2847 0%, #1a3f6f 40%, #2563a8 100%);
    padding: 90px 20px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.about-hero::before {
    content: '';
    position: absolute;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: radial-gradient(ellipse at center, rgba(255,255,255,0.05) 0%, transparent 60%);
    pointer-events: none;
}

.about-hero::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 6px;
    background: linear-gradient(90deg, #f59e0b, #f97316, #ef4444, #8b5cf6, #3b82f6);
}

.about-hero h1 {
    font-size: 48px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 16px;
    letter-spacing: -1px;
}

.about-hero p {
    font-size: 18px;
    color: rgba(255,255,255,0.7);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
    font-weight: 300;
}

/* ===== MAIN CONTAINER ===== */
.about-container {
    max-width: 1200px;
    margin: -40px auto 60px;
    padding: 0 24px;
    position: relative;
    z-index: 2;
}

/* ===== MISSION & VISION ===== */
.mv-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 60px;
}
.mv-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 10px 15px -3px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.04);
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.mv-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
}

.mv-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
}

.mv-card.mission::before { 
    background: linear-gradient(90deg, #3b82f6, #60a5fa); 
}

.mv-card.vision::before { 
    background: linear-gradient(90deg, #8b5cf6, #a78bfa); 
}

.mv-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-bottom: 24px;
}
.mv-card.mission .mv-icon {
    background: #eff6ff;
    color: #3b82f6;
}

.mv-card.vision .mv-icon {
    background: #f5f3ff;
    color: #8b5cf6;
}

.mv-card h2 {
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 16px;
}

.mv-card p {
    font-size: 16px;
    line-height: 1.7;
    color: #475569;
    margin: 0;
}

/* ===== VALORES ===== */
.values-section {
    text-align: center;
    margin-bottom: 80px;
}

.values-section h2 {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 12px;
}

.values-section > p {
    font-size: 16px;
    color: #64748b;
    max-width: 600px;
    margin: 0 auto 40px;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.value-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 32px 24px;
    text-align: center;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: all 0.3s;
}

.value-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.06);
    border-color: rgba(59,130,246,0.2);
}

.value-icon {
    width: 64px;
    height: 64px;
    background: #f8fafc;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #1e3a5f;
    margin: 0 auto 20px;
    transition: all 0.3s;
}

.value-card:hover .value-icon {
    background: #eff6ff;
    color: #3b82f6;
    transform: scale(1.1);
}

.value-card h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 12px;
}

.value-card p {
    font-size: 14px;
    color: #64748b;
    line-height: 1.6;
    margin: 0;
}

/* ===== CTA FINAL ===== */
.about-cta {
    background: #1e3a5f;
    border-radius: 24px;
    padding: 60px 40px;
    text-align: center;
    position: relative;
    overflow: hidden;
    color: #fff;
}

.about-cta::before {
    content: '';
    position: absolute;
    right: -10%; bottom: -20%;
    width: 300px; height: 300px;
    background: rgba(59,130,246,0.15);
    border-radius: 50%;
    filter: blur(40px);
}

.about-cta h2 {
    font-size: 32px;
    font-weight: 800;
    margin: 0 0 16px;
    position: relative;
    z-index: 2;
}

.about-cta p {
    font-size: 18px;
    color: rgba(255,255,255,0.7);
    margin: 0 auto 32px;
    max-width: 500px;
    position: relative;
    z-index: 2;
}

.cta-buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
    position: relative;
    z-index: 2;
}

.cta-btn-primary {
    background: #3b82f6;
    color: #fff;
    padding: 14px 32px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}

.cta-btn-primary:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37,99,235,0.3);
}

.cta-btn-outline {
    background: transparent;
    color: #fff;
    border: 2px solid rgba(255,255,255,0.3);
    padding: 12px 32px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}

.cta-btn-outline:hover {
    border-color: #fff;
    background: rgba(255,255,255,0.1);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .mv-grid {
        grid-template-columns: 1fr;
    }
    
    .values-grid {
        grid-template-columns: 1fr 1fr;
    }

    .about-hero h1 { 
        font-size: 36px; 
    }
}
@media (max-width: 600px) {
    .values-grid {
        grid-template-columns: 1fr;
    }

    .cta-buttons {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .about-hero { padding: 60px 18px; }
    .about-hero h1 { font-size: 30px; }
    .about-hero p { font-size: 16px; }
    .mv-card { padding: 26px 20px; }
    .values-section h2 { font-size: 26px; }
    .about-cta { padding: 40px 20px; }
    .about-cta h2 { font-size: 26px; }
    .about-cta p { font-size: 16px; }
}
</style>
