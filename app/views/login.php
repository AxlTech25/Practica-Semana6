<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Control de Visitantes</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #eef2ff; font-family: Inter, system-ui, sans-serif; color: #121827; }
        .login-card { width: min(460px, calc(100vw - 32px)); background: #ffffff; border-radius: 22px; padding: 32px; box-shadow: 0 22px 70px rgba(15,23,42,.12); }
        h1 { margin-top: 0; font-size: 28px; }
        p { margin: 6px 0 24px; color: #475569; }
        label { display: block; margin-bottom: 8px; font-weight: 700; }
        input { width: 100%; padding: 14px 16px; margin-bottom: 18px; border: 1px solid #cbd5e1; border-radius: 12px; background: #f8fafc; }
        .button { width: 100%; padding: 14px; border: 0; border-radius: 14px; background: #2563eb; color: #fff; font-size: 16px; cursor: pointer; }
        .message { border-radius: 14px; padding: 14px 16px; margin-bottom: 18px; }
        .message.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .note { font-size: 13px; color: #64748b; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Iniciar sesión</h1>
        <p>Ingrese sus credenciales para acceder al sistema de registro de visitantes.</p>
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
            <label for="correo">Correo electrónico</label>
            <input type="email" name="correo" id="correo" placeholder="admin@visitantes.com" required>
            <label for="contraseña">Contraseña</label>
            <input type="password" name="contraseña" id="contraseña" placeholder="Ingrese su contraseña" required>
            <button type="submit" class="button">Entrar</button>
        </form>
        <p class="note">Usuario de prueba: <strong>admin@visitantes.com</strong> / Contraseña: <strong>admin123</strong></p>
    </div>
</body>
</html>
