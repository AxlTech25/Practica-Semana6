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
    <style>
        :root {
            --bg: #eff6ff;
            --surface: rgba(255,255,255,0.88);
            --card: #ffffff;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --text: #0f172a;
            --muted: #475569;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --border: rgba(148,163,184,.22);
            --shadow: 0 24px 80px rgba(15,23,42,.08);
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, system-ui, sans-serif;
            background: radial-gradient(circle at top left, #eef6ff 0%, #f8fafc 38%, #eff6ff 100%);
            color: var(--text);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 28px 20px 40px;
        }
        .header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 16px;
        }
        .header h1 {
            margin: 0;
            font-size: clamp(1.9rem, 2.4vw, 2.6rem);
        }
        .header p {
            margin: 6px 0 0;
            color: var(--muted);
        }
        .user-panel {
            font-size: 14px;
            color: var(--muted);
        }
        .user-panel a {
            color: var(--primary-dark);
            text-decoration: none;
            font-weight: 600;
        }
        .menu {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 24px;
        }
        .menu a {
            display: inline-flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 12px;
            background: rgba(255,255,255,0.95);
            border: 1px solid var(--border);
            color: var(--text);
            text-decoration: none;
            font-size: 0.95rem;
            transition: transform .2s ease, background .2s ease, color .2s ease, border-color .2s ease;
        }
        .menu a:hover,
        .menu a.active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            transform: translateY(-1px);
        }
        .page-title {
            margin: 0 0 22px;
            font-size: clamp(1.75rem, 2vw, 2.2rem);
            letter-spacing: -0.03em;
        }
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 28px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 28px 90px rgba(15,23,42,.12);
        }
        .grid {
            display: grid;
            gap: 20px;
        }
        .grid-3 {
            grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
        }
        .grid-2 {
            grid-template-columns: repeat(auto-fit,minmax(320px,1fr));
        }
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 20px;
            border: none;
            border-radius: 14px;
            text-decoration: none;
            color: #fff;
            background: var(--primary);
            cursor: pointer;
            transition: transform .2s ease, filter .2s ease, box-shadow .2s ease;
            box-shadow: 0 12px 30px rgba(37,99,235,.2);
        }
        .button:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }
        .button-secondary {
            background: #475569;
            box-shadow: 0 12px 30px rgba(71,85,105,.18);
        }
        .button-success {
            background: var(--success);
        }
        .button-danger {
            background: var(--danger);
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text);
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .form-group textarea {
            min-height: 130px;
            resize: vertical;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: rgba(37,99,235,.7);
            box-shadow: 0 0 0 4px rgba(59,130,246,.12);
            outline: none;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        .table th,
        .table td {
            padding: 14px 12px;
            border: 1px solid var(--border);
            text-align: left;
            vertical-align: middle;
        }
        .table th {
            background: #eef4ff;
            font-weight: 700;
        }
        .table tbody tr:hover {
            background: #f8fbff;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fff7cd; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .message {
            border-radius: 18px;
            padding: 18px 20px;
            margin-bottom: 24px;
            line-height: 1.55;
        }
        .message.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .message.success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #a7f3d0;
        }
        .pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 24px;
        }
        .pagination a {
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid var(--border);
            text-decoration: none;
            color: var(--text);
            transition: background .2s ease, border-color .2s ease;
        }
        .pagination a.active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }
        .section-row { display: grid; gap: 20px; grid-template-columns: 1fr; }
        .stats-grid { display: grid; gap: 18px; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); }
        .stat-card { padding: 22px 24px; border-radius: 22px; border: 1px solid var(--border); background: #fff; }
        .stat-card strong { display: block; font-size: 2rem; margin-bottom: 10px; }
        .footer { margin-top: 28px; padding-top: 18px; color: var(--muted); font-size: 13px; text-align: center; }
        @media (max-width: 660px) {
            .menu { justify-content: center; }
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="container">
    <header class="header">
        <div>
            <h1>Control de Visitantes</h1>
            <p>Sistema de registro y reportes de visitas</p>
        </div>
        <div class="user-panel">
            <?= htmlspecialchars($userName) ?>
            | <a href="index.php?accion=logout">Cerrar sesión</a>
        </div>
    </header>
    <nav class="menu">
        <a href="index.php?pagina=dashboard" class="<?= $paginaActual === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
        <a href="index.php?pagina=registro" class="<?= $paginaActual === 'registro' ? 'active' : '' ?>">Registrar visita</a>
        <a href="index.php?pagina=lista" class="<?= $paginaActual === 'lista' ? 'active' : '' ?>">Lista</a>
        <a href="index.php?pagina=busqueda" class="<?= $paginaActual === 'busqueda' ? 'active' : '' ?>">Buscar</a>
        <a href="index.php?pagina=reportes" class="<?= $paginaActual === 'reportes' ? 'active' : '' ?>">Reportes</a>
        <a href="index.php?pagina=historial" class="<?= $paginaActual === 'historial' ? 'active' : '' ?>">Historial</a>
    </nav>
    <main>
        <h2 class="page-title"><?= htmlspecialchars($page_title ?? '') ?></h2>
