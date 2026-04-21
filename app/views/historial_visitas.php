<?php $page_title = 'Historial de visitas'; require_once VIEWS_PATH . 'partials/header.php'; ?>
<div class="card" style="margin-bottom:20px;">
    <form action="index.php" method="get" class="grid grid-2">
        <input type="hidden" name="pagina" value="historial">
        <div class="form-group">
            <label for="fecha_inicio">Fecha inicio</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
        </div>
        <div class="form-group">
            <label for="fecha_fin">Fecha fin</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
        </div>
        <div class="form-group">
            <label for="despacho">Despacho</label>
            <select id="despacho" name="despacho">
                <option value="">Todos los despachos</option>
                <?php foreach ($despachos as $despacho): ?>
                    <option value="<?= htmlspecialchars($despacho['id']) ?>" <?= ($despacho_filtro == $despacho['id']) ? 'selected' : '' ?>><?= htmlspecialchars($despacho['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="grid-column: span 2; text-align:right;">
            <button type="submit" class="button">Filtrar</button>
        </div>
    </form>
</div>
<div class="card" style="margin-bottom:20px;">
    <h3>Exportar historial</h3>
    <div class="grid grid-2">
        <a class="button" href="index.php?pagina=exportarCSV&tipo=historial&fecha_inicio=<?= urlencode($fecha_inicio) ?>&fecha_fin=<?= urlencode($fecha_fin) ?>&despacho=<?= urlencode($despacho_filtro) ?>">CSV</a>
        <a class="button button-secondary" href="index.php?pagina=exportarExcel&tipo=historial&fecha_inicio=<?= urlencode($fecha_inicio) ?>&fecha_fin=<?= urlencode($fecha_fin) ?>&despacho=<?= urlencode($despacho_filtro) ?>">Excel</a>
    </div>
</div>
<div class="card">
    <?php if (empty($historial)): ?>
        <p>No hay visitas registradas para el filtro seleccionado.</p>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Visitante</th>
                        <th>DNI</th>
                        <th>Despacho</th>
                        <th>Persona visitada</th>
                        <th>Fecha</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Tiempo</th>
                        <th>Estado</th>
                        <th>Registrado por</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['id']) ?></td>
                            <td><?= htmlspecialchars($item['nombre_completo']) ?></td>
                            <td><?= htmlspecialchars($item['documento_identidad']) ?></td>
                            <td><?= htmlspecialchars($item['despacho_nombre']) ?></td>
                            <td><?= htmlspecialchars($item['persona_visitada']) ?></td>
                            <td><?= htmlspecialchars($item['fecha_visita']) ?></td>
                            <td><?= htmlspecialchars($item['hora_entrada']) ?></td>
                            <td><?= htmlspecialchars($item['hora_salida'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['tiempo_permanencia'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['estado']) ?></td>
                            <td><?= htmlspecialchars($item['usuario_registro'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require_once VIEWS_PATH . 'partials/footer.php'; ?>
