<?php $page_title = 'Buscar visitas'; require_once VIEWS_PATH . 'partials/header.php'; ?>
<div class="card">
    <form action="index.php?pagina=busqueda" method="post" class="grid grid-2">
        <div class="form-group">
            <label for="documento">Documento (DNI)</label>
            <input type="text" id="documento" name="documento" value="<?= htmlspecialchars($_POST['documento'] ?? '') ?>" placeholder="12345678">
        </div>
        <div class="form-group">
            <label for="visitante">Nombre del visitante</label>
            <input type="text" id="visitante" name="visitante" value="<?= htmlspecialchars($_POST['visitante'] ?? '') ?>" placeholder="Juan Pérez">
        </div>
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="despacho">Despacho</label>
            <select id="despacho" name="despacho">
                <option value="">Todos los despachos</option>
                <?php foreach ($despachos as $despacho): ?>
                    <option value="<?= htmlspecialchars($despacho['id']) ?>" <?= isset($_POST['despacho']) && $_POST['despacho'] == $despacho['id'] ? 'selected' : '' ?>><?= htmlspecialchars($despacho['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="grid-column: span 2; text-align:right;">
            <button type="submit" class="button">Buscar</button>
        </div>
    </form>
</div>
<?php if (isset($resultados)): ?>
    <div class="card" style="margin-top:20px;">
        <h3>Resultados</h3>
        <?php if (empty($resultados)): ?>
            <p>No se encontraron visitas con los criterios seleccionados.</p>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Visitante</th>
                            <th>DNI</th>
                            <th>Despacho</th>
                            <th>Fecha</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Tiempo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultados as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['id']) ?></td>
                                <td><?= htmlspecialchars($item['nombre_completo']) ?></td>
                                <td><?= htmlspecialchars($item['documento_identidad']) ?></td>
                                <td><?= htmlspecialchars($item['despacho_nombre']) ?></td>
                                <td><?= htmlspecialchars($item['fecha_visita']) ?></td>
                                <td><?= htmlspecialchars($item['hora_entrada']) ?></td>
                                <td><?= htmlspecialchars($item['hora_salida'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['tiempo_permanencia'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['estado']) ?></td>
                                <td><a class="button button-secondary" href="index.php?pagina=detalles&id=<?= htmlspecialchars($item['id']) ?>">Detalles</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php require_once VIEWS_PATH . 'partials/footer.php'; ?>
