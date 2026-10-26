<?php $__env->startSection('title', 'Daftar tiket'); ?>
<?php $__env->startSection('content'); ?>
<section class="page-heading"><div><p class="eyebrow">TICKETING</p><h1>Daftar tiket</h1><p class="muted">Kelola pembelian dan status setiap kode booking.</p></div><a class="button primary" href="<?php echo e(route('tickets.create')); ?>">＋ Pembelian baru</a></section>
<section class="panel">
    <form class="search-form" method="GET" action="<?php echo e(route('tickets.index')); ?>"><input type="search" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari kode booking, nama, atau e-mail"><button class="button secondary" type="submit">Cari</button><?php if(request('q')): ?><a class="text-link" href="<?php echo e(route('tickets.index')); ?>">Reset</a><?php endif; ?></form>
    <div class="table-wrap"><table><thead><tr><th>Kode booking</th><th>Nama / e-mail</th><th>Event</th><th>Nominal</th><th>Status</th><th>Dibuat</th><th></th></tr></thead><tbody>
    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr><td><code><?php echo e($ticket->booking_code); ?></code></td><td><?php echo e($ticket->buyer_name); ?><small><?php echo e($ticket->buyer_email); ?></small></td><td><?php echo e($ticket->event->name); ?></td><td>Rp <?php echo e(number_format((float)$ticket->amount, 0, ',', '.')); ?></td><td><span class="status status-<?php echo e($ticket->status); ?>"><?php echo e($ticket->status_label); ?></span></td><td><?php echo e($ticket->created_at->format('d M Y H:i')); ?></td><td><a class="text-link" href="<?php echo e(route('tickets.show', $ticket)); ?>">Detail →</a></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <tr><td colspan="7" class="empty-state">Tidak ada tiket yang cocok.</td></tr> <?php endif; ?>
    </tbody></table></div>
    <div class="pagination"><?php echo e($tickets->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\chris\Downloads\EventPass-Laravel-PostgreSQL-Starter\eventpass-starter\EventPass\resources\views/tickets/index.blade.php ENDPATH**/ ?>