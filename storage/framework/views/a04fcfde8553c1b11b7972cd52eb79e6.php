
<?php $__env->startSection('title','Indikator KPI'); ?>
<?php $__env->startSection('page-title','Indikator KPI UP3 Surakarta'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <p class="text-muted mb-0" style="font-size:13px">Daftar indikator KPI sesuai Kinerja Januari 2026.</p>
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mdlTambah"><i class="bi bi-plus-lg me-1"></i>Tambah Indikator</button>
</div>
<?php $grouped = $indikators->groupBy(fn($i) => $i->kategori->nama); ?>
<?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namaKat => $inds): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $kat = $inds->first()->kategori; ?>
<div class="card mb-4">
  <div class="card-header" style="border-left:4px solid <?php echo e($kat->warna); ?>">
    <span style="font-weight:700;color:<?php echo e($kat->warna); ?>"><?php echo e($namaKat); ?></span>
    <span style="font-size:11px;color:#64748B;margin-left:8px">Total bobot: <strong><?php echo e($inds->sum('bobot')); ?>%</strong></span>
  </div>
  <div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Kode</th><th>Nama Indikator</th><th>Satuan</th><th class="text-end">Target Jan</th><th class="text-center">Bobot</th><th class="text-center">Arah</th><th class="text-center">Aksi</th></tr></thead>
    <tbody>
      <?php $__currentLoopData = $inds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ind): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td><code style="font-size:10px;background:#F1F5F9;padding:2px 6px;border-radius:4px"><?php echo e($ind->kode); ?></code></td>
        <td style="font-size:12.5px;font-weight:500;max-width:260px"><?php echo e($ind->nama); ?></td>
        <td><span style="background:#EFF6FF;color:#1E40AF;padding:1px 7px;border-radius:8px;font-size:10px"><?php echo e($ind->satuan); ?></span></td>
        <td class="text-end" style="font-weight:600;font-size:12.5px"><?php echo e(number_format($ind->target,4)); ?></td>
        <td class="text-center"><span class="badge bg-primary" style="font-size:11px"><?php echo e($ind->bobot); ?>%</span></td>
        <td class="text-center" style="font-size:12px;font-weight:600;color:<?php echo e($ind->arah=='naik'?'#059669':'#DC2626'); ?>"><?php echo e($ind->arah=='naik'?'⬆ Naik':'⬇ Turun'); ?></td>
        <td class="text-center">
          <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#mdlEdit"
            data-id="<?php echo e($ind->id); ?>" data-nama="<?php echo e($ind->nama); ?>" data-satuan="<?php echo e($ind->satuan); ?>" data-target="<?php echo e($ind->target); ?>" data-bobot="<?php echo e($ind->bobot); ?>" data-arah="<?php echo e($ind->arah); ?>" data-periode="<?php echo e($ind->periode); ?>">
            <i class="bi bi-pencil"></i>
          </button>
          <form action="<?php echo e(route('kpi.destroy',$ind->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus indikator ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
          </form>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table></div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<div class="modal fade" id="mdlTambah" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content" style="border-radius:13px;border:none">
  <div class="modal-header" style="background:linear-gradient(135deg,#003B93,#0052CC);color:#fff;border-radius:13px 13px 0 0"><h5 class="modal-title fw-bold">Tambah Indikator KPI</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
  <form action="<?php echo e(route('kpi.store')); ?>" method="POST"><?php echo csrf_field(); ?>
    <div class="modal-body p-4">
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">KATEGORI</label><select name="kategori_id" class="form-select" required><option value="">-- Pilih --</option><?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k->id); ?>"><?php echo e($k->nama); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">KODE</label><input type="text" name="kode" class="form-control" required maxlength="20" placeholder="KPI-18"></div>
        <div class="col-12"><label class="form-label fw-bold" style="font-size:11px">NAMA INDIKATOR</label><input type="text" name="nama" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label fw-bold" style="font-size:11px">SATUAN</label><input type="text" name="satuan" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label fw-bold" style="font-size:11px">TARGET</label><input type="number" name="target" class="form-control" step="0.0001" required></div>
        <div class="col-md-4"><label class="form-label fw-bold" style="font-size:11px">BOBOT (%)</label><input type="number" name="bobot" class="form-control" step="0.5" min="0" max="100" required></div>
        <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">ARAH TARGET</label><select name="arah" class="form-select"><option value="naik">⬆ Naik (semakin naik semakin baik)</option><option value="turun">⬇ Turun (semakin turun semakin baik)</option></select></div>
        <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">PERIODE</label><select name="periode" class="form-select"><option value="bulanan">Bulanan</option><option value="triwulan">Triwulan</option><option value="tahunan">Tahunan</option></select></div>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i>Simpan</button></div>
  </form>
</div></div></div>

<div class="modal fade" id="mdlEdit" tabindex="-1"><div class="modal-dialog"><div class="modal-content" style="border-radius:13px;border:none">
  <div class="modal-header" style="background:linear-gradient(135deg,#059669,#065F46);color:#fff;border-radius:13px 13px 0 0"><h5 class="modal-title fw-bold">Edit Indikator KPI</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
  <form id="fmEdit" method="POST"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="modal-body p-4">
      <div class="mb-3"><label class="form-label fw-bold" style="font-size:11px">NAMA INDIKATOR</label><input type="text" name="nama" id="en" class="form-control" required></div>
      <div class="row g-3">
        <div class="col-md-4"><label class="form-label fw-bold" style="font-size:11px">SATUAN</label><input type="text" name="satuan" id="es" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label fw-bold" style="font-size:11px">TARGET</label><input type="number" name="target" id="et" class="form-control" step="0.0001" required></div>
        <div class="col-md-4"><label class="form-label fw-bold" style="font-size:11px">BOBOT (%)</label><input type="number" name="bobot" id="eb" class="form-control" step="0.5" required></div>
        <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">ARAH</label><select name="arah" id="ea" class="form-select"><option value="naik">⬆ Naik</option><option value="turun">⬇ Turun</option></select></div>
        <div class="col-md-6"><label class="form-label fw-bold" style="font-size:11px">PERIODE</label><select name="periode" id="ep" class="form-select"><option value="bulanan">Bulanan</option><option value="triwulan">Triwulan</option><option value="tahunan">Tahunan</option></select></div>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-success btn-sm"><i class="bi bi-save me-1"></i>Simpan</button></div>
  </form>
</div></div></div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('mdlEdit').addEventListener('show.bs.modal',function(e){
  const b=e.relatedTarget;
  document.getElementById('fmEdit').action='/kpi/'+b.dataset.id;
  document.getElementById('en').value=b.dataset.nama;
  document.getElementById('es').value=b.dataset.satuan;
  document.getElementById('et').value=b.dataset.target;
  document.getElementById('eb').value=b.dataset.bobot;
  document.getElementById('ea').value=b.dataset.arah;
  document.getElementById('ep').value=b.dataset.periode;
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Asus\Documents\simkpi\resources\views/kpi/index.blade.php ENDPATH**/ ?>