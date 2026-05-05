<?php $__env->startSection('title','Monitoring Real-time'); ?>
<?php $__env->startSection('page-title','Monitoring Kinerja Real-time'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
  <div class="d-flex align-items-center gap-2">
    <span class="live"></span>
    <span style="font-size:12px;color:#64748B">Auto-refresh 60 detik | Terakhir: <span id="lastUpdate" style="font-weight:600;color:#1E293B">—</span></span>
  </div>
  <div class="d-flex gap-2 flex-wrap align-items-center">
    
    <select class="form-select form-select-sm" id="selUlp" style="width:auto">
      <option value="">— UP3 (Keseluruhan) —</option>
      <?php $__currentLoopData = $ulps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e($u->id); ?>"><?php echo e($u->nama); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <select class="form-select form-select-sm" id="selBulan" style="width:auto">
      <?php $__currentLoopData = range(1,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e($b); ?>" <?php if($bulan==$b): echo 'selected'; endif; ?>><?php echo e(['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$b]); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <select class="form-select form-select-sm" id="selTahun" style="width:auto">
      <?php $__currentLoopData = range(2025,2027); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($t); ?>" <?php if($tahun==$t): echo 'selected'; endif; ?>><?php echo e($t); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button class="btn btn-sm btn-outline-primary" onclick="muat()"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
  </div>
</div>

<div id="ulpBadge" class="mb-3" style="display:none">
  <span style="background:#DBEAFE;color:#1E40AF;padding:5px 14px;border-radius:20px;font-size:13px;font-weight:600">
    📍 Menampilkan data: <span id="ulpNama">—</span>
  </span>
</div>

<div id="container">
  <div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Memuat data...</p></div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let timer;
const ulpNames = { <?php $__currentLoopData = $ulps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>'<?php echo e($u->id); ?>':'<?php echo e($u->nama); ?>', <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> };

function muat() {
  const b = document.getElementById('selBulan').value;
  const t = document.getElementById('selTahun').value;
  const u = document.getElementById('selUlp').value;

  // ULP badge
  const badge = document.getElementById('ulpBadge');
  if (u) { badge.style.display='block'; document.getElementById('ulpNama').textContent = ulpNames[u] || ''; }
  else   { badge.style.display='none'; }

  const url = `<?php echo e(route('monitoring.data')); ?>?bulan=${b}&tahun=${t}${u?'&ulp_id='+u:''}`;
  fetch(url).then(r=>r.json()).then(res=>{
    document.getElementById('lastUpdate').textContent = res.timestamp;
    render(res.data, !!u);
  }).catch(()=>{
    document.getElementById('container').innerHTML='<div class="alert alert-danger">Gagal memuat data. Pastikan server berjalan.</div>';
  });
}

function render(data, isUlp) {
  let html = '';
  data.forEach(kat => {
    const col = kat.warna;
    html += `<div class="card mb-3">
      <div class="card-header d-flex align-items-center justify-content-between" style="border-left:4px solid ${col}">
        <span style="font-weight:700;color:${col};font-size:13.5px">${kat.nama}</span>
        <div class="d-flex align-items-center gap-2">
          ${!isUlp ? `<span style="font-size:11px;color:#64748B">Skor: <strong>${kat.total_skor}</strong></span>` : ''}
          <span style="background:${col}20;color:${col};padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700">${kat.avg_capaian}% rata-rata</span>
        </div>
      </div>
      <div class="table-responsive"><table class="table mb-0">
        <thead><tr><th>Kode</th><th>Indikator KPI</th><th class="text-end">Target</th><th class="text-end">Realisasi</th><th style="width:200px">Capaian</th>
          ${!isUlp ? '<th class="text-center">Bobot</th><th class="text-end">Skor</th>' : ''}
          <th class="text-center">Status</th></tr></thead>
        <tbody>`;

    kat.indikators.forEach(ind => {
      const c   = ind.capaian;
      const cc  = c===null?'#94A3B8':c>=100?'#059669':c>=80?'#D97706':'#DC2626';
      const pct = c!==null?Math.min(c,100):0;
      const statusMap = {'approved':['Disetujui','#DCFCE7','#166534'],'submitted':['Menunggu','#FEF3C7','#92400E'],'rejected':['Ditolak','#FEE2E2','#991B1B'],'belum':['Belum Input','#F1F5F9','#64748B'],'ada':['Ada Data','#DCFCE7','#166534']};
      const [slbl,sbg,stxt] = statusMap[ind.status]||statusMap['belum'];

      html += `<tr>
        <td><code style="font-size:10px;background:#F1F5F9;padding:2px 5px;border-radius:4px">${ind.kode}</code></td>
        <td style="font-size:12.5px;font-weight:500;max-width:240px">${ind.nama}</td>
        <td class="text-end" style="font-size:12px;color:#64748B">${fmt(ind.target)} <small>${ind.satuan}</small></td>
        <td class="text-end" style="font-weight:600">${ind.nilai!==null?fmt(ind.nilai)+' <small style="color:#94A3B8">'+ind.satuan+'</small>':'<span style="color:#94A3B8">—</span>'}</td>
        <td><div class="d-flex align-items-center gap-2">
          <div class="progress flex-grow-1"><div class="progress-bar" style="width:${pct}%;background:${cc}"></div></div>
          <span style="font-size:11.5px;font-weight:700;color:${cc};min-width:44px;text-align:right">${c!==null?c.toFixed(1)+'%':'—'}</span>
        </div></td>
        ${!isUlp ? `<td class="text-center" style="font-size:11px;color:#64748B">${ind.bobot}%</td><td class="text-end" style="font-weight:700">${ind.skor!==null?ind.skor.toFixed(2):'—'}</td>` : ''}
        <td class="text-center"><span style="background:${sbg};color:${stxt};padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700">${slbl}</span></td>
      </tr>`;
    });

    html += `</tbody></table></div></div>`;
  });

  document.getElementById('container').innerHTML = html || '<div class="alert alert-info">Belum ada data untuk periode ini.</div>';
}

function fmt(n) {
  if (n===null||n===undefined) return '—';
  const f = parseFloat(n);
  return Math.abs(f) < 0.01 ? f.toFixed(6) : f.toLocaleString('id-ID',{maximumFractionDigits:4});
}

muat();
timer = setInterval(muat, 60000);
document.getElementById('selBulan').addEventListener('change', muat);
document.getElementById('selTahun').addEventListener('change', muat);
document.getElementById('selUlp').addEventListener('change', muat);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Asus\Documents\simkpi\resources\views/monitoring/index.blade.php ENDPATH**/ ?>