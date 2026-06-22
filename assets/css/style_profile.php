<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

.profile-page * {
    box-sizing: border-box;
}

.profile-page {
    font-family: 'Inter', sans-serif;
    background: #f1f5f9;
    min-height: 100vh;
}

.profile-hero {
    background: linear-gradient(135deg, #0f2847 0%, #1a3f6f 40%, #2563a8 100%);
    padding: 50px 40px 80px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.profile-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(ellipse at 30% 50%, rgba(255,255,255,0.04) 0%, transparent 60%);
    pointer-events: none;
}
.profile-hero::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #f59e0b, #f97316, #ef4444, #8b5cf6, #3b82f6);
}
.profile-hero .breadcrumb {
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    margin-bottom: 14px;
    letter-spacing: 0.5px;
}
.profile-hero .breadcrumb a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: color 0.3s;
}
.profile-hero .breadcrumb a:hover { color: #fff; }
.profile-hero h1 {
    font-size: 36px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 8px;
    letter-spacing: -0.5px;
}
.profile-hero p {
    font-size: 16px;
    color: rgba(255,255,255,0.6);
    font-weight: 300;
    margin: 0;
}

.profile-layout {
    display: flex;
    max-width: 1140px;
    margin: -40px auto 50px;
    padding: 0 24px;
    gap: 28px;
    position: relative;
    z-index: 2;
}

.profile-sidebar {
    flex: 0 0 260px;
    position: sticky;
    top: 24px;
    align-self: flex-start;
}
.profile-sidebar-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.04);
    border: 1px solid rgba(0,0,0,0.04);
}
.sidebar-section {
    margin-bottom: 24px;
}
.sidebar-section:last-child {
    margin-bottom: 0;
}
.sidebar-section h4 {
    font-size: 11px;
    text-transform: uppercase;
    color: #94a3b8;
    font-weight: 700;
    letter-spacing: 1.5px;
    margin: 0 0 12px;
    padding: 0 12px 10px;
    border-bottom: 1px solid #f1f5f9;
}
.sidebar-nav {
    list-style: none;
    padding: 0;
    margin: 0;
}
.sidebar-nav li {
    margin-bottom: 4px;
}
.sidebar-nav a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    text-decoration: none;
    color: #64748b;
    font-weight: 500;
    font-size: 14px;
    border-radius: 10px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}
.sidebar-nav a i {
    font-size: 16px;
    color: #94a3b8;
    width: 20px;
    text-align: center;
    transition: color 0.25s;
}
.sidebar-nav a:hover {
    background: #f8fafc;
    color: #334155;
}
.sidebar-nav a.active {
    background: #eff6ff;
    color: #1e40af;
    font-weight: 600;
}
.sidebar-nav a.active::before {
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
.sidebar-nav a.active i {
    color: #2563eb;
}

.profile-content {
    flex: 1;
    min-width: 0;
}

/* Flash Messages */
.alert {
    padding: 14px 20px;
    border-radius: 12px;
    margin-bottom: 22px;
    font-weight: 500;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideDown 0.4s ease;
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.alert-success {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}
.alert-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

/* Cards */
.profile-card {
    background: #fff;
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.04);
    border: 1px solid rgba(0,0,0,0.04);
}
.profile-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 28px;
    padding-bottom: 18px;
    border-bottom: 1px solid #f1f5f9;
}
.card-header-actions {
    margin-left: auto;
    flex-shrink: 0;
}
.profile-card-header > div:not(.card-icon):not(.card-header-actions) {
    flex: 1;
}
.profile-card-header .card-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.profile-card-header .card-icon.personal {
    background: linear-gradient(135deg, #dbeafe, #eff6ff);
    color: #2563eb;
}
.profile-card-header .card-icon.domicilio {
    background: linear-gradient(135deg, #fef3c7, #fefce8);
    color: #d97706;
}
.profile-card-header h2 {
    font-size: 17px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    letter-spacing: -0.2px;
}
.profile-card-header span {
    font-size: 13px;
    color: #94a3b8;
    font-weight: 400;
}

.profile-identity {
    display: flex;
    align-items: center;
    gap: 28px;
    margin-bottom: 32px;
    padding-bottom: 28px;
    border-bottom: 1px solid #f1f5f9;
}

.photo-upload-container {
    position: relative;
    flex-shrink: 0;
}
.photo-preview {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e0e7ff, #f0f9ff);
    border: 3px solid #fff;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.photo-preview .placeholder-icon {
    font-size: 44px;
    color: #93c5fd;
}
.photo-edit-btn {
    position: absolute;
    bottom: 2px;
    right: 2px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 3px solid #fff;
    transition: all 0.3s;
    font-size: 13px;
    box-shadow: 0 2px 8px rgba(59,130,246,0.4);
}
.photo-edit-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(59,130,246,0.5);
}
.photo-upload-input { display: none; }

.profile-identity-info h3 {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 4px;
}
.profile-identity-info p {
    font-size: 14px;
    color: #94a3b8;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}
.profile-identity-info .badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    background: #ecfdf5;
    color: #059669;
    font-size: 11px;
    font-weight: 600;
    border-radius: 20px;
    margin-top: 8px;
}

/* Form Inputs */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.form-group.full-width {
    grid-column: 1 / -1;
}
.form-group label {
    font-size: 13px;
    color: #64748b;
    font-weight: 600;
    letter-spacing: 0.2px;
}
.form-group input {
    padding: 12px 16px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    color: #1e293b;
    font-family: 'Inter', sans-serif;
    transition: all 0.25s;
    background: #fff;
    outline: none;
}
.form-group input:hover {
    border-color: #cbd5e1;
}
.form-group input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
.form-group input[readonly] {
    background: #f8fafc;
    color: #94a3b8;
    cursor: not-allowed;
    border-style: dashed;
}
.form-group .input-hint {
    font-size: 12px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Actions */
.btn-edit {
    background: #f1f5f9;
    color: #475569;
    border: 1.5px solid #e2e8f0;
    padding: 8px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s;
    display: flex;
    align-items: center;
    gap: 7px;
    font-family: 'Inter', sans-serif;
}
.btn-edit:hover {
    background: #1e3a5f;
    color: #fff;
    border-color: #1e3a5f;
    box-shadow: 0 4px 14px rgba(30,58,95,0.18);
}
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 8px;
}
.btn-save {
    background: linear-gradient(135deg, #1e3a5f, #1a3f6f);
    color: white;
    border: none;
    padding: 13px 32px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    letter-spacing: 0.3px;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(30,58,95,0.3);
}
.btn-save:active {
    transform: translateY(0);
}
.btn-cancel {
    background: #f1f5f9;
    color: #64748b;
    border: 1.5px solid #e2e8f0;
    padding: 13px 28px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
}
.btn-cancel:hover {
    background: #e2e8f0;
    color: #334155;
}

/* Campo en modo edición activo */
.field-editing {
    border-color: #3b82f6 !important;
    background: #fff !important;
    color: #1e293b !important;
}

/* Selectores de ubicación */
.selects-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 16px;
}
.sublabel {
    font-size: 12px !important;
    color: #94a3b8 !important;
    font-weight: 500 !important;
}
.profile-select {
    padding: 12px 16px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    color: #1e293b;
    font-family: 'Inter', sans-serif;
    transition: all 0.25s;
    background: #fff;
    outline: none;
    cursor: pointer;
    width: 100%;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 16px;
    padding-right: 40px;
}
.profile-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
.location-selectors {
    margin-top: 4px;
}

/* Danger Zone */
.card-danger {
    border: 1px solid #fecaca;
}
.danger-action-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 12px 0;
}
.danger-title {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 4px;
}
.danger-text {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}
.danger-divider {
    border: 0;
    border-top: 1px solid #f1f5f9;
    margin: 16px 0;
}
.btn-danger {
    background: #ef4444;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
}
.btn-danger:hover { background: #dc2626; }
.btn-danger-outline {
    background: white;
    color: #ef4444;
    border: 1.5px solid #ef4444;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
}
.btn-danger-outline:hover { background: #fef2f2; }

/* Toggle Switch */
.toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 8px 0;
}
.toggle-info h3 {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 4px;
}
.toggle-info p {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}
.switch {
    position: relative;
    display: inline-block;
    width: 46px;
    height: 24px;
    flex-shrink: 0;
}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1;
    transition: .4s;
}
.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
input:checked + .slider {
    background-color: #3b82f6;
}
input:checked + .slider:before {
    transform: translateX(22px);
}
.slider.round {
    border-radius: 24px;
}
.slider.round:before {
    border-radius: 50%;
}

/* Password Input with eye icon */
.input-password-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}
.input-password-wrapper input {
    width: 100%;
    padding-right: 44px;
}
.toggle-password {
    position: absolute;
    right: 14px;
    background: none;
    border: none;
    cursor: pointer;
    color: #94a3b8;
    font-size: 15px;
    padding: 0;
    transition: color 0.2s;
}
.toggle-password:hover { color: #3b82f6; }

/* Strength Bar */
.strength-bar {
    height: 4px;
    background: #e2e8f0;
    border-radius: 4px;
    margin-top: 6px;
    overflow: hidden;
}
.strength-fill {
    height: 100%;
    width: 0%;
    border-radius: 4px;
    transition: width 0.4s, background 0.4s;
}
.strength-label {
    font-size: 12px;
    font-weight: 600;
    margin-top: 4px;
    display: block;
    min-height: 18px;
}

/* Modal */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.5);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.2s ease;
}
@keyframes fadeIn {
    from { opacity: 0; } to { opacity: 1; }
}
.modal-box {
    background: #fff;
    border-radius: 20px;
    padding: 40px 36px;
    max-width: 420px;
    width: 90%;
    text-align: center;
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    animation: slideUp 0.3s cubic-bezier(0.4,0,0.2,1);
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.modal-icon {
    width: 64px;
    height: 64px;
    background: #fef2f2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 28px;
    color: #dc2626;
}
.modal-box h3 {
    font-size: 20px;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 10px;
}
.modal-box p {
    font-size: 14px;
    color: #64748b;
    margin: 0 0 28px;
    line-height: 1.6;
}
.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

@media (max-width: 700px) {
    .selects-grid {
        grid-template-columns: 1fr;
    }
}

/* Responsive */
@media (max-width: 900px) {
    .profile-layout {
        flex-direction: column;
    }
    .profile-sidebar {
        flex: none;
        position: static;
    }
    .profile-sidebar-card .sidebar-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .profile-sidebar-card .sidebar-nav li { margin-bottom: 0; }
    .profile-sidebar-card .sidebar-nav a {
        padding: 8px 14px;
        font-size: 13px;
    }
    .profile-identity {
        flex-direction: column;
        text-align: center;
    }
}
@media (max-width: 600px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    .profile-card {
        padding: 20px;
    }
    .profile-hero h1 {
        font-size: 26px;
    }
}
</style>
