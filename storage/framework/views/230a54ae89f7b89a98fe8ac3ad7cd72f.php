<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<section class="page-heading">
    <div><p class="eyebrow">OVERVIEW</p><h1>Event operations</h1><p class="muted">Pantau penjualan, validasi tiket, dan kondisi layanan dari satu tempat.</p></div>
    <a class="button primary" href="<?php echo e(route('tickets.create')); ?>">＋ Catat pembelian</a>
</section>
<section class="stat-grid">
    <article class="stat-card"><span class="stat-label">Event aktif</span><strong><?php echo e(number_format($eventCount)); ?></strong><span class="stat-note">Event yang tersedia</span></article>
    <article class="stat-card"><span class="stat-label">Total tiket</span><strong><?php echo e(number_format($ticketCount)); ?></strong><span class="stat-note">Seluruh pencatatan pembelian</span></article>
    <article class="stat-card"><span class="stat-label">Tiket lunas</span><strong><?php echo e(number_format($paidCount)); ?></strong><span class="stat-note">Lunas dan sudah check-in</span></article>
    <article class="stat-card"><span class="stat-label">Check-in</span><strong><?php echo e(number_format($checkInCount)); ?></strong><span class="stat-note">Tiket yang sudah digunakan</span></article>
</section>
<section class="content-grid">
    <article class="panel wide-panel">
        <div class="panel-heading"><div><h2>Pembelian terbaru</h2><p class="muted">Aktivitas tiket terakhir yang tercatat.</p></div><a class="text-link" href="<?php echo e(route('tickets.index')); ?>">Semua tiket →</a></div>
        <div class="table-wrap"><table><thead><tr><th>Kode booking</th><th>Pembeli</th><th>Event</th><th>Status</th><th></th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $latestTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><code><?php echo e($ticket->booking_code); ?></code></td><td><?php echo e($ticket->buyer_name); ?><small><?php echo e($ticket->buyer_email); ?></small></td><td><?php echo e($ticket->event->name); ?></td><td><span class="status status-<?php echo e($ticket->status); ?>"><?php echo e($ticket->status_label); ?></span></td><td><a class="text-link" href="<?php echo e(route('tickets.show', $ticket)); ?>">Detail</a></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <tr><td colspan="5" class="empty-state">Belum ada pembelian. Tambahkan tiket pertama untuk memulai.</td></tr> <?php endif; ?>
        </tbody></table></div>
    </article>
    <aside class="panel side-panel">
        <div class="panel-heading"><div><h2>Ringkasan pembayaran</h2><p class="muted">Data tiket berstatus lunas atau sudah check-in.</p></div></div>
        <p class="revenue">Rp <?php echo e(number_format($revenue, 0, ',', '.')); ?></p><span class="muted">Total nilai tiket tercatat</span>
        <div class="quick-links"><a href="<?php echo e(route('validation.form')); ?>"><span class="quick-icon">✓</span><span><strong>Validasi booking</strong><small>Periksa tiket sebelum masuk</small></span><span>→</span></a><a href="<?php echo e(route('gateway.index')); ?>"><span class="quick-icon">↗</span><span><strong>Status gateway</strong><small>Lihat pemeriksaan terakhir</small></span><span>→</span></a></div>
        <div class="mini-status"><span class="dot <?php echo e($latestGatewayCheck?->provider_status === 'online' ? 'dot-green' : 'dot-amber'); ?>"></span><span><strong>Gateway demo:</strong> <?php echo e(strtoupper($latestGatewayCheck?->provider_status ?? config('eventpass.gateway_status', 'online'))); ?></span></div>
    </aside>
</section>
<section class="panel event-panel"><div class="panel-heading"><div><h2>Alur kerja</h2><p class="muted">Alur dasar untuk demonstrasi EventPass.</p></div></div><div class="workflow"><div><span>01</span><strong>Catat pembelian</strong><p>Buat booking dan simpan ke PostgreSQL.</p></div><div><span>02</span><strong>Tandai pembayaran</strong><p>Gunakan tombol pembayaran demo untuk simulasi.</p></div><div><span>03</span><strong>Kirim e-tiket</strong><p>Mailer log menyimpan isi e-mail ke log lokal.</p></div><div><span>04</span><strong>Check-in</strong><p>Validasi kode dan tandai tiket telah digunakan.</p></div></div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\chris\Downloads\EventPass-Laravel-PostgreSQL-Starter\eventpass-starter\EventPass\resources\views/dashboard/index.blade.php ENDPATH**/ ?>