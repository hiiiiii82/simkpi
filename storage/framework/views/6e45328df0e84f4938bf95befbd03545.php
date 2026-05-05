
<?php $__env->startSection('title','ULP '.$ulp->nama); ?>
<?php $__env->startSection('page-title','Kinerja ULP '.$ulp->nama); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex flex-wrap align-items-center gap-2 mb-4">
  <?php $__currentLoopData = $ulps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <a href="<?php echo e(route('ulp.show',$u->id)); ?>"
     class="btn btn-sm <?php echo e($u->id == $ulp->id ? 'btn-primary' : 'btn-outline-primary'); ?>"
     style="font-size:12px;font-weight:600">
    <?php echo e($u->nama); ?>

  </a>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <div class="ms-auto d-flex gap-2">
    <form method="GET" class="d-flex gap-2">
      <select name="bulan" class="form-select form-select-sm" style="width:auto">
        <?php $__currentLoopData = range(1,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($b); ?>" <?php if($bulan==$b): echo 'selected'; endif; ?>><?php echo e(['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$b]); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <select name="tahun" class="form-select form-select-sm" style="width:auto">
        <?php $__currentLoopData = range(2025,2027); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($t); ?>" <?php if($tahun==$t): echo 'selected'; endif; ?>><?php echo e($t); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <button class="btn btn-sm btn-outline-secondary">Tampilkan</button>
    </form>
  </div>
</div>


<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat s-blue" style="padding:14px">
      <div class="stat-val"><?php echo e($avgCapaian); ?>%</div>
      <div class="stat-lbl">Rata-rata Capaian</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat s-green" style="padding:14px">
      <div class="stat-val"><?php echo e($realisasis->count()); ?></div>
      <div class="stat-lbl">Indikator Terlaporkan</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat s-orange" style="padding:14px">
      <div class="stat-val"><?php echo e($realisasis->where('capaian','>=',100)->count()); ?></div>
      <div class="stat-lbl">KPI Tercapai (≥100%)</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat s-red" style="padding:14px">
      <div class="stat-val"><?php echo e($realisasis->where('capaian','<',80)->count()); ?></div>
      <div class="stat-lbl">KPI Kritis (&lt;80%)</div>
    </div>
  </div>
</div>


<?php $__currentLoopData = $perKategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namaKat => $reals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between"
       style="border-left:4px solid <?php echo e($reals->first()->indikator->kategori->warna); ?>">
    <span style="font-weight:700;color:<?php echo e($reals->first()->indikator->kategori->warna); ?>"><?php echo e($namaKat); ?></span>
    <span style="font-size:11px;color:#64748B"><?php echo e($reals->count()); ?> indikator &nbsp;|&nbsp; Avg: <strong><?php echo e(round($reals->avg('capaian'),1)); ?>%</strong></span>
  </div>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr><th>Kode</th><th>Indikator KPI</th><th>Satuan</th><th class="text-end">Target</th><th class="text-end">Realisasi</th><th style="width:180px">Capaian</th></tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $reals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $c = $r->capaian ?? 0; $cc = $c>=100?'#059669':($c>=80?'#D97706':'#DC2626'); ?>
        <tr>
          <td><code style="font-size:10px;background:#F1F5F9;padding:2px 6px;border-radius:4px"><?php echo e($r->indikator->kode); ?></code></td>
          <td style="font-size:13px;font-weight:500;max-width:250px"><?php echo e($r->indikator->nama); ?></td>
          <td><span style="background:#EFF6FF;color:#1E40AF;padding:2px 8px;border-radius:8px;font-size:10px"><?php echo e($r->indikator->satuan); ?></span></td>
          <td class="text-end" style="font-size:12px;color:#64748B"><?php echo e(number_format($r->target_snapshot,4)); ?></td>
          <td class="text-end" style="font-weight:600;font-size:13px"><?php echo e(number_format($r->nilai,4)); ?></td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="progress flex-grow-1"><div class="progress-bar" style="width:<?php echo e(min($c,100)); ?>%;background:<?php echo e($cc); ?>"></div></div>
              <span style="font-size:12px;font-weight:700;color:<?php echo e($cc); ?>;min-width:46px;text-align:right"><?php echo e(number_format($c,1)); ?>%</span>
            </div>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
  </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($realisasis->isEmpty()): ?>
<div class="card">
  <div class="card-body text-center py-5">
    <div style="font-size:36px">📊</div>
    <p class="text-muted mt-2 mb-0">Belum ada data realisasi ULP <?php echo e($ulp->nama); ?> untuk periode ini.</p>
  </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Asus\Documents\simkpi\resources\views/ulp/show.blade.php ENDPATH**/ ?>