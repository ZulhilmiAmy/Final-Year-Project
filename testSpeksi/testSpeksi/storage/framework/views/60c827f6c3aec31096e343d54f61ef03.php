<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Surat Temujanji</title>
  <style>
    body { font-family: Arial, sans-serif; font-size:14px; line-height:1.4; color:#000; }
    .header { text-align:left; margin-bottom:20px; }
    .address { margin-top:20px; margin-bottom:20px; }
    .content { margin-top:10px; }
    .signature { margin-top:50px; }
    .right { float:right; }
    .clear { clear:both; }
  </style>
</head>
<body>
  <div class="header">
    <strong>KEMENTERIAN KESIHATAN MALAYSIA</strong><br>
    HOSPITAL ENCHE’ BESAR HAJJAH KHALSOM<br>
    KM. 5, Jalan Kota Tinggi,<br>
    86000 Kluang<br>
    JOHOR DARUL TAKZIM
  </div>

  <div class="address">
    <div>Tarikh: <?php echo e($tarikhTemu ? $tarikhTemu->format('d-m-Y') : \Carbon\Carbon::now()->format('d-m-Y')); ?></div>

    <p>
      <?php echo e($patient->nama ?? '-'); ?><br>
      <?php echo $alamatFormatted; ?><br>
      <?php echo e($patient->poskod ?? ''); ?>, <?php echo e($patient->bandar ?? ''); ?><br>
      <?php echo e($patient->negeri ?? ''); ?>

    </p>
  </div>

  <div class="content">
    <p><strong>PER: TEMUJANJI PESAKIT</strong></p>

    <p>2. Sukacita dimaklumkan bahawa pihak kami memohon kerjasama pihak tuan/puan untuk hadir di Jabatan Kerja Sosial Perubatan, Hospital Enche’ Besar Hajjah Khalsom, Kluang bagi urusan bantuan terapi sokongan seperti berikut :</p>

    <p>
      <strong>Tarikh:</strong> <?php echo e($tarikhTemuFormatted ?? '-'); ?> (<?php echo e($hariTemu ?? '-'); ?>)<br>
      <strong>Masa:</strong> <?php echo e($patient->masa_temu ?? '-'); ?><br>
      <strong>Tempat:</strong> Jabatan Kerja Sosial Perubatan, Aras 3, Hospital Enche’ Besar Hajjah Khalsom, Kluang
    </p>

    <p>Sila bawa salinan kad pengenalan dan dokumen yang berkaitan. Sekiranya ada pertanyaan, sila hubungi <?php echo e($patient->pegawai_kes ?? 'Pegawai Kes'); ?> di talian 07-7787000 sambungan 2363.</p>

    <p>Kerjasama tuan/puan dalam perkara ini amat dihargai dan didahului dengan ucapan terima kasih.</p>

    <p class="signature">
      Saya yang menjalankan amanah,<br><br><br>
      (RAIMAH BINTI ISMAIL)<br>
      Ketua Jabatan<br>
      Jabatan Kerja Sosial Perubatan
    </p>
  </div>

  <div class="clear"></div>
</body>
</html>
<?php /**PATH C:\laragon\www\testSpeksi\resources\views/letters/appointment.blade.php ENDPATH**/ ?>