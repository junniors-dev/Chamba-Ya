<?php
// Muestra un mensaje segun ?estado= de la URL.
if (!empty($_GET['estado'])):
    $bannerMsgs = [
        'postulado'    => ['¡Te postulaste con éxito!',                 '#16a34a'],
        'duplicado'    => ['Ya te habías postulado a este anuncio.',     '#d97706'],
        'no_disponible'=> ['Este anuncio ya no está disponible para postular.', '#d97706'],
        'propio'       => ['No puedes postularte a tu propio anuncio.',  '#d97706'],
        'fav_guardado' => ['Anuncio agregado a tus guardados.',          '#16a34a'],
        'fav_quitado'  => ['Anuncio quitado de tus guardados.',          '#64748b'],
        'calificado'   => ['¡Gracias! Tu calificación fue registrada.',  '#16a34a'],
        'cal_invalida' => ['Selecciona una puntuación de 1 a 5 estrellas.', '#d97706'],
        'cal_propia'   => ['No puedes calificarte a ti mismo.',          '#d97706'],
        'trab_guardado'=> ['Trabajador agregado a tus guardados.',       '#16a34a'],
        'trab_quitado' => ['Trabajador quitado de tus guardados.',       '#64748b'],
        'trab_propio'  => ['No puedes guardarte a ti mismo.',            '#d97706'],
        'reportado'    => ['Gracias, recibimos tu reporte.',             '#16a34a'],
        'rep_duplicado'=> ['Ya habías reportado este anuncio.',          '#d97706'],
        'rep_error'    => ['No se pudo enviar el reporte.',              '#dc2626'],
        'error'        => ['Ocurrió un error. Inténtalo de nuevo.',      '#dc2626'],
    ];
    $b = $bannerMsgs[$_GET['estado']] ?? null;
    if ($b): ?>
        <div style="max-width:1100px;margin:15px auto;padding:12px 18px;border-radius:8px;color:#fff;background:<?= $b[1] ?>;font-weight:600;text-align:center;">
            <?= htmlspecialchars($b[0]) ?>
        </div>
<?php endif;
endif; ?>
