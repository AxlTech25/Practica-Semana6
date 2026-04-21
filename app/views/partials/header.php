<?php
$userName = $_SESSION['usuario_nombre'] ?? 'Invitado';
$paginaActual = $_GET['pagina'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Control de Visitantes') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ═══════════════════════════════════════
           PALETA PREMIUM: NAVY + GOLD + PLATINUM
        ═══════════════════════════════════════ */
        :root {
            --navy-900:  #060d1a;
            --navy-800:  #0a1628;
            --navy-700:  #0f1f3d;
            --navy-600:  #162544;
            --navy-500:  #1e3157;
            --gold-400:  #e2b96f;
            --gold-500:  #c9a84c;
            --gold-600:  #a8843a;
            --platinum:  #d6dde8;
            --white:     #ffffff;
            --text-primary:   #e8edf5;
            --text-secondary: #8fa3c0;
            --text-muted:     #5a7090;
            --surface-glass: rgba(14, 26, 52, 0.72);
            --surface-card:  rgba(15, 31, 61, 0.85);
            --border-subtle: rgba(226,185,111,.12);
            --border-mid:    rgba(226,185,111,.22);
            --border-bright: rgba(226,185,111,.45);
            --success-bg: rgba(22,163,74,.15);
            --success-txt: #4ade80;
            --success-bdr: rgba(74,222,128,.25);
            --warning-bg:  rgba(217,119,6,.15);
            --warning-txt: #fbbf24;
            --warning-bdr: rgba(251,191,36,.25);
            --danger-bg:   rgba(220,38,38,.15);
            --danger-txt:  #f87171;
            --danger-bdr:  rgba(248,113,113,.25);
            --shadow-card: 0 20px 60px rgba(0,0,0,.45);
            --shadow-glow: 0 0 40px rgba(201,168,76,.08);
        }

        @keyframes fadeInUp {
            from { opacity:0; transform: translateY(18px); }
            to   { opacity:1; transform: translateY(0); }
        }
        @keyframes shimmer {
            0%  { background-position: -200% center; }
            100%{ background-position:  200% center; }
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Inter', system-ui, sans-serif;
            background:
                radial-gradient(ellipse 80% 60% at 10% 0%,   rgba(201,168,76,.07) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 90% 100%, rgba(30,49,87,.9)  0%, transparent 60%),
                linear-gradient(160deg, var(--navy-900) 0%, var(--navy-800) 50%, #07111f 100%);
            background-attachment: fixed;
            color: var(--text-primary);
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--navy-900); }
        ::-webkit-scrollbar-thumb { background: var(--gold-600); border-radius: 99px; }

        /* ── LAYOUT ── */
        .container {
            max-width: 1260px;
            margin: 0 auto;
            padding: 0 24px 60px;
        }

        /* ── TOP HEADER BAR ── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px 0 18px;
            border-bottom: 1px solid var(--border-subtle);
            margin-bottom: 28px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .header-badge {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--gold-500), var(--gold-400));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            box-shadow: 0 6px 20px rgba(201,168,76,.35);
            flex-shrink: 0;
        }
        .header h1 {
            font-size: clamp(1.2rem, 2vw, 1.55rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            background: linear-gradient(90deg, var(--white) 0%, var(--gold-400) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .header p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 2px;
            font-weight: 400;
        }
        .user-panel {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--surface-glass);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            backdrop-filter: blur(12px);
        }
        .user-panel .username {
            color: var(--text-primary);
            font-weight: 600;
        }
        .user-panel a {
            color: var(--gold-400);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.82rem;
            transition: color .2s;
        }
        .user-panel a:hover { color: var(--gold-400); filter: brightness(1.2); }
        .user-panel .divider { color: var(--border-mid); }

        /* ── NAVIGATION ── */
        .menu {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 30px;
            background: var(--surface-glass);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 8px;
            backdrop-filter: blur(14px);
            animation: fadeInUp .4s ease both;
        }
        .menu a {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            border-radius: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all .22s ease;
            border: 1px solid transparent;
        }
        .menu a:hover {
            color: var(--text-primary);
            background: rgba(226,185,111,.08);
            border-color: var(--border-subtle);
        }
        .menu a.active {
            background: linear-gradient(135deg, var(--gold-500), var(--gold-400));
            color: var(--navy-900);
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(201,168,76,.35);
        }
        .menu a.active:hover { filter: brightness(1.05); }

        /* ── PAGE TITLE ── */
        .page-title {
            font-size: clamp(1.5rem, 2.2vw, 2rem);
            font-weight: 800;
            letter-spacing: -0.04em;
            color: var(--text-primary);
            margin-bottom: 24px;
            animation: fadeInUp .35s .05s ease both;
        }

        /* ── CARD ── */
        .card {
            background: var(--surface-card);
            border: 1px solid var(--border-subtle);
            border-radius: 20px;
            padding: 28px;
            box-shadow: var(--shadow-card), var(--shadow-glow);
            backdrop-filter: blur(18px);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
            animation: fadeInUp .4s .08s ease both;
        }
        .card:hover {
            transform: translateY(-3px);
            border-color: var(--border-mid);
            box-shadow: 0 30px 80px rgba(0,0,0,.55), 0 0 50px rgba(201,168,76,.1);
        }

        /* ── STAT CARDS ── */
        .stats-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
        }
        .stat-card {
            padding: 26px 24px;
            border-radius: 18px;
            border: 1px solid var(--border-subtle);
            background: var(--surface-glass);
            backdrop-filter: blur(14px);
            transition: transform .22s ease, border-color .22s ease;
            animation: fadeInUp .4s ease both;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            border-color: var(--border-mid);
        }
        .stat-card strong {
            display: block;
            font-size: 2.4rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--gold-400), var(--white));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
            line-height: 1;
        }
        .stat-card p, .stat-card div {
            color: var(--text-secondary);
            font-size: 0.88rem;
            font-weight: 500;
        }

        /* ── GRID LAYOUTS ── */
        .grid { display: grid; gap: 20px; }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(240px,1fr)); }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(300px,1fr)); }

        /* ── BUTTONS ── */
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 12px 22px;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .22s ease;
            background: linear-gradient(135deg, var(--gold-500), var(--gold-400));
            color: var(--navy-900);
            box-shadow: 0 6px 20px rgba(201,168,76,.32);
            letter-spacing: 0.01em;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(201,168,76,.45);
            filter: brightness(1.06);
        }
        .button:active { transform: translateY(0); }

        .button-secondary {
            background: rgba(255,255,255,.06);
            color: var(--platinum);
            border: 1px solid var(--border-mid);
            box-shadow: none;
        }
        .button-secondary:hover {
            background: rgba(226,185,111,.12);
            border-color: var(--border-bright);
            color: var(--gold-400);
            box-shadow: 0 6px 20px rgba(201,168,76,.15);
        }
        .button-success {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #fff;
            box-shadow: 0 6px 20px rgba(22,163,74,.35);
        }
        .button-danger {
            background: linear-gradient(135deg, #b91c1c, #ef4444);
            color: #fff;
            box-shadow: 0 6px 20px rgba(185,28,28,.35);
        }
        .btn-sm {
            padding: 7px 13px;
            font-size: 0.8rem;
            border-radius: 9px;
        }
        .action-buttons {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            gap: 8px;
        }

        /* ── FORMS ── */
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            margin-bottom: 9px;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 13px 16px;
            border-radius: 12px;
            border: 1px solid var(--border-mid);
            background: rgba(6,13,26,.55);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }
        .form-group select option { background: var(--navy-800); color: var(--text-primary); }
        .form-group textarea { min-height: 120px; resize: vertical; }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--gold-500);
            box-shadow: 0 0 0 4px rgba(201,168,76,.12);
            outline: none;
            background: rgba(6,13,26,.75);
        }
        .form-group input::placeholder,
        .form-group textarea::placeholder { color: var(--text-muted); }

        /* ── TABLE ── */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            font-size: 0.9rem;
        }
        .table thead { position: sticky; top: 0; z-index: 2; }
        .table th {
            padding: 14px 16px;
            text-align: left;
            background: rgba(201,168,76,.12);
            color: var(--gold-400);
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-bottom: 1px solid var(--border-mid);
        }
        .table th:first-child { border-radius: 10px 0 0 0; }
        .table th:last-child  { border-radius: 0 10px 0 0; }
        .table td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            vertical-align: middle;
        }
        .table tbody tr {
            transition: background .18s ease;
        }
        .table tbody tr:hover td {
            background: rgba(226,185,111,.05);
            color: var(--text-primary);
        }
        .table tbody tr:last-child td { border-bottom: none; }

        /* ── BADGES ── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .badge-success {
            background: var(--success-bg);
            color: var(--success-txt);
            border: 1px solid var(--success-bdr);
        }
        .badge-warning {
            background: var(--warning-bg);
            color: var(--warning-txt);
            border: 1px solid var(--warning-bdr);
        }
        .badge-danger {
            background: var(--danger-bg);
            color: var(--danger-txt);
            border: 1px solid var(--danger-bdr);
        }

        /* ── MESSAGES ── */
        .message {
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 22px;
            line-height: 1.6;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .message.error {
            background: var(--danger-bg);
            color: var(--danger-txt);
            border: 1px solid var(--danger-bdr);
        }
        .message.success {
            background: var(--success-bg);
            color: var(--success-txt);
            border: 1px solid var(--success-bdr);
        }

        /* ── PAGINATION ── */
        .pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 26px;
        }
        .pagination a {
            padding: 9px 15px;
            border-radius: 10px;
            border: 1px solid var(--border-mid);
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.88rem;
            font-weight: 500;
            transition: all .2s ease;
            background: transparent;
        }
        .pagination a:hover {
            border-color: var(--gold-500);
            color: var(--gold-400);
            background: rgba(201,168,76,.08);
        }
        .pagination a.active {
            background: linear-gradient(135deg, var(--gold-500), var(--gold-400));
            color: var(--navy-900);
            border-color: transparent;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(201,168,76,.3);
        }

        /* ── DIVIDER LINE ── */
        .gold-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold-500), transparent);
            margin: 28px 0;
            opacity: .35;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--border-subtle);
            color: var(--text-muted);
            font-size: 0.78rem;
            text-align: center;
            letter-spacing: 0.04em;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 660px) {
            .menu { padding: 6px; }
            .menu a { padding: 9px 14px; font-size: 0.84rem; }
            .grid-2 { grid-template-columns: 1fr; }
            .header { padding: 16px 0 14px; }
        }
    </style>
</head>
<body>
<div class="container">
    <header class="header">
        <div class="header-brand">
            <div class="header-badge">🏛️</div>
            <div>
                <h1>Control de Visitantes</h1>
                <p>Sistema de registro y reportes de visitas</p>
            </div>
        </div>
        <div class="user-panel">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color:var(--gold-500);flex-shrink:0"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            <span class="username"><?= htmlspecialchars($userName) ?></span>
            <span class="divider">|</span>
            <a href="index.php?accion=logout">Cerrar sesión</a>
        </div>
    </header>
    <nav class="menu">
        <a href="index.php?pagina=dashboard" class="<?= $paginaActual === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="index.php?pagina=registro"  class="<?= $paginaActual === 'registro'  ? 'active' : '' ?>">✏️ Registrar visita</a>
        <a href="index.php?pagina=lista"     class="<?= $paginaActual === 'lista'     ? 'active' : '' ?>">📋 Lista</a>
        <a href="index.php?pagina=busqueda"  class="<?= $paginaActual === 'busqueda'  ? 'active' : '' ?>">🔍 Buscar</a>
        <a href="index.php?pagina=reportes"  class="<?= $paginaActual === 'reportes'  ? 'active' : '' ?>">📈 Reportes</a>
        <a href="index.php?pagina=historial" class="<?= $paginaActual === 'historial' ? 'active' : '' ?>">🕐 Historial</a>
    </nav>
    <main>
        <h2 class="page-title"><?= htmlspecialchars($page_title ?? '') ?></h2>
