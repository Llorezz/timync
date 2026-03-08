<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timync – <?php echo e($title ?? 'Panel'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        .mono { font-family: 'DM Mono', monospace; }
        :root {
            --primary: #0f4c81;
            --primary-light: #1a6bb5;
            --accent: #00b4d8;
            --sidebar-bg: #0a1628;
            --sidebar-hover: #162544;
            --sidebar-active: #1a3a6b;
        }
        .sidebar { background: var(--sidebar-bg); min-height: 100vh; width: 260px; flex-shrink: 0; }
        .nav-item { transition: all 0.2s ease; border-left: 3px solid transparent; }
        .nav-item:hover { background: var(--sidebar-hover); border-left-color: var(--accent); }
        .nav-item.active { background: var(--sidebar-active); border-left-color: var(--accent); }
        .main-content { flex: 1; background: #f0f4f8; min-height: 100vh; }
        .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .metric-card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-top: 4px solid var(--accent); }
        .btn-primary { background: var(--primary); color: white; padding: 8px 18px; border-radius: 8px; font-weight: 500; font-size: 14px; transition: background 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary:hover { background: var(--primary-light); }
        .btn-danger { color: #dc2626; font-size: 14px; font-weight: 500; }
        .btn-danger:hover { color: #991b1b; }
        .btn-edit { color: var(--primary); font-size: 14px; font-weight: 500; }
        .btn-edit:hover { color: var(--primary-light); }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #f8fafc; color: #64748b; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        tbody td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 14px; }
        tbody tr:hover { background: #f8fafc; }
        tbody tr:last-child td { border-bottom: none; }
        input, select, textarea { border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; width: 100%; font-size: 14px; color: #334155; transition: border-color 0.2s, box-shadow 0.2s; outline: none; }
        input:focus, select:focus, textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0,180,216,0.1); }
        label { font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; display: block; }
        .badge { padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .badge-pendiente { background: #fef3c7; color: #92400e; }
        .badge-confirmada { background: #d1fae5; color: #065f46; }
        .badge-cancelada { background: #fee2e2; color: #991b1b; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 16px; font-size: 14px; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 8px; padding: 12px 16px; font-size: 14px; }
    </style>
</head>
<body>
<div style="display:flex;">

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Logo -->
        <div style="padding: 28px 24px 24px; border-bottom: 1px solid #162544;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:36px; height:36px; background: var(--accent); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <svg width="20" height="20" fill="white" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>
                </div>
                <span style="color:white; font-size:20px; font-weight:700; letter-spacing:-0.5px;">Timync</span>
            </div>
            <div style="color:#64748b; font-size:12px; margin-top:6px; padding-left:46px;">Panel de gestión</div>
        </div>

        <!-- Usuario -->
        <div style="padding: 16px 24px; border-bottom: 1px solid #162544;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:32px; height:32px; background: var(--primary-light); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-size:13px; font-weight:600;">
                    <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                </div>
                <div>
                    <div style="color:white; font-size:13px; font-weight:500;"><?php echo e(Auth::user()->name); ?></div>
                    <div style="color:#64748b; font-size:11px;"><?php echo e(Auth::user()->email); ?></div>
                </div>
            </div>
        </div>

        <!-- Navegación -->
        <nav style="padding: 16px 0;">
            <div style="padding: 0 16px; margin-bottom: 8px;">
                <span style="color:#475569; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.1em;">Principal</span>
            </div>

            <a href="<?php echo e(route('dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" style="display:flex; align-items:center; gap:12px; padding:11px 24px; color:#94a3b8; text-decoration:none; font-size:14px; font-weight:500;">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                Dashboard
            </a>

<a href="<?php echo e(route('clientes.index')); ?>" class="nav-item <?php echo e(request()->routeIs('clientes.*') ? 'active' : ''); ?>" style="display:flex; align-items:center; gap:12px; padding:11px 24px; color:#94a3b8; text-decoration:none; font-size:14px; font-weight:500;">
    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
    Clientes
</a>
            <a href="<?php echo e(route('empleados.index')); ?>" class="nav-item <?php echo e(request()->routeIs('empleados.*') ? 'active' : ''); ?>" style="display:flex; align-items:center; gap:12px; padding:11px 24px; color:#94a3b8; text-decoration:none; font-size:14px; font-weight:500;">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                Empleados
            </a>

            <a href="<?php echo e(route('servicios.index')); ?>" class="nav-item <?php echo e(request()->routeIs('servicios.*') ? 'active' : ''); ?>" style="display:flex; align-items:center; gap:12px; padding:11px 24px; color:#94a3b8; text-decoration:none; font-size:14px; font-weight:500;">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
                Servicios
            </a>

<a href="<?php echo e(route('citas.index')); ?>" class="nav-item <?php echo e(request()->routeIs('citas.index') ? 'active' : ''); ?>" style="display:flex; align-items:center; gap:12px; padding:11px 24px; color:#94a3b8; text-decoration:none; font-size:14px; font-weight:500;">
    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
    Citas
</a>

<a href="<?php echo e(route('citas.calendario')); ?>" class="nav-item <?php echo e(request()->routeIs('citas.calendario') ? 'active' : ''); ?>" style="display:flex; align-items:center; gap:12px; padding:11px 24px; color:#94a3b8; text-decoration:none; font-size:14px; font-weight:500;">
    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H4V8h16v13z"/></svg>
    Calendario
</a>
            <div style="padding: 16px 16px 8px; margin-top:8px;">
                <span style="color:#475569; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.1em;">Sistema</span>
            </div>
<a href="<?php echo e(route('estadisticas')); ?>" class="nav-item <?php echo e(request()->routeIs('estadisticas') ? 'active' : ''); ?>" style="display:flex; align-items:center; gap:12px; padding:11px 24px; color:#94a3b8; text-decoration:none; font-size:14px; font-weight:500;">
    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H7v-2h5v2zm5-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
    Estadísticas
</a>
<a href="<?php echo e(route('automatizaciones.index')); ?>" class="nav-item <?php echo e(request()->routeIs('automatizaciones.*') ? 'active' : ''); ?>" style="display:flex; align-items:center; gap:12px; padding:11px 24px; color:#94a3b8; text-decoration:none; font-size:14px; font-weight:500;">
    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
    Automatizaciones
</a>

<a href="<?php echo e(route('email-templates.index')); ?>" class="nav-item <?php echo e(request()->routeIs('email-templates.*') ? 'active' : ''); ?>" style="display:flex; align-items:center; gap:12px; padding:11px 24px; color:#94a3b8; text-decoration:none; font-size:14px; font-weight:500;">
    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
    Plantillas Email
</a>
<?php if(Auth::user()->isAdmin()): ?>
<a href="<?php echo e(route('admin.index')); ?>" class="nav-item <?php echo e(request()->is('admin*') ? 'active' : ''); ?>" style="display:flex; align-items:center; gap:12px; padding:11px 24px; color:#94a3b8; text-decoration:none; font-size:14px; font-weight:500;">
    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
    Administración
</a>
<?php endif; ?>
<a href="<?php echo e(route('configuracion.index')); ?>" class="nav-item <?php echo e(request()->routeIs('configuracion.*') ? 'active' : ''); ?>" style="display:flex; align-items:center; gap:12px; padding:11px 24px; color:#94a3b8; text-decoration:none; font-size:14px; font-weight:500;">
    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
    Configuración
</a>
        </nav>

        <!-- Cerrar sesión -->
        <div style="position:absolute; bottom:0; width:260px; padding:16px 24px; border-top:1px solid #162544;">
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" style="display:flex; align-items:center; gap:10px; color:#64748b; font-size:13px; font-weight:500; background:none; border:none; cursor:pointer; width:100%;">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Header -->
        <div style="background:white; border-bottom:1px solid #e2e8f0; padding:16px 32px; display:flex; align-items:center; justify-content:space-between;">
            <h1 style="font-size:18px; font-weight:600; color:#0f172a;">
                <?php echo e($header ?? 'Panel'); ?>

            </h1>
            <div style="font-size:13px; color:#64748b;">
                <?php echo e(now()->format('l, d \d\e F \d\e Y')); ?>

            </div>
        </div>

        <!-- Contenido -->
        <div style="padding:32px;">
            <?php echo e($slot); ?>

        </div>

        <!-- Footer -->
        <div style="text-align:center; padding:24px; color:#94a3b8; font-size:12px; border-top:1px solid #e2e8f0; margin-top:auto;">
            © <?php echo e(date('Y')); ?> Timync · Todos los derechos reservados
        </div>
    </div>

</div>
</body>
</html>
<?php /**PATH /home/u759498454/domains/timync.com/public_html/app/resources/views/layouts/app.blade.php ENDPATH**/ ?>