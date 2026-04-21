<?php $page_title = 'Registrar visita'; require_once VIEWS_PATH . 'partials/header.php'; ?>
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
<div class="card">
    <form action="index.php?accion=procesarRegistro" method="post" class="grid grid-2">
        <div class="form-group">
            <label for="nombre_completo">Nombre completo</label>
            <input type="text" id="nombre_completo" name="nombre_completo" required>
        </div>
        <div class="form-group">
            <label for="documento_identidad">Documento de identidad</label>
            <input type="text" id="documento_identidad" name="documento_identidad" maxlength="20" required>
        </div>
        <div class="form-group">
            <label for="tipo_documento">Tipo de documento</label>
            <select id="tipo_documento" name="tipo_documento" required>
                <option value="cedula">Cédula</option>
                <option value="pasaporte">Pasaporte</option>
                <option value="otro">Otro</option>
            </select>
        </div>
        <div class="form-group">
            <label for="persona_visitada">Persona o funcionario visitado</label>
            <input type="text" id="persona_visitada" name="persona_visitada" required>
        </div>
        <div class="form-group">
            <label for="despacho_visitado">Despacho visitado</label>
            <select id="despacho_visitado" name="despacho_visitado" required>
                <option value="">Seleccione un despacho</option>
                <?php foreach ($despachos as $despacho): ?>
                    <option value="<?= htmlspecialchars($despacho['id']) ?>"><?= htmlspecialchars($despacho['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fecha_visita">Fecha</label>
            <input type="date" id="fecha_visita" name="fecha_visita" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
            <label for="hora_entrada">Hora de entrada</label>
            <input type="time" id="hora_entrada" name="hora_entrada" required>
        </div>
        <div class="form-group" style="grid-column: span 2;">
            <label for="motivo_visita">Motivo de la visita</label>
            <textarea id="motivo_visita" name="motivo_visita" placeholder="Opcional"></textarea>
        </div>
        <div class="form-group" style="grid-column: span 2;">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" placeholder="Opcional"></textarea>
        </div>
        <div style="grid-column: span 2; text-align:right;">
            <button type="submit" class="button">Registrar visita</button>
        </div>
    </form>
</div>
<?php require_once VIEWS_PATH . 'partials/footer.php'; ?>
