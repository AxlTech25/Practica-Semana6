<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso — Control de Visitantes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy-900: #060d1a;
            --navy-800: #0a1628;
            --navy-700: #0f1f3d;
            --gold-400: #e2b96f;
            --gold-500: #c9a84c;
            --gold-600: #a8843a;
            --platinum: #d6dde8;
            --text-primary:   #e8edf5;
            --text-secondary: #8fa3c0;
            --text-muted:     #5a7090;
            --surface-glass: rgba(10, 22, 45, 0.80);
            --border-subtle: rgba(226,185,111,.12);
            --border-mid:    rgba(226,185,111,.25);
            --border-bright: rgba(226,185,111,.5);
            --danger-bg:  rgba(220,38,38,.15);
            --danger-txt: #f87171;
            --danger-bdr: rgba(248,113,113,.25);
        }
        @keyframes fadeInUp {
            from { opacity:0; transform: translateY(24px); }
            to   { opacity:1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            font-family: 'Inter', system-ui, sans-serif;
            display: grid;
            place-items: center;
            background:
                radial-gradient(ellipse 70% 55% at 15% 10%,  rgba(201,168,76,.09) 0%, transparent 55%),
                radial-gradient(ellipse 55% 45% at 85% 90%,  rgba(30,49,87,.85)   0%, transparent 60%),
                linear-gradient(160deg, var(--navy-900) 0%, var(--navy-800) 55%, #07111f 100%);
            background-attachment: fixed;
            color: var(--text-primary);
            padding: 24px;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: var(--gold-600); border-radius: 99px; }

        /* ── CARD ── */
        .login-wrapper {
            width: min(460px, 100%);
            animation: fadeInUp .5s ease both;
        }
        .login-badge {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--gold-500), var(--gold-400));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.9rem;
            margin: 0 auto 24px;
            box-shadow: 0 10px 30px rgba(201,168,76,.4);
            animation: float 4s ease-in-out infinite;
        }
        .login-card {
            background: var(--surface-glass);
            border: 1px solid var(--border-mid);
            border-radius: 24px;
            padding: 38px 36px;
            box-shadow: 0 32px 80px rgba(0,0,0,.55), 0 0 60px rgba(201,168,76,.06);
            backdrop-filter: blur(20px);
        }
        .login-card h1 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            background: linear-gradient(90deg, #fff 0%, var(--gold-400) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }
        .login-card p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.65;
            margin-bottom: 28px;
        }
        .gold-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold-500), transparent);
            opacity: .3;
            margin-bottom: 28px;
        }

        /* ── LABELS ── */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        /* ── INPUTS ── */
        input {
            width: 100%;
            padding: 13px 16px;
            margin-bottom: 18px;
            border: 1px solid var(--border-mid);
            border-radius: 12px;
            background: rgba(6,13,26,.55);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }
        input::placeholder { color: var(--text-muted); }
        input:focus {
            border-color: var(--gold-500);
            box-shadow: 0 0 0 4px rgba(201,168,76,.12);
            outline: none;
            background: rgba(6,13,26,.8);
        }

        /* ── BUTTON ── */
        .button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--gold-500), var(--gold-400));
            color: var(--navy-900);
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform .22s ease, box-shadow .22s ease, filter .22s ease;
            box-shadow: 0 10px 28px rgba(201,168,76,.38);
            letter-spacing: 0.02em;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 34px rgba(201,168,76,.5);
            filter: brightness(1.06);
        }
        .button:active { transform: translateY(0); }

        /* ── MESSAGES ── */
        .message {
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 18px;
            line-height: 1.6;
            font-size: 0.88rem;
            font-weight: 500;
        }
        .message.error {
            background: var(--danger-bg);
            color: var(--danger-txt);
            border: 1px solid var(--danger-bdr);
        }

        /* ── DEMO CREDENTIALS ── */
        .metric {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
            padding: 14px 16px;
            border-radius: 12px;
            background: rgba(201,168,76,.06);
            border: 1px solid var(--border-subtle);
            font-size: 0.82rem;
            color: var(--text-secondary);
        }
        .metric strong { color: var(--gold-400); font-weight: 700; }
        .metric .sep { color: var(--border-mid); }

        /* ── FOOTER NOTE ── */
        .note {
            text-align: center;
            margin-top: 22px;
            font-size: 0.76rem;
            color: var(--text-muted);
            letter-spacing: 0.04em;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-badge">🏛️</div>
        <div class="login-card">
            <h1>Iniciar sesión</h1>
            <p>Accede al panel de control de visitas y reportes seguros del sistema.</p>
            <div class="gold-divider"></div>
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
                <input type="password" name="contraseña" id="contraseña" placeholder="••••••••" required>
                <button type="submit" class="button">Entrar al sistema →</button>
            </form>
            <div class="metric">
                <strong>Demo:</strong>
                <span>admin@visitantes.com</span>
                <span class="sep">|</span>
                <strong>Clave:</strong>
                <span>admin123</span>
            </div>
        </div>
        <p class="note">Control de Visitantes v1.0 &nbsp;·&nbsp; Sistema seguro</p>
    </div>
</body>
</html>
