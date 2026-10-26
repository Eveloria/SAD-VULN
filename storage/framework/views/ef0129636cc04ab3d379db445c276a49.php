<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> · EventPass</title>
    <link rel="stylesheet" href="<?php echo e(asset('eventpass.css')); ?>">
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <a class="brand" href="<?php echo e(route('dashboard')); ?>"><span class="brand-mark">E</span><span>EventPass<small>Ticket & Exhibition Desk</small></span></a>
        <nav class="nav-links" aria-label="Navigasi utama">
            <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">Dashboard</a>
            <a href="<?php echo e(route('tickets.index')); ?>" class="<?php echo e(request()->routeIs('tickets.*') ? 'active' : ''); ?>">Tiket</a>
            <a href="<?php echo e(route('validation.form')); ?>" class="<?php echo e(request()->routeIs('validation.*') ? 'active' : ''); ?>">Validasi</a>
            <a href="<?php echo e(route('gateway.index')); ?>" class="<?php echo e(request()->routeIs('gateway.*') ? 'active' : ''); ?>">Gateway</a>
            <?php if(app()->environment('local')): ?><a href="<?php echo e(route('lab.index')); ?>" class="<?php echo e(request()->routeIs('lab.*') ? 'active' : ''); ?>">Security Lab</a><?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
    <?php if(session('success')): ?> <div class="alert success" role="status"><?php echo e(session('success')); ?></div> <?php endif; ?>
    <?php if(session('error')): ?> <div class="alert error" role="alert"><?php echo e(session('error')); ?></div> <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert error"><strong>Periksa kembali input:</strong><ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
    <?php endif; ?>
    <?php echo $__env->yieldContent('content'); ?>
</main>
<footer class="footer"><div class="container footer-inner"><span>EventPass · Local learning project</span><span>Payment provider and security lab are simulated</span></div></footer>
</body>
</html>
<?php /**PATH C:\Users\chris\Downloads\EventPass-Laravel-PostgreSQL-Starter\eventpass-starter\EventPass\resources\views/layouts/app.blade.php ENDPATH**/ ?>