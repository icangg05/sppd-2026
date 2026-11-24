<footer class="main-footer">
    <strong>
      <center>&copy; <a href="https://portal.kendarikota.go.id/" target="_blank" rel="noopener noreferrer">Diskominfo</a>
        <a href="http://kendarikota.go.id" target="_blank" rel="noopener noreferrer">Kota Kendari</a> <?php echo date('Y')?></center>
    </strong>
  </footer>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->
  <!-- jQuery 2.2.3 -->
  <!--script src="
<!--?php echo base_url();?>assets2/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
  <script src="
	<?php echo base_url();?>assets2/plugins/jQueryUI/jquery-ui.min.js">
  </script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.6 -->
  <script src="
	<?php echo base_url();?>assets2/bootstrap/js/bootstrap.min.js">
  </script>
  <!-- Select2 -->
  <script src="
	<?php echo base_url();?>assets2/plugins/select2/select2.full.min.js">
  </script>
  <!-- Morris.js charts -->
  <!--script src="
<!--?php echo base_url();?>assets2/plugins/morris/raphael.js"></script><script src="
<!--?php echo base_url();?>assets2/plugins/morris/morris.min.js"></script-->
  <!-- Sparkline -->
  <!--script src="
<!--?php echo base_url();?>assets2/plugins/sparkline/jquery.sparkline.min.js"></script-->
  <!-- jvectormap -->
  <!--script src="
<!--?php echo base_url();?>assets2/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<!--script src="
<!--?php echo base_url();?>assets2/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
  <!--script src="
<!--?php echo base_url();?>assets2/plugins/knob/jquery.knob.js"></script-->
  <!-- daterangepicker -->
  <script src="
	<?php echo base_url();?>assets2/plugins/daterangepicker/moment.min.js">
  </script>
  <script src="
	<?php echo base_url();?>assets2/plugins/daterangepicker/daterangepicker.js">
  </script>
  <!-- datepicker -->
  <script src="
	<?php echo base_url();?>assets2/plugins/datepicker/bootstrap-datepicker.js">
  </script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="
	<?php echo base_url();?>assets2/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js">
  </script>
  <!-- Slimscroll -->
  <script src="
	<?php echo base_url();?>assets2/plugins/slimScroll/jquery.slimscroll.min.js">
  </script>
  <!-- FastClick -->
  <script src="
	<?php echo base_url();?>assets2/plugins/fastclick/fastclick.js">
  </script>
  <!-- AdminLTE App -->
  <script src="
	<?php echo base_url();?>assets2/dist/js/app.min.js">
  </script>
  <script>
    // Ingat posisi scroll sidebar antar halaman (layout "fixed" memakai slimScroll yang dibuat ulang
    // AdminLTE di setiap halaman/resize, sehingga scroll selalu kembali ke atas).
    (function ($) {
      var KUNCI = 'sppd.sidebar.scroll';
      var $sb = $('.main-sidebar .sidebar');
      if (!$sb.length) return;

      function baca() {
        try { var v = sessionStorage.getItem(KUNCI); return v === null ? null : parseInt(v, 10); } catch (e) { return null; }
      }
      function simpan() {
        try { sessionStorage.setItem(KUNCI, String(Math.round($sb.scrollTop()))); } catch (e) {}
      }
      function gulirKe(y) {
        y = Math.max(0, y || 0);
        if ($.fn.slimScroll && $sb.parent().hasClass('slimScrollDiv')) {
          $sb.slimScroll({ scrollTo: y + 'px' });
        } else {
          $sb.scrollTop(y);
        }
      }
      function pulihkan() {
        var y = baca();
        if (y !== null && !isNaN(y)) { gulirKe(y); return; }
        // belum ada posisi tersimpan: pastikan menu yang aktif terlihat
        var $aktif = $sb.find('li.active > a').last();
        if (!$aktif.length) return;
        var atas = $aktif.offset().top - $sb.offset().top + $sb.scrollTop();
        if (atas > $sb.scrollTop() + $sb.innerHeight() - 50) gulirKe(atas - $sb.innerHeight() / 3);
      }

      $(function () {
        pulihkan();                                   // setelah AdminLTE membuat slimScroll
        $sb.on('scroll', simpan);
        $(document).on('click', '.main-sidebar a', simpan);
        $(window).on('pagehide beforeunload', simpan);
        $(window).on('resize', function () {          // AdminLTE membuat ulang slimScroll saat resize
          var y = baca();
          setTimeout(function () { if (y !== null) gulirKe(y); }, 0);
        });
      });
    })(jQuery);
  </script>
  <style>
    /* Tombol lihat/sembunyikan password & passphrase */
    .lihat-sandi { position: relative; display: block; }
    .lihat-sandi > input { padding-right: 40px; }
    .lihat-sandi-tombol {
      position: absolute; top: 0; right: 0; width: 38px; height: 100%;
      padding: 0; border: 0; background: transparent; color: #999; cursor: pointer;
    }
    .lihat-sandi-tombol:hover, .lihat-sandi-tombol:focus { color: #3c8dbc; outline: none; }
    .keterangan-ukuran { display: block; margin: 4px 0 0; color: #888; font-size: 12px; }

    /* Paginasi ringkas:  [<]  [3]  [>]   Halaman [3] dari 120   (markup dari libraries/MY_Pagination.php)
       Warna = biru tema skin-blue / logo SPPD (#367fa9, hover #3c8dbc) */
    .pg-ringkas {
      display: flex; flex-wrap: wrap; align-items: center; justify-content: flex-end;
      gap: 8px 12px; margin: 4px 0; font-size: 16px; line-height: 1; color: #333;
    }
    .pg-ringkas .pg-panah {
      display: inline-flex; align-items: center; justify-content: center;
      width: 38px; height: 38px; border: 1px solid #367fa9; border-radius: 0;
      background: #fff; color: #367fa9; font-size: 14px; text-decoration: none;
      transition: background-color .15s, color .15s;
    }
    .pg-ringkas a.pg-panah:hover, .pg-ringkas a.pg-panah:focus {
      background: #3c8dbc; border-color: #3c8dbc; color: #fff; outline: none;
    }
    .pg-ringkas a.pg-panah:focus-visible { box-shadow: 0 0 0 3px rgba(60, 141, 188, .3); }
    .pg-ringkas .pg-mati { border-color: #ddd; background: #f4f4f4; color: #bbb; cursor: not-allowed; }
    .pg-ringkas .pg-aktif {
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 38px; height: 38px; padding: 0 10px; border-radius: 0;
      background: #367fa9; color: #fff; font-weight: 600;
    }
    .pg-ringkas .pg-loncat {
      display: inline-flex; align-items: center; gap: 10px; margin: 0 0 0 6px; font-weight: normal;
    }
    .pg-ringkas .pg-input {
      width: 72px; height: 38px; padding: 4px 10px; border: 1px solid #c8c8c8; border-radius: 0;
      background: #fff; color: #333; font-size: 15px; -moz-appearance: textfield; appearance: textfield;
    }
    .pg-ringkas .pg-input::-webkit-outer-spin-button,
    .pg-ringkas .pg-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .pg-ringkas .pg-input:focus { border-color: #3c8dbc; box-shadow: 0 0 0 2px rgba(60, 141, 188, .2); outline: none; }
    @media (max-width: 480px) {
      .pg-ringkas { justify-content: center; gap: 8px 10px; }
      .pg-ringkas .pg-loncat { margin-left: 0; }
    }
  </style>
  <script>
    // Paginasi ringkas: ketik nomor halaman di kotak "Halaman" lalu Enter untuk pindah halaman
    (function ($) {
      function pindah(input) {
        var maks = parseInt(input.getAttribute('max'), 10) || 1;
        var sekarang = parseInt(input.getAttribute('data-hal'), 10) || 1;
        var n = parseInt(input.value, 10);
        if (isNaN(n)) { input.value = sekarang; return; }
        n = Math.min(Math.max(n, 1), maks);
        input.value = n;
        if (n === sekarang) return;
        var pakaiNomor = input.getAttribute('data-nomor') === '1';
        var nilai = pakaiNomor ? n : (n - 1) * (parseInt(input.getAttribute('data-per-hal'), 10) || 1);
        var awal = pakaiNomor ? 1 : 0;
        window.location.href = nilai === awal
          ? input.getAttribute('data-url-awal')
          : input.getAttribute('data-url').replace('__HAL__', nilai);
      }
      // keydown + preventDefault: paginasi bisa berada di dalam <form> lain, jangan sampai Enter mengirim form itu
      $(document).on('keydown', '.pg-ringkas .pg-input', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) { e.preventDefault(); pindah(this); }
      });
      $(document).on('blur', '.pg-ringkas .pg-input', function () {
        if (this.value === '') this.value = this.getAttribute('data-hal');
      });
    })(jQuery);
  </script>
  <script>
    // Tombol lihat/sembunyikan di semua input password & passphrase (kecuali yang diberi data-tanpa-lihat)
    (function ($) {
      function pasang(input) {
        var $in = $(input);
        if ($in.data('lihat-sandi') || $in.is('[data-tanpa-lihat]')) return;
        $in.data('lihat-sandi', true).wrap('<span class="lihat-sandi"></span>');
        $('<button type="button" class="lihat-sandi-tombol" aria-pressed="false"></button>')
          .attr({ 'aria-label': 'Tampilkan', title: 'Tampilkan' })
          .html('<i class="fa fa-eye"></i>')
          .insertAfter($in);
      }
      $(document).on('click', '.lihat-sandi-tombol', function () {
        var input = $(this).siblings('input')[0];
        if (!input) return;
        var tampil = input.type === 'password';
        input.type = tampil ? 'text' : 'password';
        var label = tampil ? 'Sembunyikan' : 'Tampilkan';
        $(this).attr({ 'aria-label': label, title: label, 'aria-pressed': tampil ? 'true' : 'false' })
          .html(tampil ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>');
        input.focus();
      });
      // sebelum dikirim, kembalikan ke password agar isian tidak tersimpan sebagai teks biasa oleh browser
      $(document).on('submit', 'form', function () {
        $(this).find('.lihat-sandi > input[type="text"]').each(function () { this.type = 'password'; });
      });
      $(function () { $('input[type="password"]').each(function () { pasang(this); }); });
      $(document).on('shown.bs.modal', function (e) {
        $(e.target).find('input[type="password"]').each(function () { pasang(this); });
      });
    })(jQuery);

    // Batas ukuran file (data-maks-mb): tolak di browser sebelum diunggah. Server tetap memeriksa lagi.
    (function ($) {
      $(function () {
        $('input[type="file"][data-maks-mb]').not('[data-tanpa-keterangan]').each(function () {
          if (!$(this).next('.keterangan-ukuran').length) {
            $('<small class="keterangan-ukuran"></small>').text('Ukuran maks. ' + $(this).attr('data-maks-mb') + ' MB').insertAfter(this);
          }
        });
      });
      $(document).on('change', 'input[type="file"][data-maks-mb]', function () {
        var mb = parseFloat(this.getAttribute('data-maks-mb'));
        var files = this.files || [];
        for (var i = 0; i < files.length; i++) {
          if (files[i].size > mb * 1024 * 1024) {
            alert('Ukuran file "' + files[i].name + '" (' + (files[i].size / 1048576).toFixed(1) + ' MB) melebihi batas ' + mb + ' MB.\nSilakan pilih file yang lebih kecil.');
            this.value = '';
            return;
          }
        }
      });
    })(jQuery);
  </script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <!--script src="
<!--?php echo base_url();?>assets2/dist/js/pages/dashboard.js"></script->
<!-- AdminLTE for demo purposes -->
  <!--script src="
<!--?php echo base_url();?>assets2/dist/js/demo.js"></script-->
</body>
</html>
