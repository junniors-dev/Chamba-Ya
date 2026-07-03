<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

.faq-page {
    font-family: 'Inter', sans-serif;
    background: #f8fafc;
    min-height: 100vh;
}

.faq-hero {
    background: linear-gradient(135deg, #0f2847 0%, #1a3f6f 40%, #2563a8 100%);
    padding: 80px 40px 90px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.faq-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(ellipse at 70% 50%, rgba(255,255,255,0.04) 0%, transparent 60%);
    pointer-events: none;
}

.faq-hero::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, #f59e0b, #f97316, #ef4444, #8b5cf6, #3b82f6);
}

.faq-hero .breadcrumb {
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    margin-bottom: 20px;
    letter-spacing: 0.5px;
}

.faq-hero .breadcrumb a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: color 0.3s;
}

.faq-hero .breadcrumb a:hover {
    color: #fff;
}

.faq-hero h1 {
    font-size: 42px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 12px;
    letter-spacing: -1px;
}

.faq-hero p {
    font-size: 17px;
    color: rgba(255,255,255,0.65);
    max-width: 550px;
    margin: 0 auto 36px;
    line-height: 1.6;
    font-weight: 300;
}

.faq-search-wrapper {
    max-width: 580px;
    margin: 0 auto;
    position: relative;
}

.faq-search-wrapper i {
    position: absolute;
    left: 22px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 18px;
    z-index: 2;
}

.faq-search-wrapper input {
    width: 100%;
    padding: 18px 24px 18px 54px;
    font-size: 16px;
    font-family: 'Inter', sans-serif;
    border: 2px solid rgba(255,255,255,0.15);
    border-radius: 16px;
    outline: none;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(20px);
    color: #fff;
    transition: all 0.3s;
    box-shadow: 0 4px 24px rgba(0,0,0,0.15);
}

.faq-search-wrapper input::placeholder {
    color: rgba(255,255,255,0.45);
}

.faq-search-wrapper input:focus {
    border-color: rgba(255,255,255,0.35);
    background: rgba(255,255,255,0.15);
    box-shadow: 0 4px 32px rgba(0,0,0,0.2);
}

.faq-tabs {
    display: flex;
    justify-content: center;
    gap: 8px;
    max-width: 800px;
    margin: -24px auto 48px;
    position: relative;
    z-index: 2;
    padding: 0 24px;
}

.faq-tab {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 28px;
    background: #fff;
    border-radius: 14px;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 20px rgba(0,0,0,0.04);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    flex: 1;
    justify-content: center;
}

.faq-tab:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08), 0 12px 28px rgba(0,0,0,0.06);
}

.faq-tab.active {
    background: linear-gradient(135deg, #1e3a5f, #1a3f6f);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(30,58,95,0.25), 0 12px 28px rgba(30,58,95,0.15);
}

.faq-tab .tab-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 16px;
    background: #f1f5f9;
    color: #64748b;
    transition: all 0.3s;
}

.faq-tab.active .tab-icon {
    background: rgba(255,255,255,0.15);
    color: #fff;
}

.faq-tab .tab-text small {
    display: block;
    font-size: 11px;
    font-weight: 400;
    opacity: 0.6;
    margin-top: 2px;
}

.faq-main {
    max-width: 820px;
    margin: 0 auto 80px;
    padding: 0 24px;
}

.faq-column-section {
    display: none;
}

.faq-column-section.active {
    display: block;
    animation: fadeInUp 0.4s ease;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}

.faq-item {
    background: #fff;
    border-radius: 14px;
    margin-bottom: 12px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    border: 1px solid rgba(0,0,0,0.05);
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.faq-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 8px 20px rgba(0,0,0,0.03);
    border-color: rgba(0,0,0,0.08);
}

.faq-question {
    padding: 22px 28px;
    font-size: 15px;
    font-weight: 600;
    color: #1e293b;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    transition: all 0.25s;
    user-select: none;
}

.faq-question:hover {
    color: #1e3a5f;
}

.faq-question .q-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 12px;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    flex-shrink: 0;
}

.faq-item.active .faq-question .q-icon {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: #fff;
    transform: rotate(180deg);
}

.faq-item.active .faq-question {
    color: #1e3a5f;
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
}

.faq-answer-inner {
    padding: 0 28px 24px;
    font-size: 15px;
    line-height: 1.8;
    color: #64748b;
}

.faq-item.active .faq-answer {
    max-height: 400px;
}

.faq-no-results {
    text-align: center;
    padding: 60px 20px;
    display: none;
}

.faq-no-results i {
    font-size: 48px;
    color: #cbd5e1;
    margin-bottom: 16px;
}

.faq-no-results p {
    font-size: 16px;
    color: #94a3b8;
    font-weight: 500;
}

@media (max-width: 768px) {
    .faq-hero {
        padding: 60px 20px 70px;
    }

    .faq-hero h1 {
        font-size: 28px;
    }

    .faq-tabs {
        flex-direction: column;
        margin-top: -20px;
    }

    .faq-question {
        padding: 18px 20px;
        font-size: 14px;
    }
    
    .faq-answer-inner {
        padding: 0 20px 20px;
    }
}

@media (max-width: 480px) {
    .faq-hero { padding: 48px 16px 60px; }
    .faq-hero h1 { font-size: 24px; }
    .faq-hero p { font-size: 15px; }
    .faq-search-wrapper input { padding: 14px 18px 14px 48px; font-size: 15px; }
    .faq-tab { padding: 14px 18px; }
    .faq-question { padding: 16px; }
    .faq-answer-inner { padding: 0 16px 18px; }
}
</style>
