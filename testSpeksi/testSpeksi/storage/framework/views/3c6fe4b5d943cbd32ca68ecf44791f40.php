<!DOCTYPE html>
<html lang="ms">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sejarah Pesakit</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      line-height: 1.6;
      background-color: #f5f5f5;
      counter-reset: section;
    }

    .header {
      background-color: #111;
      color: white;
      padding: 0;
      text-align: center;
      margin: -20px -20px 20px -20px;
      width: calc(100% + 40px);
    }

    .header img {
      width: 100%;
      max-height: 200px;
      object-fit: cover;
      display: block;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .compartment {
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 20px;
      background-color: #f9f9f9;
      margin-bottom: 20px;
    }

    .form-row,
    .date-row {
      display: flex;
      flex-wrap: wrap;
      margin: 0 -10px 10px -10px;
      align-items: center;
    }

    .form-group,
    .date-group {
      display: flex;
      align-items: center;
      padding: 0 10px;
      flex: 1 0 calc(50% - 20px);
      box-sizing: border-box;
      margin-bottom: 6px;
    }

    label {
      width: 85px;
      margin-right: 2px;
      font-weight: bold;
      color: #333;
    }

    h1 {
      color: #333;
      border-bottom: 2px solid #2196F3;
      padding-bottom: 10px;
      margin-top: 0;
    }

    .form-title {
      font-size: 18px;
      font-weight: bold;
      color: #2196F3;
      margin-bottom: 15px;
      padding-bottom: 5px;
      border-bottom: 1px solid #eee;
      counter-increment: section;
    }

    .form-title::before {
      content: counter(section) ". ";
    }

    .label-value {
      flex: 1;
      padding: 2px 0;
      border-bottom: 1px dashed #ccc;
    }

    .action-buttons {
      display: flex;
      gap: 8px;
      margin-left: auto;
    }

    .action-button {
      color: white;
      border: none;
      border-radius: 4px;
      padding: 6px 10px;
      font-size: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
    }

    .action-button.view {
      background-color: #4CAF50;
    }

    .action-button.view:hover {
      background-color: #45a049;
    }

    .action-button.edit {
      background-color: #2196F3;
    }

    .action-button.edit:hover {
      background-color: #1976D2;
    }

    .action-button.print {
      background-color: #FF9800;
    }

    .action-button.print:hover {
      background-color: #F57C00;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #f8f9fa;
      padding: 8px 20px;
      border-bottom: 2px solid #204d84;
      font-size: 14px;
    }

    .breadcrumbs a {
      color: #204d84;
      text-decoration: none;
      font-weight: 500;
    }

    .breadcrumbs a:hover {
      text-decoration: underline;
    }

    .breadcrumbs .separator {
      color: #204d84;
      /* sama warna dengan link */
      margin: 0 4px;
    }

    .breadcrumbs span {
      color: #555;
    }
  </style>
</head>

<body>

  <div class="header">
    <img src="https://raw.githubusercontent.com/ZulhilmiAmy/Final-Year-Project/main/Image%20banner/Banner-SPeKSi.png"
      alt="Banner">

    <div class="top-bar">
      <div class="breadcrumbs">
        <a href="<?php echo e(route('login.custom')); ?>" onclick="return confirmLogin(event)">Log Masuk</a>
        <span class="separator">&gt;</span>
        <a href="<?php echo e(route('home')); ?>">Halaman Utama</a>
        <span class="separator">&gt;</span>
        <a>Sejarah Pesakit</a>
      </div>
    </div>

  </div>

  </div>


  <div class="container">
    <h1>Sejarah Pesakit</h1>

    <?php if($patients->isEmpty()): ?>
      <p>Tiada rekod ditemui untuk IC: <strong><?php echo e($no_kp); ?></strong></p>
    <?php else: ?>
      <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="compartment">
          <div class="form-title">Maklumat Asas</div>
          <div class="form-row">
            <div class="form-group">
              <label><b>Nama:</b></label>
              <div class="label-value"><?php echo e($p->nama); ?></div>
            </div>
            <div class="form-group">
              <label><b>No. KP:</b></label>
              <div class="label-value"><?php echo e($p->no_kp); ?></div>
            </div>
          </div>
          <div class="date-row">
            <div class="date-group">
              <label><b>Tarikh:</b></label>
              <div class="label-value">
                <?php echo e($p->tarikh_rujukan ? \Carbon\Carbon::parse($p->tarikh_rujukan)->format('d-m-Y') : '-'); ?>

              </div>
            </div>
            <div class="date-group">
              <label><b>No. Kes:</b></label>
              <div class="label-value"><?php echo e($p->no_fail ?? '-'); ?></div>
            </div>
            <div class="action-buttons">
              
              <a href="<?php echo e(route('patients.show', $p->id)); ?>" class="action-button view">
                <i class="fas fa-eye"></i>
              </a>
              
              <a href="<?php echo e(route('patients.edit', $p->id)); ?>" class="action-button edit">
                <i class="fas fa-edit"></i>
              </a>

              
              <a href="<?php echo e(route('patients.letter', $p->id)); ?>" target="_blank" class="action-button print">
                <i class="fas fa-print"></i>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    <a href="<?php echo e(route('home')); ?>" onclick="return confirmHome(event)">← Kembali ke Halaman Utama</a>
  </div>

</body>

<script>
  function confirmHome(e) {
    e.preventDefault(); // stop dulu redirect
    if (confirm("Anda pasti mahu kembali ke Halaman Utama? Maklumat yang belum disimpan mungkin akan hilang.")) {
      window.location.href = e.target.href; // teruskan kalau user tekan OK
    }
    return false; // cegah default behaviour
  }
  function confirmLogin(e) {
    e.preventDefault();
    if (confirm("Anda pasti mahu pergi ke halaman Log Masuk?")) {
      window.location.href = e.target.href;
    }
    return false;
  }
</script>

</html><?php /**PATH C:\laragon\www\testSpeksi\resources\views/patients/history.blade.php ENDPATH**/ ?>