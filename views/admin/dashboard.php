<?php
    $tituloAdmin = 'Dashboard';
    $subtituloAdmin = 'Resumen general de Chamba Ya';
    require __DIR__ . '/_head_admin.php';

    $s = $stats ?? [];
    $kpis = [
        ['Usuarios',            $s['total_usuarios'] ?? 0,      'fa-users',        '#3b82f6'],
        ['Anuncios',            $s['total_anuncios'] ?? 0,      'fa-bullhorn',     '#f97316'],
        ['Postulaciones',       $s['total_postulaciones'] ?? 0, 'fa-paper-plane',  '#10b981'],
        ['Reportes pendientes', $s['reportes_pendientes'] ?? 0, 'fa-flag',         '#ef4444'],
        ['Categorías',          $s['total_categorias'] ?? 0,    'fa-tags',         '#8b5cf6'],
        ['Nuevos este mes',     $s['nuevos_usuarios_mes'] ?? 0, 'fa-user-plus',    '#f59e0b'],
    ];
?>

<div class="kpi-grid">
    <?php foreach ($kpis as $k): ?>
        <div class="kpi-card">
            <div class="kpi-icon" style="background:<?= $k[3] ?>"><i class="fa-solid <?= $k[2] ?>"></i></div>
            <div>
                <div class="kpi-num"><?= (int) $k[1] ?></div>
                <div class="kpi-label"><?= $k[0] ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="chart-grid">
    <div class="chart-card">
        <h3>Anuncios publicados (últimos 6 meses)</h3>
        <canvas id="chartMeses" height="120"></canvas>
    </div>
    <div class="chart-card">
        <h3>Trabajo vs Servicio</h3>
        <canvas id="chartTipos" height="120"></canvas>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    const mesesData = <?= json_encode($s['anuncios_por_mes'] ?? []) ?>;
    const tiposData = <?= json_encode($s['distribucion_tipo'] ?? []) ?>;

    const nombresMes = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    const labelsMeses = mesesData.map(m => {
        const p = m.mes.split('-');
        return nombresMes[parseInt(p[1],10)-1] + ' ' + p[0].slice(2);
    });
    const valoresMeses = mesesData.map(m => parseInt(m.total,10));

    if (document.getElementById('chartMeses')) {
        new Chart(document.getElementById('chartMeses'), {
            type: 'bar',
            data: { labels: labelsMeses, datasets: [{ label: 'Anuncios', data: valoresMeses, backgroundColor: '#2563eb', borderRadius: 6 }] },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#64748b' }, grid: { color: '#e4e9e4' } },
                    y: { ticks: { color: '#64748b' }, grid: { color: '#e4e9e4' }, beginAtZero: true }
                }
            }
        });
    }

    if (document.getElementById('chartTipos')) {
        // Verde = Trabajo, Amarillo = Servicio (colores de marca del sitio)
        const coloresTipo = Object.keys(tiposData).map(t => t === 'Trabajo' ? '#25C25E' : '#FFC700');
        new Chart(document.getElementById('chartTipos'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(tiposData),
                datasets: [{ data: Object.values(tiposData).map(v => parseInt(v,10)), backgroundColor: coloresTipo, borderWidth: 0 }]
            },
            options: { plugins: { legend: { labels: { color: '#15231a' } } } }
        });
    }
</script>

<?php require __DIR__ . '/_foot_admin.php'; ?>
