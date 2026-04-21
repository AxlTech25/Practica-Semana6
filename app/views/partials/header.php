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
            --bg: #f4f7fb;
            --card: #ffffff;
            --primary: #2b6cb0;
            --primary-dark: #1b4f8c;
            --text: #263238;
            --muted: #6b7280;
            --success: #16a34a;
            --danger: #dc2626;
            --border: #d1d5db;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Inter, system-ui, sans-serif; background: var(--bg); color: var(--text); }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 18px; gap: 16px; }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 4px 0 0; color: var(--muted); }
        .user-panel { font-size: 14px; color: var(--muted); }
        .user-panel a { color: var(--primary-dark); text-decoration: none; font-weight: 600; }
        .menu { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
        .menu a { display: inline-block; padding: 10px 14px; border-radius: 8px; background: #fff; border: 1px solid var(--border); color: var(--text); text-decoration: none; transition: background .2s ease; }
        .menu a.active, .menu a:hover { background: var(--primary); color: #fff; border-color: var(--primary); }
        .page-title { margin: 0 0 20px; font-size: 24px; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 24px; box-shadow: 0 8px 24px rgba(15,23,42,.04); }
        .grid { display: grid; gap: 18px; }
        .grid-3 { grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); }
        .grid-2 { grid-template-columns: repeat(auto-fit,minmax(300px,1fr)); }
        .button { display: inline-flex; align-items: center; justify-content: center; padding: 12px 18px; border: none; border-radius: 10px; text-decoration: none; color: #fff; background: var(--primary); cursor: pointer; transition: filter .2s ease; }
        .button:hover { filter: brightness(0.95); }
        .button-secondary { background: #475569; }
        .button-success { background: var(--success); }
        .button-danger { background: var(--danger); }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--border); background: #f8fafc; color: var(--text); }
        .form-group textarea { min-height: 120px; resize: vertical; }
        .table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .table th, .table td { padding: 12px 10px; border: 1px solid var(--border); text-align: left; }
        .table th { background: #eef2ff; font-weight: 700; }
        .badge { display: inline-flex; align-items: center; padding: 6px 10px; border-radius: 999px; font-size: 13px; font-weight: 700; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .message { border-radius: 12px; padding: 16px 18px; margin-bottom: 20px; }
        .message.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .message.success { background: #d1fae5; color: #166534; border: 1px solid #a7f3d0; }
        .pagination { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 20px; }
        .pagination a { padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); text-decoration: none; color: var(--text); }
        .pagination a.active { background: var(--primary); color: #fff; border-color: var(--primary); }
        .section-row { display: grid; gap: 16px; grid-template-columns: 1fr; }
        .stats-grid { display: grid; gap: 16px; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); }
        .stat-card { padding: 18px 20px; border-radius: 16px; border: 1px solid var(--border); background: #fff; }
        .stat-card strong { display: block; font-size: 28px; margin-bottom: 8px; }
        .footer { margin-top: 24px; padding-top: 16px; color: var(--muted); font-size: 13px; text-align: center; }
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
