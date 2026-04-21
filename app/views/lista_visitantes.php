<?php $page_title = 'Lista de visitas'; require_once VIEWS_PATH . 'partials/header.php'; ?>
<div class="card">
    <?php if (empty($visitantes)): ?>
        <p>No hay visitas registradas aún.</p>
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
                        <th>Hora entrada</th>
                        <th>Hora salida</th>
                        <th>Tiempo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($visitantes as $item): ?>
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
                            <td>
                                <?php if ($item['estado'] === 'finalizada'): ?>
                                    <span class="badge badge-success">Finalizada</span>
                                <?php elseif ($item['estado'] === 'activa'): ?>
                                    <span class="badge badge-warning">Activa</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><?= htmlspecialchars($item['estado']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td style="white-space: nowrap;">
                                <div class="action-buttons">
                                    <a class="button button-secondary btn-sm" href="index.php?pagina=detalles&id=<?= htmlspecialchars($item['id']) ?>">Detalles</a>
                                    <a class="button button-secondary btn-sm" href="index.php?pagina=editar&id=<?= htmlspecialchars($item['id']) ?>">Editar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($total_paginas) && $total_paginas > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <a href="index.php?pagina=lista&p=<?= $i ?>" class="<?= $i == $pagina ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php require_once VIEWS_PATH . 'partials/footer.php'; ?>
