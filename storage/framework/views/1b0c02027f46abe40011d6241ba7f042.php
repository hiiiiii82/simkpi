<?php $__env->startSection('title','Laporan'); ?>
<?php $__env->startSection('page-title','Laporan & Unduh'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <form method="GET" class="d-flex gap-2">
    <select name="tahun" class="form-select form-select-sm" style="width:auto"><?php $__currentLoopData = range(2025,2027); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($t); ?>" <?php if($tahun==$t): echo 'selected'; endif; ?>><?php echo e($t); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
    <button class="btn btn-sm btn-outline-primary">Tampilkan</button>
  </form>
  <div class="d-flex gap-2">
    <a href="<?php echo e(route('laporan.pdf',['bulan'=>$bulan,'tahun'=>$tahun])); ?>" class="btn btn-sm btn-danger"><i class="bi bi-file-pdf me-1"></i>PDF Bulan Ini</a>
    <a href="<?php echo e(route('laporan.excel',['bulan'=>$bulan,'tahun'=>$tahun])); ?>" class="btn btn-sm btn-success"><i class="bi bi-file-spreadsheet me-1"></i>Excel</a>
  </div>
</div>
<div class="row g-3 mb-4">
  <div class="col-12 col-lg-8"><div class="card"><div class="card-header"><i class="bi bi-graph-up me-2" style="color:var(--pln)"></i>Skor Proporsional Bulanan <?php echo e($tahun); ?></div>
    <div class="card-body"><canvas id="lChart" height="220"></canvas></div></div></div>
  <div class="col-12 col-lg-4"><div class="card h-100"><div class="card-header">Per Kategori</div>
    <div class="card-body"><?php $__currentLoopData = $perKategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="mb-3"><div class="d-flex justify-content-between mb-1"><span style="font-size:12px;font-weight:600"><?php echo e($pk['nama']); ?></span><span style="font-size:12px;font-weight:700;color:<?php echo e($pk['warna']); ?>"><?php echo e($pk['capaian']); ?>%</span></div>
      <div class="progress"><div class="progress-bar" style="width:<?php echo e(min($pk['capaian'],100)); ?>%;background:<?php echo e($pk['warna']); ?>"></div></div></div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></div></div>
</div>
<div class="card"><div class="card-header"><i class="bi bi-table me-2" style="color:var(--pln)"></i>Rekap Bulanan <?php echo e($tahun); ?></div>
  <div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Bulan</th><th class="text-center">Jml KPI</th><th class="text-center">Total Skor</th><th class="text-center">Proporsional</th><th class="text-center">Predikat</th><th class="text-center">Unduh</th></tr></thead>
    <tbody><?php $__currentLoopData = $rekap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td style="font-weight:600"><?php echo e($r['nama']); ?></td>
        <td class="text-center"><?php echo e($r['jumlah']); ?></td>
        <td class="text-center"><?php if($r['skor']>0): ?><span style="font-weight:700;font-size:14px;color:var(--pln)"><?php echo e($r['skor']); ?></span><?php else: ?><span class="text-muted">—</span><?php endif; ?></td>
        <td class="text-center"><?php if($r['proporsional']>0): ?><div class="d-flex align-items-center justify-content-center gap-2"><div class="progress" style="width:50px"><div class="progress-bar <?php echo e($r['proporsional']>=90?'bg-success':($r['proporsional']>=80?'bg-primary':'bg-danger')); ?>" style="width:<?php echo e(min($r['proporsional'],100)); ?>%"></div></div><span style="font-size:12px;font-weight:600"><?php echo e($r['proporsional']); ?>%</span></div><?php else: ?><span class="text-muted">—</span><?php endif; ?></td>
        <td class="text-center"><?php if($r['predikat']!='-'): ?><?php $k=\App\Models\Evaluasi::kelasPredikat($r['predikat']); ?><span class="<?php echo e($k); ?>"><?php echo e($r['predikat']); ?></span><?php else: ?><span class="text-muted" style="font-size:12px">Belum ada data</span><?php endif; ?></td>
        <td class="text-center"><?php if($r['jumlah']>0): ?><div class="d-flex gap-1 justify-content-center">
          <a href="<?php echo e(route('laporan.pdf',['bulan'=>$r['bulan'],'tahun'=>$tahun])); ?>" class="btn btn-sm btn-outline-danger" style="font-size:11px;padding:2px 8px"><i class="bi bi-file-pdf"></i> PDF</a>
          <a href="<?php echo e(route('laporan.excel',['bulan'=>$r['bulan'],'tahun'=>$tahun])); ?>" class="btn btn-sm btn-outline-success" style="font-size:11px;padding:2px 8px"><i class="bi bi-file-spreadsheet"></i> XLS</a>
        </div><?php else: ?><span class="text-muted" style="font-size:11px">—</span><?php endif; ?></td>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody>
  </table></div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
const lb=<?php echo json_encode($rekap->pluck('nama'), 15, 512) ?>; const sk=<?php echo json_encode($rekap->pluck('skor'), 15, 512) ?>; const pr=<?php echo json_encode($rekap->pluck('proporsional'), 15, 512) ?>;
new Chart(document.getElementById('lChart'),{type:'line',data:{labels:lb,datasets:[{label:'Total Skor',data:sk,borderColor:'#003B93',backgroundColor:'#003B9315',borderWidth:2.5,pointRadius:4,tension:0.4,fill:true,yAxisID:'y'},{label:'Proporsional (%)',data:pr,borderColor:'#F5A623',backgroundColor:'transparent',borderWidth:2,pointRadius:4,borderDash:[6,3],tension:0.4,yAxisID:'y1'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{font:{size:11}}}},scales:{y:{position:'left',beginAtZero:true,max:110,grid:{color:'#F1F5F9'},ticks:{font:{size:11}}},y1:{position:'right',beginAtZero:true,max:120,grid:{display:false},ticks:{font:{size:11},callback:v=>v+'%'}},x:{grid:{display:false},ticks:{font:{size:11}}}}}});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Asus\Documents\simkpi\resources\views/laporan/index.blade.php ENDPATH**/ ?>