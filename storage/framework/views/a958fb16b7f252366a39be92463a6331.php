<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($negocio->nombre_negocio ?? $negocio->name); ?></title>
    
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:#f0f4f8;color:#0f172a}
        :root{--primary:#0f4c81;--primary-light:#e8f0f9;--gradient:linear-gradient(135deg,#0f4c81,#1a6eb5)}
        .hero{position:relative;height:320px;overflow:hidden}
        .hero-bg{position:absolute;inset:0;background-size:cover;background-position:center;filter:brightness(0.45)}
        .hero-overlay{position:absolute;inset:0;background:linear-gradient(to bottom,rgba(15,76,129,0.2) 0%,rgba(10,30,60,0.88) 100%)}
        .hero-content{position:relative;z-index:2;height:100%;display:flex;flex-direction:column;justify-content:flex-end;padding:24px 20px;max-width:1000px;margin:0 auto}
        .hero-avatar{width:64px;height:64px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,0.4);margin-bottom:12px}
        .hero-avatar-placeholder{width:64px;height:64px;border-radius:50%;background:rgba(255,255,255,0.15);border:3px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:white;margin-bottom:12px}
        .hero h1{color:white;font-size:24px;font-weight:800;letter-spacing:-0.5px;text-shadow:0 2px 8px rgba(0,0,0,0.3)}
        .hero-meta{display:flex;gap:12px;margin-top:8px;flex-wrap:wrap}
        .hero-meta span{color:rgba(255,255,255,0.85);font-size:12px;display:flex;align-items:center;gap:4px}
        .social-links{display:flex;gap:8px;margin-top:12px;flex-wrap:wrap}
        .social-btn{display:flex;align-items:center;gap:5px;padding:6px 12px;border-radius:20px;background:rgba(255,255,255,0.15);backdrop-filter:blur(8px);color:white;text-decoration:none;font-size:11px;font-weight:600;border:1px solid rgba(255,255,255,0.2);transition:all 0.2s}
        .social-btn:hover{background:rgba(255,255,255,0.25)}
        .page-container{max-width:1000px;margin:0 auto;padding:20px 16px 80px}
        .grid{display:grid;grid-template-columns:1fr 300px;gap:20px}
        .card{background:white;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);overflow:hidden;margin-bottom:16px}
        .card-body{padding:20px}
        .section-title{font-size:14px;font-weight:700;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px}
        .servicio-card{border:1px solid #e8f0f9;border-radius:12px;overflow:hidden;transition:all 0.2s;margin-bottom:10px}
        .servicio-card:hover{border-color:#0f4c81;box-shadow:0 4px 20px rgba(15,76,129,0.1)}
        .servicio-img{width:88px;height:88px;object-fit:cover;flex-shrink:0}
        .servicio-img-placeholder{width:88px;height:88px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0}
        .price-tag{font-size:18px;font-weight:800;color:var(--primary)}
        .duration-tag{font-size:11px;color:#94a3b8;background:#f1f5f9;padding:3px 8px;border-radius:20px}
        .btn-reservar{background:var(--gradient);color:white;padding:8px 16px;border-radius:8px;font-weight:600;border:none;cursor:pointer;font-size:13px;transition:opacity 0.2s;white-space:nowrap}
        .btn-reservar:hover{opacity:0.9}
        .info-item{display:flex;gap:12px;padding:10px 0;border-bottom:1px solid #f1f5f9;align-items:flex-start}
        .info-item:last-child{border-bottom:none}
        .info-icon{width:34px;height:34px;background:var(--primary-light);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
        .info-label{font-size:10px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em}
        .info-value{font-size:13px;color:#334155;font-weight:500;margin-top:2px}
        #map{height:200px;border-radius:0 0 12px 12px;overflow:hidden;} #map iframe{width:100%;height:100%;border:none;display:block;}
        .search-input{width:100%;padding:10px 16px 10px 38px;border:1px solid #e2e8f0;border-radius:10px;font-size:13px;outline:none;background:#f8fafc}
        .search-input:focus{border-color:var(--primary);background:white}
        .search-wrap{position:relative;margin-bottom:16px}
        .search-wrap::before{content:'🔍';position:absolute;left:11px;top:50%;transform:translateY(-50%);font-size:13px}
        .galeria-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:6px}
        .galeria-img{width:100%;height:80px;object-fit:cover;border-radius:8px}
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(10,20,40,0.6);z-index:1000;align-items:flex-end;justify-content:center;backdrop-filter:blur(4px)}
        .modal-overlay.open{display:flex}
        .modal{background:white;border-radius:20px 20px 0 0;width:100%;max-height:92vh;overflow-y:auto;box-shadow:0 -8px 40px rgba(0,0,0,0.2)}
        .modal-header{padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;background:var(--gradient);border-radius:20px 20px 0 0;position:sticky;top:0;z-index:10}
        .modal-header h3{color:white;font-size:15px;font-weight:700}
        .modal-header p{color:rgba(255,255,255,0.75);font-size:12px;margin-top:2px}
        .modal-close{background:rgba(255,255,255,0.2);border:none;color:white;width:30px;height:30px;border-radius:50%;cursor:pointer;font-size:15px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .modal-body{padding:20px}
        .step-bar{display:flex;gap:6px;margin-bottom:20px}
        .step-bar-item{flex:1;height:4px;border-radius:2px;background:#e2e8f0;transition:background 0.3s}
        .step-bar-item.active{background:var(--primary)}
        .step{display:none}
        .step.active{display:block}
        .slot{padding:8px 4px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;cursor:pointer;background:white;text-align:center;transition:all 0.15s;font-weight:500;width:100%}
        .slot:hover{border-color:var(--primary);color:var(--primary);background:var(--primary-light)}
        .slot.selected{background:var(--primary);color:white;border-color:var(--primary)}
        .form-group{margin-bottom:14px}
        .input-field{width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;transition:border 0.2s;background:#fafafa}
        .input-field:focus{border-color:var(--primary);background:white}
        .btn-submit{width:100%;padding:14px;background:var(--gradient);color:white;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;transition:opacity 0.2s}
        .btn-submit:hover{opacity:0.9}
        .btn-submit:disabled{opacity:0.4;cursor:not-allowed}
        .btn-back{flex:1;padding:12px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#64748b;background:white;cursor:pointer;font-weight:500}
        .resumen-box{background:linear-gradient(135deg,#e8f0f9,#f0f4f8);border-radius:12px;padding:14px;margin-bottom:16px;border-left:4px solid var(--primary)}
        /* CALENDARIO CUSTOM */
        .cal-wrap{border-radius:14px;overflow:hidden;box-shadow:0 4px 20px rgba(15,76,129,0.1);margin-bottom:8px}
        .cal-header{background:var(--gradient);padding:14px 12px;display:flex;align-items:center;justify-content:space-between}
        .cal-title{color:white;font-size:15px;font-weight:700;flex:1;text-align:center}
        .cal-btn{background:rgba(255,255,255,0.2);border:none;color:white;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:20px;line-height:1;display:flex;align-items:center;justify-content:center}
        .cal-btn:hover{background:rgba(255,255,255,0.35)}
        .cal-weekdays{background:var(--gradient);display:grid;grid-template-columns:repeat(7,1fr);padding:0 8px 10px}
        .cal-wd{color:rgba(255,255,255,0.85);font-size:11px;font-weight:600;text-align:center;padding:2px 0}
        .cal-days{background:white;display:grid;grid-template-columns:repeat(7,1fr);padding:8px;gap:3px}
        .cal-day{height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:500;cursor:pointer;position:relative;border:1.5px solid transparent;transition:all 0.15s;color:#334155;user-select:none}
        .cal-day:hover:not(.cd-dis):not(.cd-empty):not(.cd-lleno):not(.cd-cerrado){border-color:var(--primary);color:var(--primary);background:var(--primary-light)}
        .cd-sel{background:var(--primary)!important;color:white!important;border-color:var(--primary)!important;font-weight:700!important}
        .cd-today{border-color:var(--primary);color:var(--primary);font-weight:700}
        .cd-dis{color:#cbd5e1;cursor:default;opacity:0.5}
        .cd-empty{cursor:default}
        .cd-cerrado{color:#cbd5e1!important;cursor:not-allowed;background:#f8fafc;text-decoration:line-through}
        .cd-lleno{background:#fee2e2!important;color:#ef4444!important;cursor:not-allowed}
        .cd-dot::after{content:'';position:absolute;bottom:3px;left:50%;transform:translateX(-50%);width:5px;height:5px;border-radius:50%}
        .cd-libre::after{background:#22c55e}
        .cd-medio::after{background:#eab308}
        .cd-alto::after{background:#f97316}
        .cal-leyenda{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px}
        .cal-leyenda-item{display:flex;align-items:center;gap:5px;font-size:11px;color:#64748b}
        .cal-dot-s{width:7px;height:7px;border-radius:50%;flex-shrink:0}
        @media(min-width:768px){
            .hero{height:380px}.hero-content{padding:40px}.hero h1{font-size:32px}
            .hero-avatar,.hero-avatar-placeholder{width:80px;height:80px;font-size:32px}
            .modal-overlay{align-items:center}
            .modal{border-radius:20px;max-width:520px;max-height:90vh}
            .servicio-img,.servicio-img-placeholder{width:100px;height:100px}
            .price-tag{font-size:20px}
        }
        @media(max-width:767px){
            .grid{grid-template-columns:1fr}.sidebar-order{order:-1}
            .galeria-img{height:70px}
    
        }
    </style>
</head>
<body>
<div class="hero">
    <?php if($negocio->fotos_galeria && count($negocio->fotos_galeria) > 0): ?>
        <div class="hero-bg" style="background-image:url('<?php echo e(asset('storage/' . $negocio->fotos_galeria[0])); ?>')"></div>
    <?php elseif($negocio->foto_portada): ?>
        <div class="hero-bg" style="background-image:url('<?php echo e(asset('storage/' . $negocio->foto_portada)); ?>')"></div>
    <?php else: ?>
        <div class="hero-bg" style="background:linear-gradient(135deg,#0f4c81,#1a3a5c)"></div>
    <?php endif; ?>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <?php if($negocio->foto_portada): ?>
            <img src="<?php echo e(asset('storage/' . $negocio->foto_portada)); ?>" class="hero-avatar" alt="">
        <?php else: ?>
            <div class="hero-avatar-placeholder"><?php echo e(strtoupper(substr($negocio->nombre_negocio ?? $negocio->name, 0, 1))); ?></div>
        <?php endif; ?>
        <h1><?php echo e($negocio->nombre_negocio ?? $negocio->name); ?></h1>
        <div class="hero-meta">
            <?php if($negocio->ciudad): ?><span>📍 <?php echo e($negocio->ciudad); ?></span><?php endif; ?>
            <?php if($negocio->horario_apertura && $negocio->horario_cierre): ?><span>🕐 <?php echo e($negocio->horario_apertura); ?> – <?php echo e($negocio->horario_cierre); ?></span><?php endif; ?>
            <?php if($negocio->telefono_negocio): ?><span>📞 <?php echo e($negocio->telefono_negocio); ?></span><?php endif; ?>
        </div>
        <div class="social-links">
            <?php if($negocio->instagram): ?>
            <a href="https://instagram.com/<?php echo e(ltrim($negocio->instagram,'@')); ?>" target="_blank" class="social-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                Instagram
            </a>
            <?php endif; ?>
            <?php if($negocio->facebook): ?>
            <a href="<?php echo e($negocio->facebook); ?>" target="_blank" class="social-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Facebook
            </a>
            <?php endif; ?>
            <?php if($negocio->whatsapp_negocio): ?>
            <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/','',$negocio->whatsapp_negocio)); ?>" target="_blank" class="social-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                WhatsApp
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="page-container">
    <div class="grid">
        <div>
            <?php if($negocio->descripcion_negocio): ?>
            <div class="card"><div class="card-body">
                <div class="section-title">ℹ️ Sobre nosotros</div>
                <p style="color:#475569;font-size:14px;line-height:1.8;"><?php echo e($negocio->descripcion_negocio); ?></p>
            </div></div>
            <?php endif; ?>
            <?php if($negocio->fotos_galeria && count($negocio->fotos_galeria) > 0): ?>
            <div class="card"><div class="card-body">
                <div class="section-title">🖼️ Galería</div>
                <div class="galeria-grid">
                    <?php $__currentLoopData = $negocio->fotos_galeria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('storage/' . $foto)); ?>" class="galeria-img" alt="">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div></div>
            <?php endif; ?>
            <div class="card"><div class="card-body">
                <div class="section-title">✂️ Servicios</div>
                <div class="search-wrap">
                    <input type="text" class="search-input" placeholder="Buscar servicio..." oninput="filtrarServicios(this.value)">
                </div>
                <div id="lista-servicios">
                    <?php $__currentLoopData = $servicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $servicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="servicio-card" data-nombre="<?php echo e(strtolower($servicio->nombre)); ?>">
                        <div style="display:flex;">
                            <?php if($servicio->foto): ?>
                                <img src="<?php echo e(asset('storage/' . $servicio->foto)); ?>" class="servicio-img" alt="">
                            <?php else: ?>
                                <div class="servicio-img-placeholder">✂️</div>
                            <?php endif; ?>
                            <div style="padding:14px;flex:1;display:flex;flex-direction:column;justify-content:space-between;min-width:0;">
                                <div>
                                    <div style="font-weight:700;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($servicio->nombre); ?></div>
                                    <?php if($servicio->descripcion): ?>
                                        <div style="font-size:12px;color:#64748b;margin-top:3px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?php echo e($servicio->descripcion); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;gap:8px;">
                                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                        <span class="price-tag"><?php echo e(number_format($servicio->precio,2)); ?>€</span>
                                        <span class="duration-tag"><?php echo e($servicio->duracion_minutos); ?> min</span>
                                    </div>
                                    <button class="btn-reservar" onclick="abrirReserva(<?php echo e($servicio->id); ?>,'<?php echo e(addslashes($servicio->nombre)); ?>',<?php echo e($servicio->duracion_minutos); ?>,<?php echo e($servicio->empleado_id ?? 'null'); ?>)">Reservar</button>
                                </div>
                            </div>
                        </div>
                        <?php if($servicio->descripcion_larga): ?>
                        <div style="padding:10px 14px;font-size:12px;color:#64748b;border-top:1px solid #f1f5f9;line-height:1.6;background:#fafcff;"><?php echo e($servicio->descripcion_larga); ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div></div>
        </div>
        <div class="sidebar-order">
            <div class="card">
                <div class="card-body">
                    <div class="section-title">📍 Información</div>
                    <?php if($negocio->direccion): ?>
                    <div class="info-item"><div class="info-icon">📍</div><div><div class="info-label">Dirección</div><div class="info-value"><?php echo e($negocio->direccion); ?><?php echo e($negocio->ciudad ? ', '.$negocio->ciudad : ''); ?></div></div></div>
                    <?php endif; ?>
                    <?php if($negocio->telefono_negocio): ?>
                    <div class="info-item"><div class="info-icon">📞</div><div><div class="info-label">Teléfono</div><div class="info-value"><a href="tel:<?php echo e($negocio->telefono_negocio); ?>" style="color:var(--primary);text-decoration:none;"><?php echo e($negocio->telefono_negocio); ?></a></div></div></div>
                    <?php endif; ?>
                    <?php if($negocio->horario_apertura && $negocio->horario_cierre): ?>
                    <div class="info-item"><div class="info-icon">🕐</div><div><div class="info-label">Horario</div><div class="info-value"><?php echo e($negocio->horario_apertura); ?> – <?php echo e($negocio->horario_cierre); ?></div></div></div>
                    <?php endif; ?>
                    <?php if($setting?->negocio_email): ?>
                    <div class="info-item"><div class="info-icon">✉️</div><div><div class="info-label">Email</div><div class="info-value"><a href="mailto:<?php echo e($setting->negocio_email); ?>" style="color:var(--primary);text-decoration:none;"><?php echo e($setting->negocio_email); ?></a></div></div></div>
                    <?php endif; ?>
                </div>
                <?php if($negocio->direccion): ?>
<div id="map">
    <iframe src="https://www.google.com/maps?q=<?php echo e(urlencode($negocio->direccion . ", " . $negocio->ciudad)); ?>&output=embed&z=16" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="modal-overlay" id="modal-reserva">
    <div class="modal">
        <div class="modal-header">
            <div><p>Reservar servicio</p><h3 id="modal-titulo"></h3></div>
            <button class="modal-close" onclick="cerrarModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="step-bar">
                <div class="step-bar-item active" id="bar1"></div>
                <div class="step-bar-item" id="bar2"></div>
            </div>
            <form action="<?php echo e(route('negocio.store', $negocio->slug)); ?>" method="POST" id="form-reserva">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="servicio_id" id="input-servicio-id">
                <input type="hidden" name="empleado_id" id="input-empleado-id">
                <input type="hidden" name="fecha_hora" id="input-fecha-hora">
                <div class="step active" id="step1">
                    <div class="form-group">
                        <label style="font-size:13px;font-weight:600;color:#475569;display:block;margin-bottom:10px;">Selecciona una fecha</label>
                        <div class="cal-wrap">
                            <div class="cal-header">
                                <button type="button" class="cal-btn" onclick="calNavegar(-1)">&#8249;</button>
                                <div class="cal-title" id="cal-titulo"></div>
                                <button type="button" class="cal-btn" onclick="calNavegar(1)">&#8250;</button>
                            </div>
                            <div class="cal-weekdays">
                                <div class="cal-wd">Lun</div><div class="cal-wd">Mar</div><div class="cal-wd">Mié</div>
                                <div class="cal-wd">Jue</div><div class="cal-wd">Vie</div><div class="cal-wd">Sáb</div><div class="cal-wd">Dom</div>
                            </div>
                            <div class="cal-days" id="cal-days"></div>
                        </div>
                        <div class="cal-leyenda">
                            <div class="cal-leyenda-item"><div class="cal-dot-s" style="background:#22c55e"></div> Disponible</div>
                            <div class="cal-leyenda-item"><div class="cal-dot-s" style="background:#eab308"></div> Poca disp.</div>
                            <div class="cal-leyenda-item"><div class="cal-dot-s" style="background:#f97316"></div> Muy ocupado</div>
                            <div class="cal-leyenda-item"><div class="cal-dot-s" style="background:#ef4444"></div> Sin hueco</div>
                        </div>
                    </div>
                    <div id="slots-loading" style="display:none;text-align:center;padding:16px;color:#94a3b8;font-size:13px;">⏳ Cargando horarios...</div>
                    <div id="slots-empty" style="display:none;text-align:center;padding:16px;color:#94a3b8;font-size:13px;">😔 No hay horas disponibles este día.</div>
                    <div id="slots-container" style="display:none;">
                        <label style="font-size:13px;font-weight:600;color:#475569;display:block;margin-bottom:8px;">Hora disponible</label>
                        <div id="slots-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;"></div>
                    </div>
                    <button type="button" onclick="irPaso(2)" id="btn-paso2" class="btn-submit" style="margin-top:20px;" disabled>Siguiente →</button>
                </div>
                <div class="step" id="step2">
                    <?php if(session("social_cliente") && !session("social_cliente.politica_aceptada")): ?>
                    <div style="position:fixed;inset:0;background:rgba(10,20,40,0.7);z-index:2000;display:flex;align-items:flex-end;justify-content:center;backdrop-filter:blur(4px);">
                        <div style="background:white;border-radius:20px 20px 0 0;width:100%;max-width:560px;max-height:88vh;display:flex;flex-direction:column;">
                            <div style="background:linear-gradient(135deg,#0f4c81,#1a6eb5);padding:20px 24px;border-radius:20px 20px 0 0;flex-shrink:0;">
                                <div style="color:white;font-size:16px;font-weight:700;">Terminos y Privacidad</div>
                                <div style="display:flex;gap:4px;margin-top:14px;">
                                    <button type="button" onclick="tabLegal(1)" id="tab1" style="flex:1;padding:8px;border-radius:8px;border:none;font-size:12px;font-weight:700;cursor:pointer;background:white;color:#0f4c81;">Privacidad</button>
                                    <button type="button" onclick="tabLegal(2)" id="tab2" style="flex:1;padding:8px;border-radius:8px;border:none;font-size:12px;font-weight:700;cursor:pointer;background:rgba(255,255,255,0.2);color:white;">Terminos</button>
                                </div>
                            </div>
                            <div style="overflow-y:auto;padding:20px 24px;flex:1;font-size:13px;color:#334155;line-height:1.7;" id="legal-content">
                                <div id="legal-tab1">
                                    <p>En Timync respetamos tu privacidad. No vendemos datos.</p>
                                    <p>Email: <a href="mailto:info@timync.com" style="color:#0f4c81;">info@timync.com</a></p>
                                </div>
                                <div id="legal-tab2" style="display:none;">
                                    <p>Al usar Timync aceptas estos terminos de uso.</p>
                                    <p>Email: <a href="mailto:soporte@timync.com" style="color:#0f4c81;">soporte@timync.com</a></p>
                                </div>
                            </div>
                            <div style="padding:16px 24px;border-top:1px solid #f1f5f9;flex-shrink:0;background:white;">
                                <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;padding:12px;background:#f8fafc;border-radius:10px;">
                                    <input type="checkbox" id="chk-acepto" onchange="var b=document.getElementById('btn-acepto');b.disabled=!this.checked;b.style.opacity=this.checked?'1':'0.4'" style="margin-top:2px;width:16px;height:16px;flex-shrink:0;accent-color:#0f4c81;">
                                    <label for="chk-acepto" style="font-size:12px;color:#475569;cursor:pointer;line-height:1.5;">He leido y acepto la Politica de Privacidad y los Terminos y Condiciones.</label>
                                </div>
                                <button type="button" id="btn-acepto" disabled onclick="aceptarPolitica()" style="width:100%;padding:14px;background:linear-gradient(135deg,#0f4c81,#1a6eb5);color:white;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;opacity:0.4;transition:opacity 0.2s;">Acepto y continuar</button>
                                <form action="/auth/logout/<?php echo e($negocio->slug); ?>" method="GET" style="margin-top:8px;">
                                    <button type="submit" style="width:100%;padding:10px;background:white;color:#94a3b8;border:1.5px solid #e2e8f0;border-radius:12px;font-size:13px;cursor:pointer;">No acepto - cancelar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="resumen-box" id="resumen-reserva"></div>
                    <?php if(session("social_cliente") && session("social_cliente.politica_aceptada")): ?>
                    <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#166534;"><?php echo e(session('social_cliente.nombre')); ?></div>
                            <div style="font-size:12px;color:#16a34a;"><?php echo e(session('social_cliente.email')); ?></div>
                        </div>
                        <a href="/auth/logout/<?php echo e($negocio->slug); ?>" style="margin-left:auto;font-size:11px;color:#94a3b8;text-decoration:none;">Cambiar</a>
                    </div>
                    <input type="hidden" name="cliente_id" value="<?php echo e(session('social_cliente.id')); ?>">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="form-group"><label style="font-size:13px;font-weight:600;color:#475569;display:block;margin-bottom:6px;">Nombre *</label><input class="input-field" type="text" name="nombre" required placeholder="Tu nombre" value="<?php echo e(session('social_cliente.nombre')); ?>"></div>
                        <div class="form-group"><label style="font-size:13px;font-weight:600;color:#475569;display:block;margin-bottom:6px;">Telefono *</label><input class="input-field" type="tel" name="telefono" required placeholder="+34 600..."></div>
                    </div>
                    <div class="form-group"><label style="font-size:13px;font-weight:600;color:#475569;display:block;margin-bottom:6px;">Email *</label><input class="input-field" type="email" name="email" required value="<?php echo e(session('social_cliente.email')); ?>"></div>
                    <div class="form-group"><label style="font-size:13px;font-weight:600;color:#475569;display:block;margin-bottom:6px;">Notas (opcional)</label><textarea class="input-field" name="notas" rows="2" placeholder="Peticion especial..."></textarea></div>
                    <div style="display:flex;gap:10px;margin-top:8px;">
                        <button type="button" class="btn-back" onclick="irPaso(1)">Atras</button>
                        <button type="submit" class="btn-submit" style="flex:2;">Confirmar reserva</button>
                    </div>
                    <?php else: ?>
                    <div style="text-align:center;padding:10px 0 16px;">
                        <div style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;">Identificate para continuar</div>
                        <div style="font-size:13px;color:#64748b;margin-bottom:20px;">Verifica tu identidad para completar la reserva</div>
                        <a href="#" onclick="guardarYGoogle()" style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:13px;border:1.5px solid #e2e8f0;border-radius:12px;background:white;text-decoration:none;color:#334155;font-size:14px;font-weight:600;margin-bottom:16px;">
                            <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                            Continuar con Google
                        </a>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                            <div style="flex:1;height:1px;background:#e2e8f0;"></div>
                            <span style="font-size:12px;color:#94a3b8;">o con tu email</span>
                            <div style="flex:1;height:1px;background:#e2e8f0;"></div>
                        </div>
                        <div id="otp-paso1">
                            <div style="display:flex;gap:8px;">
                                <input type="email" id="otp-email" placeholder="tu@email.com" style="flex:1;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;">
                                <button type="button" onclick="enviarOtp()" id="btn-otp-enviar" style="padding:11px 16px;background:linear-gradient(135deg,#0f4c81,#1a6eb5);color:white;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap;">Enviar codigo</button>
                            </div>
                            <div id="otp-error" style="display:none;color:#dc2626;font-size:12px;margin-top:6px;"></div>
                        </div>
                        <div id="otp-paso2" style="display:none;">
                            <div style="font-size:13px;color:#475569;margin-bottom:12px;">Codigo enviado a <strong id="otp-email-mostrado"></strong></div>
                            <div style="display:flex;gap:8px;justify-content:center;margin-bottom:12px;">
                                <input type="text" maxlength="1" class="otp-digit" style="width:44px;height:52px;text-align:center;font-size:22px;font-weight:700;border:1.5px solid #e2e8f0;border-radius:10px;outline:none;color:#0f4c81;">
                                <input type="text" maxlength="1" class="otp-digit" style="width:44px;height:52px;text-align:center;font-size:22px;font-weight:700;border:1.5px solid #e2e8f0;border-radius:10px;outline:none;color:#0f4c81;">
                                <input type="text" maxlength="1" class="otp-digit" style="width:44px;height:52px;text-align:center;font-size:22px;font-weight:700;border:1.5px solid #e2e8f0;border-radius:10px;outline:none;color:#0f4c81;">
                                <input type="text" maxlength="1" class="otp-digit" style="width:44px;height:52px;text-align:center;font-size:22px;font-weight:700;border:1.5px solid #e2e8f0;border-radius:10px;outline:none;color:#0f4c81;">
                                <input type="text" maxlength="1" class="otp-digit" style="width:44px;height:52px;text-align:center;font-size:22px;font-weight:700;border:1.5px solid #e2e8f0;border-radius:10px;outline:none;color:#0f4c81;">
                                <input type="text" maxlength="1" class="otp-digit" style="width:44px;height:52px;text-align:center;font-size:22px;font-weight:700;border:1.5px solid #e2e8f0;border-radius:10px;outline:none;color:#0f4c81;">
                            </div>
                            <button type="button" onclick="enviarOtpVerificar()" id="btn-otp-verificar" style="width:100%;padding:12px;background:linear-gradient(135deg,#0f4c81,#1a6eb5);color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;">Verificar codigo</button>
                            <div id="otp-error2" style="display:none;color:#dc2626;font-size:12px;margin-top:6px;"></div>
                            <button type="button" onclick="document.getElementById('otp-paso1').style.display='block';document.getElementById('otp-paso2').style.display='none'" style="background:none;border:none;color:#94a3b8;font-size:12px;cursor:pointer;margin-top:8px;">Cambiar email</button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
    </div>
</div>
<script>
const MESES=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
let servicioActual=null,ocupacionData={},calYear=0,calMonth=0,fechaSel=null;

function calNavegar(dir){
    calMonth+=dir;
    if(calMonth>11){calMonth=0;calYear++;}
    if(calMonth<0){calMonth=11;calYear--;}
    const mes=`${calYear}-${String(calMonth+1).padStart(2,'0')}`;
    const tieneData=Object.keys(ocupacionData).some(k=>k.startsWith(mes));
    if(!tieneData){cargarOcupacion(mes,()=>renderCal());}else{renderCal();}
}

function renderCal(){
    const hoy=new Date();hoy.setHours(0,0,0,0);
    const manana=new Date(hoy);manana.setDate(hoy.getDate()+1);
    document.getElementById('cal-titulo').textContent=`${MESES[calMonth]} ${calYear}`;
    let primerDia=new Date(calYear,calMonth,1).getDay();
    primerDia=primerDia===0?6:primerDia-1;
    const ultimoDia=new Date(calYear,calMonth+1,0).getDate();
    const grid=document.getElementById('cal-days');
    grid.innerHTML='';
    for(let i=0;i<primerDia;i++){
        const e=document.createElement('div');e.className='cal-day cd-empty';grid.appendChild(e);
    }
    for(let n=1;n<=ultimoDia;n++){
        const fecha=new Date(calYear,calMonth,n);fecha.setHours(0,0,0,0);
        const key=`${calYear}-${String(calMonth+1).padStart(2,'0')}-${String(n).padStart(2,'0')}`;
        const estado=ocupacionData[key];
        const d=document.createElement('div');
        d.textContent=n;
        let cls='cal-day';
        if(fecha<manana){cls+=' cd-dis';}
        else if(estado==='cerrado'){cls+=' cd-cerrado';}
        else if(estado==='lleno'){cls+=' cd-lleno cd-dot';}
        else{
            if(fecha.toDateString()===hoy.toDateString())cls+=' cd-today';
            if(fechaSel===key)cls+=' cd-sel';
            if(estado==='libre')cls+=' cd-dot cd-libre';
            else if(estado==='medio')cls+=' cd-dot cd-medio';
            else if(estado==='alto')cls+=' cd-dot cd-alto';
            d.onclick=()=>selFecha(key,d);
        }
        d.className=cls;
        grid.appendChild(d);
    }
}

function selFecha(key,elem){
    fechaSel=key;
    document.querySelectorAll('.cal-day').forEach(d=>d.classList.remove('cd-sel'));
    elem.classList.add('cd-sel');
    cargarSlots(key);
}

function cargarOcupacion(mes,cb){
    fetch(`/reserva/<?php echo e($negocio->slug); ?>/ocupacion?mes=${mes}`)
        .then(r=>r.json()).then(data=>{ocupacionData={...ocupacionData,...data};if(cb)cb();});
}

function abrirReserva(id,nombre,duracion,empleadoId){
    servicioActual={id,nombre,duracion,empleadoId};
    document.getElementById('input-servicio-id').value=id;
    document.getElementById('input-empleado-id').value=empleadoId||'';
    document.getElementById('modal-titulo').textContent=nombre;
    document.getElementById('modal-reserva').classList.add('open');
    document.body.style.overflow='hidden';
    irPaso(1);resetPaso1();fechaSel=null;
    const hoy=new Date();calYear=hoy.getFullYear();calMonth=hoy.getMonth();
    const mes=`${calYear}-${String(calMonth+1).padStart(2,'0')}`;
    cargarOcupacion(mes,()=>renderCal());
}

function resetPaso1(){
    ['slots-container','slots-empty','slots-loading'].forEach(id=>document.getElementById(id).style.display='none');
    document.getElementById('input-fecha-hora').value='';
    document.getElementById('btn-paso2').disabled=true;
}

function cerrarModal(){document.getElementById('modal-reserva').classList.remove('open');document.body.style.overflow='';}

function cargarSlots(fecha){
    if(!fecha||!servicioActual)return;
    document.getElementById('slots-container').style.display='none';
    document.getElementById('slots-empty').style.display='none';
    document.getElementById('slots-loading').style.display='block';
    document.getElementById('input-fecha-hora').value='';
    document.getElementById('btn-paso2').disabled=true;
    fetch(`/reserva/<?php echo e($negocio->slug); ?>/disponibilidad?fecha=${fecha}&servicio_id=${servicioActual.id}&empleado_id=${servicioActual.empleadoId||''}`)
        .then(r=>r.json()).then(slots=>{
            document.getElementById('slots-loading').style.display='none';
            if(!slots.length){document.getElementById('slots-empty').style.display='block';return;}
            document.getElementById('slots-container').style.display='block';
            const grid=document.getElementById('slots-grid');grid.innerHTML='';
            slots.forEach(slot=>{
                const btn=document.createElement('button');
                btn.type='button';btn.className='slot';btn.textContent=slot;
                btn.onclick=()=>selSlot(btn,fecha,slot);
                grid.appendChild(btn);
            });
        });
}

function selSlot(btn,fecha,hora){
    document.querySelectorAll('.slot').forEach(s=>s.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('input-fecha-hora').value=fecha+' '+hora+':00';
    document.getElementById('btn-paso2').disabled=false;
}

function irPaso(paso){
    document.querySelectorAll('.step').forEach(s=>s.classList.remove('active'));
    document.getElementById('step'+paso).classList.add('active');
    for(let i=1;i<=2;i++)document.getElementById('bar'+i).style.background=i<=paso?'#0f4c81':'#e2e8f0';
    if(paso===2)actualizarResumen();
}

function actualizarResumen(){
    const fh=document.getElementById('input-fecha-hora').value,f=fh.split(' ')[0],h=fh?fh.split(' ')[1].substring(0,5):'';
    const p=f?f.split('-'):[],fe=p.length===3?`${p[2]}/${p[1]}/${p[0]}`:'';
    document.getElementById('resumen-reserva').innerHTML=`
        <div style="font-weight:700;color:#0f172a;margin-bottom:8px;font-size:14px;">📋 Resumen</div>
        <div style="font-size:13px;color:#334155;display:flex;flex-direction:column;gap:4px;">
            <div>✂️ <strong>${servicioActual.nombre}</strong></div>
            <div>📅 <strong>${fe}</strong> a las <strong>${h}</strong></div>
            <div style="color:#94a3b8;">⏱ Duración: ${servicioActual.duracion} min</div>
        </div>`;
}

function filtrarServicios(texto){
    document.querySelectorAll('.servicio-card').forEach(c=>{c.style.display=c.dataset.nombre.includes(texto.toLowerCase())?'':'none';});
}


function tabLegal(n){
    document.getElementById("legal-tab1").style.display=n===1?"block":"none";
    document.getElementById("legal-tab2").style.display=n===2?"block":"none";
    document.getElementById("tab1").style.background=n===1?"white":"rgba(255,255,255,0.2)";
    document.getElementById("tab1").style.color=n===1?"#0f4c81":"white";
    document.getElementById("tab2").style.background=n===2?"white":"rgba(255,255,255,0.2)";
    document.getElementById("tab2").style.color=n===2?"#0f4c81":"white";
}
function enviarOtpVerificar(){
    if(servicioActual){
        sessionStorage.setItem("reserva_pendiente", JSON.stringify({
            servicioId: servicioActual.id,
            servicioNombre: servicioActual.nombre,
            servicioDuracion: servicioActual.duracion,
            empleadoId: servicioActual.empleadoId,
            fechaHora: document.getElementById("input-fecha-hora").value
        }));
    }
    verificarOtp();
}
function enviarOtp(){
    const email=document.getElementById("otp-email").value;
    const btn=document.getElementById("btn-otp-enviar");
    btn.disabled=true;btn.textContent="Enviando...";
    fetch("/auth/otp/enviar/<?php echo e($negocio->slug); ?>",{
        method:"POST",
        headers:{"Content-Type":"application/json","X-CSRF-TOKEN":"<?php echo e(csrf_token()); ?>"},
        body:JSON.stringify({email})
    }).then(r=>r.json()).then(d=>{
        if(d.ok){
            document.getElementById("otp-email-mostrado").textContent=email;
            document.getElementById("otp-paso1").style.display="none";
            document.getElementById("otp-paso2").style.display="block";
            setTimeout(()=>document.querySelectorAll(".otp-digit")[0].focus(),100);
        } else {
            document.getElementById("otp-error").textContent=d.error||"Error al enviar";
            document.getElementById("otp-error").style.display="block";
            btn.disabled=false;btn.textContent="Enviar código";
        }
    });
}
function verificarOtp(){
    const email=document.getElementById("otp-email").value;
    const codigo=Array.from(document.querySelectorAll(".otp-digit")).map(i=>i.value).join("");
    if(codigo.length<6){return;}
    const btn=document.getElementById("btn-otp-verificar");
    btn.disabled=true;btn.textContent="Verificando...";
    fetch("/auth/otp/verificar/<?php echo e($negocio->slug); ?>",{
        method:"POST",
        headers:{"Content-Type":"application/json","X-CSRF-TOKEN":"<?php echo e(csrf_token()); ?>"},
        body:JSON.stringify({email,codigo})
    }).then(r=>r.json()).then(d=>{
        if(d.ok){location.reload();}
        else{
            document.getElementById("otp-error2").textContent=d.error||"Código incorrecto";
            document.getElementById("otp-error2").style.display="block";
            btn.disabled=false;btn.textContent="Verificar código";
        }
    });
}
// Auto-avance entre dígitos OTP
document.addEventListener("input",function(e){
    if(e.target.classList.contains("otp-digit")){
        const inputs=Array.from(document.querySelectorAll(".otp-digit"));
        const idx=inputs.indexOf(e.target);
        if(e.target.value&&idx<5)inputs[idx+1].focus();
    }
});
document.addEventListener("keydown",function(e){
    if(e.target.classList.contains("otp-digit")&&e.key==="Backspace"&&!e.target.value){
        const inputs=Array.from(document.querySelectorAll(".otp-digit"));
        const idx=inputs.indexOf(e.target);
        if(idx>0)inputs[idx-1].focus();
    }
});
function aceptarPolitica(){
    // Guardar estado de reserva antes de aceptar
    if(servicioActual){
        sessionStorage.setItem("reserva_pendiente", JSON.stringify({
            servicioId: servicioActual.id,
            servicioNombre: servicioActual.nombre,
            servicioDuracion: servicioActual.duracion,
            empleadoId: servicioActual.empleadoId,
            fechaHora: document.getElementById("input-fecha-hora").value
        }));
    }
    fetch("/auth/politica/<?php echo e($negocio->slug); ?>", {
        method: "POST",
        headers: {"Content-Type":"application/json", "X-CSRF-TOKEN":"<?php echo e(csrf_token()); ?>"},
        body: JSON.stringify({})
    }).then(r=>r.json()).then(d=>{
        if(d.ok){
            // Ocultar modal de política
            document.getElementById("modal-politica") && (document.getElementById("modal-politica").style.display="none");
            // Recargar para actualizar sesión — el sessionStorage restaurará la reserva
            location.reload();
        }
    });
}
function guardarYGoogle(){
    if(servicioActual){
        sessionStorage.setItem("reserva_pendiente", JSON.stringify({
            servicioId: servicioActual.id,
            servicioNombre: servicioActual.nombre,
            servicioDuracion: servicioActual.duracion,
            empleadoId: servicioActual.empleadoId,
            fechaHora: document.getElementById("input-fecha-hora").value
        }));
    }
    window.location.href = "/auth/google/redirect/<?php echo e($negocio->slug); ?>";
}

function verificarOtpYContinuar(){
    const email=document.getElementById("otp-email").value;
    const codigo=Array.from(document.querySelectorAll(".otp-digit")).map(i=>i.value).join("");
    if(codigo.length<6){return;}
    const btn=document.getElementById("btn-otp-verificar");
    btn.disabled=true;btn.textContent="Verificando...";
    fetch("/auth/otp/verificar/<?php echo e($negocio->slug); ?>",{
        method:"POST",
        headers:{"Content-Type":"application/json","X-CSRF-TOKEN":"<?php echo e(csrf_token()); ?>"},
        body:JSON.stringify({email,codigo})
    }).then(r=>r.json()).then(d=>{
        if(d.ok){
            // No reload — actualizar UI directamente
            window.location.reload();
        } else {
            document.getElementById("otp-error2").textContent=d.error||"Codigo incorrecto";
            document.getElementById("otp-error2").style.display="block";
            btn.disabled=false;btn.textContent="Verificar codigo";
        }
    });
}

// Restaurar reserva pendiente al volver de login
window.addEventListener("load", function(){
    const pendiente = sessionStorage.getItem("reserva_pendiente");
    <?php if(session("social_cliente") && session("social_cliente.politica_aceptada")): ?>
    if(pendiente){
        try{
            const d = JSON.parse(pendiente);
            sessionStorage.removeItem("reserva_pendiente");
            servicioActual = {id:d.servicioId, nombre:d.servicioNombre, duracion:d.servicioDuracion, empleadoId:d.empleadoId};
            document.getElementById("input-servicio-id").value = d.servicioId;
            document.getElementById("input-empleado-id").value = d.empleadoId||"";
            document.getElementById("input-fecha-hora").value = d.fechaHora;
            document.getElementById("modal-titulo").textContent = d.servicioNombre;
            document.getElementById("modal-reserva").classList.add("open");
            document.body.style.overflow="hidden";
            const fh=d.fechaHora;
            const fecha=fh?fh.split(" ")[0]:"";
            const hoy=new Date();calYear=hoy.getFullYear();calMonth=hoy.getMonth();
            if(fecha){
                const parts=fecha.split("-");
                calYear=parseInt(parts[0]);calMonth=parseInt(parts[1])-1;
            }
            const mes=`${calYear}-${String(calMonth+1).padStart(2,"0")}`;
            cargarOcupacion(mes,()=>{
                renderCal();
                fechaSel=fecha;
                irPaso(2);
            });
        }catch(e){}
    }
    <?php endif; ?>
});

document.getElementById('modal-reserva').addEventListener('click',e=>{
    if(e.target===document.getElementById('modal-reserva'))cerrarModal();
});
</script>
</body>
</html>
<?php /**PATH /home/u759498454/domains/timync.com/public_html/app/resources/views/reserva/show.blade.php ENDPATH**/ ?>