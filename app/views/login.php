<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Control de Visitantes</title>
    <style>
        :root {
            --bg: #eef2ff;
            --card: rgba(255,255,255,.94);
            --primary: #2563eb;
            --text: #0f172a;
            --muted: #475569;
            --border: rgba(148,163,184,.32);
        }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; font-family: Inter, system-ui, sans-serif; display: grid; place-items: center; background: radial-gradient(circle at top, #ffffff 0%, #e2ecff 42%, #dbeafe 100%); color: var(--text); }
        .login-card { width: min(460px, calc(100vw - 32px)); background: var(--card); border-radius: 28px; padding: 36px; box-shadow: 0 32px 80px rgba(15,23,42,.12); border: 1px solid rgba(255,255,255,.72); backdrop-filter: blur(10px); }
        .login-card h1 { margin: 0 0 10px; font-size: 2.4rem; letter-spacing: -0.03em; }
        .login-card p { margin: 0 0 24px; color: var(--muted); line-height: 1.6; }
        label { display: block; margin-bottom: 10px; font-weight: 700; color: #102a43; }
        input { width: 100%; padding: 14px 16px; margin-bottom: 18px; border: 1px solid var(--border); border-radius: 16px; background: #fff; transition: border-color .2s ease, box-shadow .2s ease; }
        input:focus { border-color: rgba(37,99,235,.8); outline: none; box-shadow: 0 0 0 4px rgba(59,130,246,.12); }
        .button { width: 100%; padding: 16px; border: 0; border-radius: 16px; background: var(--primary); color: #fff; font-size: 1rem; font-weight: 700; cursor: pointer; transition: transform .2s ease, filter .2s ease; box-shadow: 0 14px 30px rgba(37,99,235,.18); }
        .button:hover { transform: translateY(-1px); filter: brightness(1.05); }
        .message { border-radius: 18px; padding: 16px 18px; margin-bottom: 18px; line-height: 1.6; }
        .message.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .note { font-size: 13px; color: var(--muted); margin-top: 16px; }
        .metric { display: inline-flex; align-items: center; gap: 10px; margin-top: 22px; padding: 14px 16px; border-radius: 16px; background: #f8fafc; border: 1px solid rgba(148,163,184,.28); }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Iniciar sesión</h1>
        <p>Accede al panel de control de visitas y reportes seguros del sistema.</p>
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
        <form action="index.php?accion=procesarLogin" method="post">
            <?php csrfInput(); ?>
            <label for="correo">Correo electrónico</label>
            <input type="email" name="correo" id="correo" placeholder="admin@visitantes.com" value="<?= old('correo') ?>" required>
            <label for="contraseña">Contraseña</label>
            <input type="password" name="contraseña" id="contraseña" placeholder="Ingrese su contraseña" required>
            <button type="submit" class="button">Entrar</button>
        </form>
        <div class="metric">
            <strong>Usuario:</strong> admin@visitantes.com
            <span style="opacity:.8;">|</span>
            <strong>Clave:</strong> admin123
        </div>
    </div>
</body>
</html>
