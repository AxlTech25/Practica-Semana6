<?php $page_title = 'Dashboard'; require_once VIEWS_PATH . 'partials/header.php'; ?>
<div class="grid grid-3">
    <div class="stat-card">
        <strong>Registrar visita</strong>
        <p>Registre la entrada de personas externas con datos completos de visita.</p>
        <a class="button button-secondary" href="index.php?pagina=registro">Ir a registrar</a>
    </div>
    <div class="stat-card">
        <strong>Consulta de visitas</strong>
        <p>Visualice la lista de visitas y consulte el estado de cada ingreso.</p>
        <a class="button button-secondary" href="index.php?pagina=lista">Ver lista</a>
    </div>
    <div class="stat-card">
        <strong>Reportes y exportación</strong>
        <p>Genere reportes por día, por despacho y exporte a CSV o Excel.</p>
        <a class="button button-secondary" href="index.php?pagina=reportes">Ver reportes</a>
    </div>
</div>
<div class="card" style="margin-top:20px;">
    <h3>Cómo usar el sistema</h3>
    <ul style="padding-left: 20px; color: var(--muted);">
        <li>Registre visitantes con fecha, hora de entrada y despacho visitado.</li>
        <li>Consulte el historial y busque por fecha, visitante o despacho.</li>
        <li>Registre la hora de salida para calcular tiempo de permanencia.</li>
        <li>Exporte resultados a CSV o Excel desde el módulo de reportes.</li>
    </ul>
</div>
<?php require_once VIEWS_PATH . 'partials/footer.php'; ?>
