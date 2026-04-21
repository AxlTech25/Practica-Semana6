<?php $page_title = 'Reportes'; require_once VIEWS_PATH . 'partials/header.php'; ?>
<?php
function formatTiempoPromedio($minutos) {
    if ($minutos === null) {
        return '-';
    }
    $minutos = round($minutos);
    if ($minutos < 60) {
        return $minutos . ' min';
    }
    $horas = floor($minutos / 60);
    $resto = $minutos % 60;
    return $horas . ' h ' . $resto . ' min';
}
?>
<div class="card" style="margin-bottom:20px;">
    <form action="index.php" method="get" class="grid grid-2">
        <input type="hidden" name="pagina" value="reportes">
        <div class="form-group">
            <label for="fecha_inicio">Fecha inicio</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
        </div>
        <div class="form-group">
            <label for="fecha_fin">Fecha fin</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
        </div>
        <div style="grid-column: span 2; text-align:right;">
            <button type="submit" class="button">Filtrar</button>
        </div>
    </form>
</div>
<div class="stats-grid">
    <div class="stat-card">
        <strong><?= htmlspecialchars($estadisticas['total_visitas'] ?? 0) ?></strong>
        <div>Total visitas</div>
    </div>
    <div class="stat-card">
        <strong><?= htmlspecialchars($estadisticas['visitas_activas'] ?? 0) ?></strong>
        <div>Visitas activas</div>
    </div>
    <div class="stat-card">
        <strong><?= htmlspecialchars($estadisticas['visitas_finalizadas'] ?? 0) ?></strong>
        <div>Visitas finalizadas</div>
    </div>
    <div class="stat-card">
        <strong><?= htmlspecialchars($estadisticas['visitantes_unicos'] ?? 0) ?></strong>
        <div>Visitantes únicos</div>
    </div>
    <div class="stat-card">
        <strong><?= formatTiempoPromedio($estadisticas['minutos_promedio'] ?? null) ?></strong>
        <div>Tiempo promedio</div>
    </div>
</div>
<div class="card" style="margin-top:20px;">
    <h3>Exportar reportes</h3>
    <div class="grid grid-2">
        <a class="button" href="index.php?pagina=exportarCSV&tipo=historial&fecha_inicio=<?= urlencode($fecha_inicio) ?>&fecha_fin=<?= urlencode($fecha_fin) ?>">CSV: Historial</a>
        <a class="button button-secondary" href="index.php?pagina=exportarExcel&tipo=historial&fecha_inicio=<?= urlencode($fecha_inicio) ?>&fecha_fin=<?= urlencode($fecha_fin) ?>">Excel: Historial</a>
        <a class="button" href="index.php?pagina=exportarCSV&tipo=dia&fecha_inicio=<?= urlencode($fecha_inicio) ?>&fecha_fin=<?= urlencode($fecha_fin) ?>">CSV: Visitas por día</a>
        <a class="button button-secondary" href="index.php?pagina=exportarExcel&tipo=dia&fecha_inicio=<?= urlencode($fecha_inicio) ?>&fecha_fin=<?= urlencode($fecha_fin) ?>">Excel: Visitas por día</a>
        <a class="button" href="index.php?pagina=exportarCSV&tipo=despacho&fecha_inicio=<?= urlencode($fecha_inicio) ?>&fecha_fin=<?= urlencode($fecha_fin) ?>">CSV: Visitas por despacho</a>
        <a class="button button-secondary" href="index.php?pagina=exportarExcel&tipo=despacho&fecha_inicio=<?= urlencode($fecha_inicio) ?>&fecha_fin=<?= urlencode($fecha_fin) ?>">Excel: Visitas por despacho</a>
    </div>
</div>
<div class="card" style="margin-top:20px;">
    <h3>Visitas por día</h3>
    <?php if (empty($visitas_dia)): ?>
        <p>No hay registros en el período seleccionado.</p>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Finalizadas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($visitas_dia as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['fecha_visita']) ?></td>
                            <td><?= htmlspecialchars($item['total_visitas']) ?></td>
                            <td><?= htmlspecialchars($item['visitas_finalizadas']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<div class="card" style="margin-top:20px;">
    <h3>Visitas por despacho</h3>
    <?php if (empty($visitas_despacho)): ?>
        <p>No hay registros en el período seleccionado.</p>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Despacho</th>
                        <th>Responsable</th>
                        <th>Total visitas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($visitas_despacho as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                            <td><?= htmlspecialchars($item['responsable']) ?></td>
                            <td><?= htmlspecialchars($item['total_visitas']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<div class="card" style="margin-top:20px;">
    <h3>Horas pico de ingreso</h3>
    <?php if (empty($horas_pico)): ?>
        <p>No hay registros en el período seleccionado.</p>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Entradas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($horas_pico as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['hora']) ?>:00</td>
                            <td><?= htmlspecialchars($item['total_entradas']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require_once VIEWS_PATH . 'partials/footer.php'; ?>
