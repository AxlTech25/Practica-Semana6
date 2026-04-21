<?php $page_title = 'Detalles de visita'; require_once VIEWS_PATH . 'partials/header.php'; ?>
<?php if (!empty($_SESSION['errores'])): ?>
    <div class="message error">
        <ul style="margin:0; padding-left:18px;">
            <?php foreach ($_SESSION['errores'] as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['errores']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['mensaje_exito'])): ?>
    <div class="message success">
        <?= htmlspecialchars($_SESSION['mensaje_exito']) ?>
    </div>
    <?php unset($_SESSION['mensaje_exito']); ?>
<?php endif; ?>
<div class="grid grid-2">
    <div class="card">
        <h3>Información del visitante</h3>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($visitante['nombre_completo']) ?></p>
        <p><strong>DNI:</strong> <?= htmlspecialchars($visitante['documento_identidad']) ?></p>
        <p><strong>Tipo de documento:</strong> <?= htmlspecialchars($visitante['tipo_documento']) ?></p>
        <p><strong>Persona visitada:</strong> <?= htmlspecialchars($visitante['persona_visitada']) ?></p>
        <p><strong>Despacho:</strong> <?= htmlspecialchars($visitante['despacho_nombre']) ?></p>
        <p><strong>Fecha:</strong> <?= htmlspecialchars($visitante['fecha_visita']) ?></p>
        <p><strong>Hora entrada:</strong> <?= htmlspecialchars($visitante['hora_entrada']) ?></p>
        <p><strong>Hora salida:</strong> <?= htmlspecialchars($visitante['hora_salida'] ?? '-') ?></p>
        <p><strong>Tiempo de permanencia:</strong> <?= htmlspecialchars($visitante['tiempo_permanencia'] ?? '-') ?></p>
        <p><strong>Estado:</strong>
            <?php if ($visitante['estado'] === 'finalizada'): ?>
                <span class="badge badge-success">Finalizada</span>
            <?php elseif ($visitante['estado'] === 'activa'): ?>
                <span class="badge badge-warning">Activa</span>
            <?php else: ?>
                <span class="badge badge-danger"><?= htmlspecialchars($visitante['estado']) ?></span>
            <?php endif; ?>
        </p>
        <p><strong>Motivo:</strong> <?= nl2br(htmlspecialchars($visitante['motivo_visita'] ?? '-')) ?></p>
        <p><strong>Observaciones:</strong> <?= nl2br(htmlspecialchars($visitante['observaciones'] ?? '-')) ?></p>
        <div style="margin-top:16px;">
            <a class="button button-secondary" href="index.php?pagina=editar&id=<?= htmlspecialchars($visitante['id']) ?>">Editar registro</a>
        </div>
    </div>
    <div class="card">
        <h3>Registrar salida</h3>
        <?php if ($visitante['estado'] === 'activa'): ?>
            <form action="index.php?accion=registrarSalida" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($visitante['id']) ?>">
                <div class="form-group">
                    <label for="hora_salida">Hora de salida</label>
                    <input type="time" id="hora_salida" name="hora_salida" required>
                </div>
                <button type="submit" class="button button-success">Registrar salida</button>
            </form>
        <?php else: ?>
            <p>La salida ya se registró o el estado no es activo.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once VIEWS_PATH . 'partials/footer.php'; ?>
