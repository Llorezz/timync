<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> Clientes <?php $__env->endSlot(); ?>

    <!-- Buscador -->
    <div class="card" style="padding:20px; margin-bottom:20px;">
        <form method="GET" action="<?php echo e(route('clientes.index')); ?>" style="display:flex; gap:12px; align-items:end;">
            <div style="flex:1;">
                <label>Buscar cliente</label>
<input type="text" name="buscar" id="buscar" value="<?php echo e(request('buscar')); ?>" placeholder="Nombre, email o teléfono..." autocomplete="off">
            </div>
            <button type="submit" class="btn-primary" style="padding:8px 16px;">Buscar</button>
            <?php if(request('buscar')): ?>
                <a href="<?php echo e(route('clientes.index')); ?>" style="padding:8px 16px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#64748b; font-weight:500;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <div>
                <h2 style="font-size:16px; font-weight:600; color:#0f172a;">Lista de clientes</h2>
                <p style="color:#64748b; font-size:13px; margin-top:2px;"><?php echo e($clientes->total()); ?> clientes encontrados</p>
            </div>
            <a href="<?php echo e(route('clientes.create')); ?>" class="btn-primary">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Nuevo cliente
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="alert-success" style="margin-bottom:20px;"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <?php if($clientes->count() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Citas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:500; color:#0f172a;"><?php echo e($cliente->nombre); ?></td>
                    <td style="color:#64748b;"><?php echo e($cliente->email ?? '—'); ?></td>
                    <td style="color:#64748b;"><?php echo e($cliente->telefono ?? '—'); ?></td>
                    <td>
                        <span class="badge" style="background:#e0f2fe; color:#0369a1;">
                            <?php echo e($cliente->citas->count()); ?> citas
                        </span>
                    </td>
                    <td style="display:flex; gap:16px; align-items:center;">
                        <a href="<?php echo e(route('clientes.show', $cliente)); ?>" style="color:#10b981; font-size:14px; font-weight:500;">Ver</a>
                        <a href="<?php echo e(route('clientes.edit', $cliente)); ?>" class="btn-edit">Editar</a>
                        <form action="<?php echo e(route('clientes.destroy', $cliente)); ?>" method="POST">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button onclick="return confirm('¿Eliminar este cliente?')" class="btn-danger" style="background:none; border:none; cursor:pointer;">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div style="margin-top:20px;"><?php echo e($clientes->links()); ?></div>
        <?php else: ?>
        <div style="text-align:center; padding:40px; color:#94a3b8;">
            <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block; opacity:0.4;"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            <?php if(request('buscar')): ?>
                No se encontraron clientes con "<?php echo e(request('buscar')); ?>". <a href="<?php echo e(route('clientes.index')); ?>" style="color:var(--primary); font-weight:500;">Ver todos</a>
            <?php else: ?>
                No hay clientes. <a href="<?php echo e(route('clientes.create')); ?>" style="color:var(--primary); font-weight:500;">Añade el primero</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
<script>
    let timer = null;

    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('buscar');
        input.focus();
        input.setSelectionRange(input.value.length, input.value.length);

        input.addEventListener('input', function() {
            clearTimeout(timer);
            const valor = this.value;
            timer = setTimeout(() => {
                fetch('<?php echo e(route("clientes.index")); ?>?buscar=' + encodeURIComponent(valor), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const tbodyNuevo = doc.querySelector('table tbody');
                    const tbodyActual = document.querySelector('table tbody');
                    if (tbodyNuevo && tbodyActual) {
                        tbodyActual.innerHTML = tbodyNuevo.innerHTML;
                    }
                    const contador = doc.querySelector('.card:last-child p');
                    if (contador) document.querySelector('.card:last-child p').innerHTML = contador.innerHTML;
                });
            }, 500);
        });
    });
</script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/u759498454/domains/timync.com/public_html/app/resources/views/clientes/index.blade.php ENDPATH**/ ?>