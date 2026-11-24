  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-lg-12 col-xs-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">LOGIN DIBLOKIR</h3>
            </div>
            <div class="box-header with-border">
              <div class="col-md-9">
                <p class="text-muted" style="margin:0">
                  3 kali gagal login: dikunci 60 detik. 3 kali gagal berikutnya: diblokir sampai dibuka di halaman ini.
                </p>
              </div>
              <div class="col-md-3 text-right">
                <a href="<?php echo base_url();?>setting_root/login_blokir" class="btn btn-warning btn-flat">Refresh</a>
              </div>
            </div>
            <div class="table-responsive box-body">
              <?php
              $message = $this->session->flashdata('notif');
              if ($message) {
                echo '<p class="alert alert-info text-center"><b>' . $message . '</b></p>';
              }
              ?>
              <table class="table table-bordered table-striped table-hover">
                <tr class="info">
                  <th>Username</th>
                  <th>Nama / SKPD</th>
                  <th style="width: 110px">Status</th>
                  <th style="width: 80px">Gagal</th>
                  <th style="width: 130px">IP Terakhir</th>
                  <th style="width: 150px">Terakhir Gagal</th>
                  <th style="width: 120px">Aksi</th>
                </tr>
                <?php if (empty($daftar)) { ?>
                <tr>
                  <td colspan="7" class="text-center text-muted">Tidak ada akun yang diblokir atau dikunci.</td>
                </tr>
                <?php } ?>
                <?php foreach ($daftar as $d) { ?>
                <tr>
                  <td><?php echo html_escape($d->login); ?></td>
                  <td>
                    <?php if ($d->user_id) { ?>
                      <?php echo html_escape(trim($d->first_name . ' ' . $d->last_name)); ?><br>
                      <small class="text-muted"><?php echo html_escape((string) $d->skpd_nama); ?></small>
                    <?php } else { ?>
                      <small class="text-muted">Username tidak terdaftar</small>
                    <?php } ?>
                  </td>
                  <td>
                    <?php if ($d->diblokir) { ?>
                      <span class="label label-danger">Diblokir</span>
                    <?php } else { ?>
                      <span class="label label-warning">Dikunci <?php echo max(0, (int) $d->kunci_sampai - time()); ?> dtk</span>
                    <?php } ?>
                  </td>
                  <td><?php echo (int) $d->gagal; ?> kali</td>
                  <td><?php echo html_escape((string) $d->ip_terakhir); ?></td>
                  <td><?php echo $d->diubah_pada ? date('d-m-Y H:i:s', (int) $d->diubah_pada) : '-'; ?></td>
                  <td>
                    <?php echo form_open('setting_root/login_blokir/buka', 'style="margin:0"'); ?>
                      <input type="hidden" name="login" value="<?php echo html_escape($d->login); ?>">
                      <button type="submit" class="btn btn-sm btn-flat btn-success" onclick="return confirm('Buka blokir login untuk <?php echo html_escape(addslashes($d->login)); ?>?');">
                        <i class="fa fa-unlock"></i> BUKA
                      </button>
                    <?php echo form_close(); ?>
                  </td>
                </tr>
                <?php } ?>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->
