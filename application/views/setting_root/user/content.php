  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <!-- Main content -->

    <section class="content">

      <!-- Small boxes (Stat box) -->

      <div class="row">

        <!-- ./col -->

        <div class="col-lg-12 col-xs-12">

          <div class="box box-success">

            <div class="box-header with-border">

              <h3 class="box-title">USER</h3>

            </div>

            <div class="box-header with-border">

             <?php
             $grup_dipilih = isset($grup_dipilih) ? (int) $grup_dipilih : 0;
             // pencarian username tetap membawa filter role yang sedang dipakai
             echo form_open("setting_root/user/search" . ($grup_dipilih ? '?grup=' . $grup_dipilih : ''));
             ?>

             <div class="<?php echo !empty($daftar_grup) ? 'col-md-4' : 'col-md-9'; ?>">

              <a href="<?php echo base_url();?>setting_root/user/create_view" class="btn btn-success btn-flat">Tambah Data</a>

              <a href="<?php echo base_url();?>setting_root/user" class="btn btn-warning btn-flat">Refresh</a>

            </div>

            <?php if (!empty($daftar_grup)) { ?>
            <div class="col-md-5">
              <!-- filter role (super admin): jumlah akun per role, kuota untuk role tingkat kota -->
              <select class="form-control" id="filter-grup" aria-label="Filter role" data-url="<?php echo base_url('setting_root/user'); ?>">
                <option value="">Semua role</option>
                <?php
                $info_grup = NULL; // rincian role terpilih, ditampilkan di atas tabel
                foreach ($daftar_grup as $g) {
                  $kuota = isset(KUOTA_GRUP_KHUSUS[(int) $g->id]) ? KUOTA_GRUP_KHUSUS[(int) $g->id] : NULL;
                  $lebih = ($kuota !== NULL && (int) $g->aktif > $kuota);
                  if ($grup_dipilih === (int) $g->id) {
                    $info_grup = array('nama' => $g->name, 'total' => (int) $g->total, 'aktif' => (int) $g->aktif, 'kuota' => $kuota, 'lebih' => $lebih);
                  }
                ?>
                <option value="<?php echo (int) $g->id; ?>"<?php echo $grup_dipilih === (int) $g->id ? ' selected' : ''; ?>><?php echo html_escape($g->name . ' (' . (int) $g->total . ')' . ($lebih ? ' ⚠' : '')); ?></option>
                <?php } ?>
              </select>
            </div>
            <?php } ?>

            <div class="col-md-3">

              <div class="input-group">

                <input type="text" class="form-control" name="data" placeholder="User Name ...">

                <span class="input-group-btn">

                  <input type="submit" name="submit" class="btn btn-info btn-flat" value="Go">

                </span>

              </div>

            </div>

            <?php echo form_close();?>

          </div>

          <!-- /.box-header -->

          <div class="table-responsive box-body">

           <?php

           $message = $this->session->flashdata('notif');

           if($message){

             echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';

           }

           ?>

           <?php if (!empty($info_grup)) { $nonaktif = $info_grup['total'] - $info_grup['aktif']; ?>
           <div class="alert <?php echo $info_grup['lebih'] ? 'alert-warning' : 'alert-info'; ?>" style="padding: 10px 15px;">
             <b>Role <?php echo html_escape($info_grup['nama']); ?>:</b>
             <?php echo $info_grup['total']; ?> akun &mdash; <?php echo $info_grup['aktif']; ?> aktif<?php echo $nonaktif > 0 ? ', ' . $nonaktif . ' nonaktif' : ''; ?>.
             <?php if ($info_grup['kuota'] !== NULL) { ?>
               Kuota <?php echo (int) $info_grup['kuota']; ?> akun aktif<?php
               if ($info_grup['lebih']) {
                 echo ' &mdash; <b>melebihi kuota ' . ($info_grup['aktif'] - $info_grup['kuota']) . ' akun</b>. Role ini tidak bisa diberikan ke akun lain sampai ada akun yang dinonaktifkan atau diganti role-nya';
               } elseif ($info_grup['aktif'] >= $info_grup['kuota']) {
                 echo ' &mdash; kuota penuh, role ini tidak bisa diberikan ke akun lain';
               } else {
                 echo ' &mdash; masih tersedia ' . ($info_grup['kuota'] - $info_grup['aktif']) . ' akun';
               }
               ?>.
             <?php } ?>
           </div>
           <?php } ?>

           <table class="table table-bordered table-striped table-hover">

            <tr class='info'>

              <th style="width: 200px">Nama User</th>

              <th style="width: 200px">Email</th>

              <th style="width: 200px">SKPD</th>

              <th style="width: 200px">Group</th>

              <th style="width: 100px">Aksi</th>

            </tr>

            <?php foreach($user as $v){?>

            <tr>

              <td><?php echo html_escape($v->username);?>
                <?php if (isset($v->active) && (int) $v->active !== 1) { ?>
                  <span class="label label-default" title="Akun nonaktif tidak dihitung dalam kuota role">Nonaktif</span>
                <?php } ?>
              </td>

              <td><?php echo html_escape($v->email);?></td>

              <td><?php echo html_escape($v->skpd_nama);?></td>

              <td><span class="label" style="background-color: <?php echo html_escape($v->bgcolor);?>;"><?php echo html_escape($v->name);?></span></td>

              <td>

               <?php
               // admin OPD tidak bisa mengelola akun bergrup khusus (Asisten, Sekda, Pimpinan DPRD, Walikota, Inspektorat)
               $grup_khusus_baris = isset($v->group_id) && in_array((int) $v->group_id, GRUP_KHUSUS_SUPER_ADMIN, TRUE);
               $super_baris = isset($v->group_id) && (int) $v->group_id === 100;
               // akun sendiri: ubah lewat halaman profil (update & hapus akun sendiri ditolak server)
               $baris_saya = (int) $v->id === (int) $this->session->userdata('user_id');
               if ($baris_saya) { ?>

               <a href="<?php echo base_url('setting_root/user/update_view2/' . (int) $v->id); ?>" class="btn btn-sm btn-flat btn-warning" title="Ubah data, SKPD dan password akun Anda"><i class="fa fa-user-edit"></i> PROFIL</a>

               <a href="<?php echo base_url('setting_root/log/status_log/' . (int) $v->id); ?>" class="btn btn-sm btn-flat btn-success"><i class="fa fa-eye"></i> Status Log</a>

               <?php } elseif ((int) $v->id === 1) { ?>

               <span class="text-muted" title="Akun utama super admin tidak bisa diubah, dihapus, atau dinonaktifkan dari akun lain"><i class="fa fa-lock"></i> Akun utama</span>

               <?php } elseif (!empty($boleh_reset_password) || !$grup_khusus_baris) { ?>

               <a href="<?php echo base_url();?>setting_root/user/update_view/<?php echo $v->id?>" class="btn btn-sm btn-flat btn-warning"><i class="fa fa-edit"></i> EDIT</a>

               <a href="<?php echo base_url();?>setting_root/user/delete/<?php echo $v->id?>" class="btn btn-sm btn-flat btn-danger" onclick="return confirm('Anda Yakin ?');"><i class="fa fa-trash"></i> HAPUS</a>

               <a href="<?php echo base_url();?>setting_root/log/status_log/<?php echo $v->id?>" class="btn btn-sm btn-flat btn-success"><i class="fa fa-eye"></i> Status Log</a>

               <?php if (!empty($boleh_reset_password)) {
                 $aktif_baris = (int) $v->active === 1;
                 $nama_js = html_escape(addslashes((string) $v->username));
               ?>
               <form action="<?php echo base_url('setting_root/user/ubah_status'); ?>" method="post" style="display:inline" onsubmit="return confirm('<?php echo $aktif_baris
                   ? 'Nonaktifkan user ' . $nama_js . '? Akun tidak bisa login, dan bila sedang login akan langsung keluar.'
                   : 'Aktifkan kembali user ' . $nama_js . '?'; ?>');">
                 <input type="hidden" name="user_id" value="<?php echo (int) $v->id; ?>">
                 <input type="hidden" name="token" value="<?php echo html_escape($token_reset_password); ?>">
                 <?php if ($aktif_baris) { ?>
                 <button type="submit" class="btn btn-sm btn-flat btn-default" title="Nonaktifkan akun (tidak bisa login)"><i class="fa fa-user-slash"></i> NONAKTIFKAN</button>
                 <?php } else { ?>
                 <button type="submit" class="btn btn-sm btn-flat bg-olive" title="Aktifkan kembali akun"><i class="fa fa-user-check"></i> AKTIFKAN</button>
                 <?php } ?>
               </form>

               <?php if (!$super_baris) { // password super admin hanya diganti pemiliknya lewat profil ?>
               <form action="<?php echo base_url('setting_root/user/reset_password'); ?>" method="post" style="display:inline" onsubmit="return confirm('Reset password user <?php echo $nama_js; ?> menjadi pass1234?');">
                 <input type="hidden" name="user_id" value="<?php echo (int) $v->id; ?>">
                 <input type="hidden" name="token" value="<?php echo html_escape($token_reset_password); ?>">
                 <button type="submit" class="btn btn-sm btn-flat btn-primary" title="Reset password ke pass1234"><i class="fa fa-key"></i> RESET PASSWORD</button>
               </form>
               <?php } ?>
               <?php } ?>

               <?php } ?>
              </td>

             </tr>

             <?php } ?>

           <?php if (empty($user)) { echo baris_data_kosong(5); } ?>
           </table>

         </div>

         <!-- /.box-body -->

         <div class="box-footer clearfix">

           <?php echo $links?>

         </div>

       </div>

       <!-- /.box -->

     </div>

     <!-- ./col -->

   </div>

   <!-- /.row -->

   

 </section>

 <!-- /.content -->

</div>

  <!-- /.content-wrapper -->

<?php if (!empty($daftar_grup)) { ?>
<script>
  // Filter role dengan kolom cari (Select2 dimuat di footer, dijalankan setelah halaman siap)
  $(function () {
    var $f = $('#filter-grup');
    $f.select2({ width: '100%', placeholder: 'Filter role', language: { noResults: function () { return 'Role tidak ditemukan'; } } });
    $f.on('change', function () {
      window.location.href = $f.data('url') + (this.value ? '?grup=' + encodeURIComponent(this.value) : '');
    });
  });
</script>
<?php } ?>
