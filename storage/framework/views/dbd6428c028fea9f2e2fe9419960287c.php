<?php $__env->startSection('title', 'Validasi booking'); ?>
<?php $__env->startSection('content'); ?>
<section class="page-heading"><div><p class="eyebrow">ADMISSION CONTROL</p><h1>Validasi kode booking</h1><p class="muted">Periksa status pembayaran sebelum mengizinkan pemegang tiket masuk.</p></div></section>
<section class="panel validation-panel"><form method="POST" action="<?php echo e(route('validation.submit')); ?>" class="validation-form"><?php echo csrf_field(); ?><label for="booking_code">Kode booking</label><div class="input-action"><input id="booking_code" name="booking_code" value="<?php echo e(old('booking_code', $input ?? '')); ?>" placeholder="Contoh: EVP-DEMO2026" maxlength="32" required autocomplete="off"><button class="button primary" type="submit">Validasi tiket</button></div></form><p class="muted small">Pencarian memakai kecocokan kode yang persis dan parameter binding. Masukkan kode booking yang tercatat.</p></section>
<?php if(isset($result)): ?>
    <section class="panel result-panel">
        <?php ($resultMeta = ['valid' => ['Valid untuk masuk', 'success'], 'used' => ['Sudah digunakan', 'error'], 'unpaid' => ['Belum lunas', 'warning'], 'cancelled' => ['Dibatalkan', 'error'], 'not_found' => ['Kode tidak ditemukan', 'error']]); ?>
        <div class="result-heading"><div><p class="eyebrow">HASIL PEMERIKSAAN</p><h2><?php echo e($resultMeta[$result][0]); ?></h2></div><span class="status status-<?php echo e($resultMeta[$result][1]); ?>"><?php echo e(strtoupper($result)); ?></span></div>
        <?php if(!empty($suspiciousInput)): ?><div class="alert warning">Input memiliki pola yang menyerupai manipulasi sintaks SQL. Input tetap diperlakukan sebagai nilai biasa; tidak ada bypass yang dijalankan.</div><?php endif; ?>
        <?php if($ticket): ?><div class="detail-list compact"><div><span>Kode booking</span><strong><?php echo e($ticket->booking_code); ?></strong></div><div><span>Event</span><strong><?php echo e($ticket->event->name); ?></strong></div><div><span>Pembeli</span><strong><?php echo e($ticket->buyer_name); ?></strong></div><div><span>Status tiket</span><strong><?php echo e($ticket->status_label); ?></strong></div></div><?php endif; ?>
        <?php if($result === 'valid' && $ticket): ?><form method="POST" action="<?php echo e(route('tickets.check-in', $ticket)); ?>" class="checkin-form"><?php echo csrf_field(); ?><button class="button primary" type="submit">Konfirmasi check-in</button><span class="muted small">Aksi ini mengubah status menjadi sudah digunakan dan mencegah check-in ulang.</span></form><?php endif; ?>
    </section>
<?php endif; ?>
<section class="info-note wide-note"><strong>Aturan validasi:</strong> hanya tiket berstatus lunas yang belum digunakan dapat check-in. Tiket menunggu pembayaran, dibatalkan, atau sudah digunakan akan ditolak.</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\chris\Downloads\EventPass-Laravel-PostgreSQL-Starter\eventpass-starter\EventPass\resources\views/validation/index.blade.php ENDPATH**/ ?>