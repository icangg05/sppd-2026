<link rel="stylesheet" href="<?php echo base_url(); ?>assets2/bootstrap/css/bootstrap-toggle.css">
<script src="<?php echo base_url(); ?>assets2/bootstrap/js/bootstrap-toggle.js"></script>
<script src="<?php echo base_url(); ?>assets2/highchart/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets2/highchart/exporting.js"></script>
<!-- Content Wrapper. Contains page content -->
<style>
  /* Set the size of the div element that contains the map */
  #map {
    height: 400px;
    /* The height is 400 pixels */
    width: 100%;
    /* The width is the width of the web page */
  }

  /* Modern UI Enhancements */
  .content-wrapper {
    background-color: #f4f6f9;
  }

  /* Modern Small Box (Cards) */
  .small-box {
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    border: none;
    overflow: hidden;
  }

  .small-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15), 0 3px 6px rgba(0, 0, 0, 0.1);
  }

  .small-box .inner {
    padding: 20px 25px;
  }

  .small-box h3,
  .small-box h2 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 10px 0;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
  }

  .small-box p {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0;
    opacity: 0.9;
  }

  .small-box .icon {
    top: 10px;
    right: 15px;
    font-size: 70px;
    color: rgba(255, 215, 0, 0.5);
    /* Gold with transparency */
    z-index: 0;
    transition: all 0.3s linear;
  }

  .small-box:hover .icon {
    font-size: 75px;
    color: rgba(255, 215, 0, 0.8);
    /* Brighter Gold on hover */
    transform: rotate(5deg) scale(1.1);
    z-index: 0;
  }

  /* Modern Box (Containers) */
  .box {
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border: none;
  }

  .box.box-primary {
    border-top: 3px solid #3c8dbc;
  }

  .box.box-info {
    border-top: 3px solid #00c0ef;
  }

  .box.box-danger {
    border-top: 3px solid #dd4b39;
  }

  .box.box-warning {
    border-top: 3px solid #f39c12;
  }

  .box.box-success {
    border-top: 3px solid #00a65a;
  }

  .box-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f4f4f4;
  }

  .box-header .box-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
  }

  .box-body {
    padding: 20px;
  }

  /* Breadcrumb Modernization */
  .content-header h1 {
    font-size: 24px;
    font-weight: 600;
    color: #2c3e50;
  }

  /* Toggle Switch Container */
  .toggle.btn {
    border-radius: 20px;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .toggle-handle {
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> <?php if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id == "") {
            echo "ADMINISTRATOR";
          } else if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id != "") {
            echo "ADMINISTRATOR " . strtoupper($nama_skpd[0]['skpd_nama']);;
          } else {
            echo strtoupper($nama_skpd[0]['skpd_nama']);
          }     ?> </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <!------ WALIKOTA ------> <?php if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 6) { ?>
      <div class="row">
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Status Kehadiran Walikota</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <center>
              <div class="table-responsive box-body">
                <div class="form-group">
                  <div class="col-sm-12">
                    <div id="walikota"> <input type="checkbox" data-toggle="toggle" data-on="Hadir" data-off="Tidak Hadir" <?php if ($posisi_walikota[0]['status'] == '1') echo 'checked'; ?> /> </div>
                    <span class="uk-form-help-block">
                      <div id="resultWalikota"> </div>
                    </span>
                  </div>
                </div>
              </div>
            </center>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->




      <!------ ADMIN OPD ------>
    <?php } else if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 1) || ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 10)) { ?>
      <!-- ./col -->
      <div class="row">
        <div class="col-lg-4 col-xs-12">
          <!-- small box -->
          <a href="<?php echo base_url() ?>setting_admin/pegawai">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3><?php echo $total_pegawai[0]['total_pegawai']; ?></h3>
                <br>
                <p><b>TOTAL PEGAWAI</b></p>
              </div>
              <div class="icon"> <i class="fa fa-users"></i> </div>
            </div>
          </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-12">
          <!-- small box -->
          <a href="#" data-toggle="modal" data-target="#myModal1">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php echo $count_perjalanan; ?></h3>
                <br>
                <p><b>TOTAL PERJALANAN DINAS</b></p>
              </div>
              <div class="icon"> <i class="fa fa-plane"></i> </div>
            </div>
          </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Status Kehadiran Kepala OPD</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <center>
              <div class="box-body">
                <div class="form-group">
                  <div class="col-sm-12">
                    <div id="kaopd"> <input type="checkbox" data-toggle="toggle" data-on="Hadir" data-off="Tidak Hadir" <?php if ($posisi_kaopd[0]['status'] == '1') echo 'checked'; ?> /> </div>
                    <span class="uk-form-help-block">
                      <div id="resultKaopd"> </div>
                    </span>
                  </div>
                </div>
                <!-- /.box-body -->
              </div>
            </center>
            <br> <!-- /.box -->
          </div>
        </div>

        <?php $this->load->view('modal_jumlah_perjalanan'); ?>
        <?php $this->load->view('grafik_anggaran'); ?>
        <?php $this->load->view('kegiatan'); ?>

        <!------ DPRD ------>
      <?php } else if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 2) { ?>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-12">
          <!-- small box -->
          <a href="<?php echo base_url() ?>setting_admin/pegawai">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3><?php echo $total_pegawai[0]['total_pegawai']; ?></h3>
                <br>
                <p><b>TOTAL PEGAWAI</b></p>
              </div>
              <div class="icon"> <i class="fa fa-users"></i> </div>
            </div>
          </a>
        </div>

        <!-- ./col -->
        <div class="col-lg-4 col-xs-12">
          <!-- small box -->
          <a href="#" data-toggle="modal" data-target="#myModal1">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php echo $count_perjalanan; ?></h3>
                <br>
                <p><b>TOTAL PERJALANAN DINAS</b></p>
              </div>
              <div class="icon"> <i class="fa fa-plane"></i> </div>
            </div>
          </a>
        </div>

        <!-- ./col -->
        <div class="col-lg-4 col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Status Kehadiran Pimpinan DPRD</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <center>
              <div class="box-body">
                <div class="form-group">
                  <div class="col-sm-12">
                    <div id="kadprd"> <input type="checkbox" data-toggle="toggle" data-on="Hadir" data-off="Tidak Hadir" <?php if ($posisi_kadprd[0]['status'] == '1') echo 'checked'; ?> /> </div>
                    <span class="uk-form-help-block">
                      <div id="resultKadprd"> </div>
                    </span>
                  </div>
                </div>
                <!-- /.box-body -->
              </div>
            </center>
            <!-- /.box -->
          </div>
        </div>
        <!-- /.row -->

        <?php $this->load->view('modal_jumlah_perjalanan'); ?>
        <?php $this->load->view('grafik_anggaran'); ?>
        <?php $this->load->view('kegiatan'); ?>

        <!------ SEKDA ------>
      <?php } else if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 3) { ?>
        <div class="row">
          <div class="col-lg-4 col-xs-12">
            <!-- small box -->
            <a href="<?php echo base_url() ?>setting_admin/pegawai">
              <div class="small-box bg-primary">
                <div class="inner">
                  <h3><?php echo $total_pegawai[0]['total_pegawai']; ?></h3>
                  <br>
                  <p><b>TOTAL PEGAWAI</b></p>
                </div>
                <div class="icon"> <i class="fa fa-users"></i> </div>
              </div>
            </a>
          </div>

          <!-- ./col -->
          <div class="col-lg-4 col-xs-12">
            <!-- small box -->
            <a href="#" data-toggle="modal" data-target="#myModal1">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo $count_perjalanan; ?></h3>
                  <br>
                  <p><b>TOTAL PERJALANAN DINAS</b></p>
                </div>
                <div class="icon"> <i class="fa fa-plane"></i> </div>
              </div>
            </a>
          </div>

          <div class="col-lg-4 col-xs-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Status Kehadiran Sekda</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <center>
                <div class="box-body">
                  <div class="form-group">
                    <div class="col-sm-12">
                      <div id="sekda"> <input type="checkbox" data-toggle="toggle" data-on="Hadir" data-off="Tidak Hadir" <?php if ($posisi_sekda[0]['status'] == '1') echo 'checked'; ?> /> </div>
                      <span class="uk-form-help-block">
                        <div id="resultSekda"> </div>
                      </span>
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
              </center>
            </div>
            <!-- /.box -->
          </div>
          <!-- ./col -->

          <?php $this->load->view('modal_jumlah_perjalanan'); ?>
          <?php $this->load->view('grafik_anggaran'); ?>
          <?php $this->load->view('kegiatan'); ?>


          <!------ KECAMATAN ------>
        <?php } else if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 4)
                                || ($this->ion_auth->get_users_groups()->row()->id == 14 && $this->ion_auth->user()->row()->jenis_skpd == 4)
                                || ($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 4)
                              ) { ?>
          <div class="row">
            <div class="col-lg-4 col-xs-12">
              <!-- small box -->
              <a href="<?php echo base_url() ?>setting_admin/pegawai">
                <div class="small-box bg-primary">
                  <div class="inner">
                    <h3><?php echo $total_pegawai[0]['total_pegawai']; ?></h3>
                    <br>
                    <p><b>TOTAL PEGAWAI</b></p>
                  </div>
                  <div class="icon"> <i class="fa fa-users"></i> </div>
                </div>
              </a>
            </div>

            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
              <!-- small box -->
              <a href="#" data-toggle="modal" data-target="#myModal1">
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3><?php echo $count_perjalanan; ?></h3>
                    <br>
                    <p><b>TOTAL PERJALANAN DINAS</b></p>
                  </div>
                  <div class="icon"> <i class="fa fa-plane"></i> </div>
                </div>
              </a>
            </div>

            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Status Kehadiran Camat</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <center>
                  <div class="box-body">
                    <div class="form-group">
                      <div class="col-sm-12">
                        <div id="kaopd"> <input type="checkbox" data-toggle="toggle" data-on="Hadir" data-off="Tidak Hadir" <?php if ($posisi_kaopd[0]['status'] == '1') echo 'checked'; ?> /> </div>
                        <span class="uk-form-help-block">
                          <div id="resultKaopd"> </div>
                        </span>
                      </div>
                    </div>
                    <!-- /.box-body -->
                  </div>
                </center>
                <!-- /.box -->
              </div>
            </div>

            <?php $this->load->view('modal_jumlah_perjalanan'); ?>
            <?php $this->load->view('grafik_anggaran'); ?>
            <?php $this->load->view('kegiatan'); ?>

            <!------ KELURAHAN ------>
          <?php } else if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 5)
                                || ($this->ion_auth->get_users_groups()->row()->id == 13 && $this->ion_auth->user()->row()->jenis_skpd == 5)
                                || ($this->ion_auth->get_users_groups()->row()->id == 15 && $this->ion_auth->user()->row()->jenis_skpd == 5)
                              ) { ?>
            <div class="row">
              <!-- ./col -->
              <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <a href="<?php echo base_url() ?>setting_admin/pegawai">
                  <div class="small-box bg-primary">
                    <div class="inner">
                      <h3><?php echo $total_pegawai[0]['total_pegawai']; ?></h3>
                      <br>
                      <p><b>TOTAL PEGAWAI</b></p>
                    </div>
                    <div class="icon"> <i class="fa fa-users"></i> </div>
                  </div>
                </a>
              </div>

              <!-- ./col -->
              <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <a href="#" data-toggle="modal" data-target="#myModal1">
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3><?php echo $count_perjalanan; ?></h3>
                      <br>
                      <p><b>TOTAL PERJALANAN DINAS</b></p>
                    </div>
                    <div class="icon"> <i class="fa fa-plane"></i> </div>
                  </div>
                </a>
              </div>

              <!-- ./col -->
              <div class="col-lg-4 col-xs-12">
                <div class="box box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Status Kehadiran Lurah</h3>
                  </div>
                  <!-- /.box-header -->
                  <!-- form start -->
                  <center>
                    <div class="box-body">
                      <div class="form-group">
                        <div class="col-sm-12">
                          <div id="kaopd"> <input type="checkbox" data-toggle="toggle" data-on="Hadir" data-off="Tidak Hadir" <?php if ($posisi_kaopd[0]['status'] == '1') echo 'checked'; ?> /> </div>
                          <span class="uk-form-help-block">
                            <div id="resultKaopd"> </div>
                          </span>
                        </div>
                      </div>
                      <!-- /.box-body -->
                    </div>
                  </center>
                  <!-- /.box -->
                </div>
              </div>

              <?php $this->load->view('modal_jumlah_perjalanan'); ?>
              <?php $this->load->view('grafik_anggaran'); ?>
              <?php $this->load->view('kegiatan'); ?>

              <!------ PUSKESMAS ------>
            <?php } else if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 7)
                                || ($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 7)
                              ) { ?>
              <div class="row">
                <!-- ./col -->
                <div class="col-lg-4 col-xs-12">
                  <!-- small box -->
                  <a href="<?php echo base_url() ?>setting_admin/pegawai">
                    <div class="small-box bg-primary">
                      <div class="inner">
                        <h3><?php echo $total_pegawai[0]['total_pegawai']; ?></h3>
                        <br>
                        <p><b>TOTAL PEGAWAI</b></p>
                      </div>
                      <div class="icon"> <i class="fa fa-users"></i> </div>
                    </div>
                  </a>
                </div>

                <!-- ./col -->
                <div class="col-lg-4 col-xs-12">
                  <!-- small box -->
                  <a href="#" data-toggle="modal" data-target="#myModal1">
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h3><?php echo $count_perjalanan; ?></h3>
                        <br>
                        <p><b>TOTAL PERJALANAN DINAS</b></p>
                      </div>
                      <div class="icon"> <i class="fa fa-plane"></i> </div>
                    </div>
                  </a>
                </div>

                <div class="col-lg-4 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Status Kehadiran Kepala Puskesmas</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <center>
                      <div class="box-body">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <div id="kaopd"> <input type="checkbox" data-toggle="toggle" data-on="Hadir" data-off="Tidak Hadir" <?php if ($posisi_kaopd[0]['status'] == '1') echo 'checked'; ?> /> </div>
                            <span class="uk-form-help-block">
                              <div id="resultKaopd"> </div>
                            </span>
                          </div>
                        </div>
                        <!-- /.box-body -->
                      </div>
                    </center>
                    <!-- /.box -->
                  </div>
                </div>

                <?php $this->load->view('modal_jumlah_perjalanan'); ?>
                <?php $this->load->view('grafik_anggaran'); ?>
                <?php $this->load->view('kegiatan'); ?>

                <!------ KASUBAG OPD ------>
              <?php } else if (($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 1) || ($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 10)) { ?>
                <div class="row">
                  <div class="col-lg-4 col-xs-12">
                    <!-- small box -->
                    <a href="<?php echo base_url() ?>setting_admin/pegawai">
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><?php echo $total_pegawai[0]['total_pegawai']; ?></h3>
                          <br>
                          <p><b>TOTAL PEGAWAI</b></p>
                        </div>
                        <div class="icon"> <i class="fa fa-users"></i> </div>
                      </div>
                    </a>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-4 col-xs-12">
                    <!-- small box -->
                    <a href="#" data-toggle="modal" data-target="#myModal1">
                      <div class="small-box bg-aqua">
                        <div class="inner">
                          <h3><?php echo $count_perjalanan; ?></h3>
                          <br>
                          <p><b>TOTAL PERJALANAN DINAS</b></p>
                        </div>
                        <div class="icon"> <i class="fa fa-plane"></i> </div>
                      </div>
                    </a>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-4 col-xs-12">
                    <div class="box box-primary">
                      <div class="box-header with-border">
                        <h3 class="box-title">Status Kehadiran Kepala OPD</h3>
                      </div>
                      <!-- /.box-header -->
                      <!-- form start -->
                      <center>
                        <div class="box-body">
                          <div class="form-group">
                            <div class="col-sm-12">
                              <div id="kaopd"> <input type="checkbox" data-toggle="toggle" data-on="Hadir" data-off="Tidak Hadir" <?php if ($posisi_kaopd[0]['status'] == '1') echo 'checked'; ?> /> </div>
                              <span class="uk-form-help-block">
                                <div id="resultKaopd"> </div>
                              </span>
                            </div>
                          </div>
                          <!-- /.box-body -->
                        </div>
                      </center>
                      <br> <!-- /.box -->
                    </div>
                  </div>

                  <?php $this->load->view('modal_jumlah_perjalanan'); ?>
                  <?php $this->load->view('grafik_anggaran'); ?>
                  <?php $this->load->view('kegiatan'); ?>

                  <!------ KASUBAG DPRD ------>
                <?php } else if ($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 2) { ?>
                  <!-- ./col -->
                  <div class="col-lg-4 col-xs-12">
                    <!-- small box -->
                    <a href="<?php echo base_url() ?>setting_admin/pegawai">
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><?php echo $total_pegawai[0]['total_pegawai']; ?></h3>
                          <br>
                          <p><b>TOTAL PEGAWAI</b></p>
                        </div>
                        <div class="icon"> <i class="fa fa-users"></i> </div>
                      </div>
                    </a>
                  </div>

                  <!-- ./col -->
                  <div class="col-lg-4 col-xs-12">
                    <!-- small box -->
                    <a href="#" data-toggle="modal" data-target="#myModal1">
                      <div class="small-box bg-aqua">
                        <div class="inner">
                          <h3><?php echo $count_perjalanan; ?></h3>
                          <br>
                          <p><b>TOTAL PERJALANAN DINAS</b></p>
                        </div>
                        <div class="icon"> <i class="fa fa-plane"></i> </div>
                      </div>
                    </a>
                  </div>

                  <!-- ./col -->
                  <div class="col-lg-4 col-xs-12">
                    <div class="box box-primary">
                      <div class="box-header with-border">
                        <h3 class="box-title">Status Kehadiran Pimpinan DPRD</h3>
                      </div>
                      <!-- /.box-header -->
                      <!-- form start -->
                      <center>
                        <div class="box-body">
                          <div class="form-group">
                            <div class="col-sm-12">
                              <div id="kadprd"> <input type="checkbox" data-toggle="toggle" data-on="Hadir" data-off="Tidak Hadir" <?= $posisi_kadprd[0]['status'] == '1' ? 'checked' : '' ?> /> </div>
                              <span class="uk-form-help-block">
                                <div id="resultKadprd"></div>
                              </span>
                            </div>
                          </div>
                        </div>
                      </center>

                      <!-- /.box -->
                    </div>
                  </div>
                  <!-- /.row -->

                  <?php $this->load->view('modal_jumlah_perjalanan'); ?>
                  <?php $this->load->view('grafik_anggaran'); ?>
                  <?php $this->load->view('kegiatan'); ?>

                  <!------ KASUBAG SEKDA ------>
                <?php } else if ($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 3) { ?>
                  <div class="row">
                    <!-- ./col -->
                    <div class="col-lg-4 col-xs-12">
                      <!-- small box -->
                      <a href="">
                        <div class="small-box bg-primary">
                          <div class="inner">
                            <h3><?php echo $total_pegawai[0]['total_pegawai']; ?></h3>
                            <br>
                            <p><b>TOTAL PEGAWAI</b></p>
                          </div>
                          <div class="icon"> <i class="fa fa-users"></i> </div>
                        </div>
                      </a>
                    </div>

                    <!-- ./col -->
                    <div class="col-lg-4 col-xs-12">
                      <!-- small box -->
                      <a href="#" data-toggle="modal" data-target="#myModal1">
                        <div class="small-box bg-aqua">
                          <div class="inner">
                            <h3><?php echo $count_perjalanan; ?></h3>
                            <br>
                            <p><b>TOTAL PERJALANAN DINAS</b></p>
                          </div>
                          <div class="icon"> <i class="fa fa-plane"></i> </div>
                        </div>
                      </a>
                    </div>


                    <div class="col-lg-4 col-xs-12">
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title">Status Kehadiran Sekda</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <center>
                          <div class="box-body">
                            <div class="form-group">
                              <div class="col-sm-12">
                                <div id="sekda"> <input type="checkbox" data-toggle="toggle" data-on="Hadir" data-off="Tidak Hadir" <?php if ($posisi_sekda[0]['status'] == '1') echo 'checked'; ?> /> </div>
                                <span class="uk-form-help-block">
                                  <div id="resultSekda"> </div>
                                </span>
                              </div>
                            </div>
                          </div>
                          <!-- /.box-body -->
                        </center>
                      </div>
                      <!-- /.box -->
                    </div>
                    <!-- ./col -->

                    <?php $this->load->view('modal_jumlah_perjalanan'); ?>
                    <?php $this->load->view('grafik_anggaran'); ?>
                    <?php $this->load->view('kegiatan'); ?>

                  <?php } else if ($this->ion_auth->get_users_groups()->row()->id == 2 || $this->ion_auth->get_users_groups()->row()->id == 3 || $this->ion_auth->get_users_groups()->row()->id == 4 || $this->ion_auth->get_users_groups()->row()->id == 5 || $this->ion_auth->get_users_groups()->row()->id == 6 || $this->ion_auth->get_users_groups()->row()->id == 7 || $this->ion_auth->get_users_groups()->row()->id == 10 || $this->ion_auth->get_users_groups()->row()->id == 11 || $this->ion_auth->get_users_groups()->row()->id == 12 || $this->ion_auth->get_users_groups()->row()->id == 16) { ?>
                    <div class="row">
                      <!-- ./col -->
                      <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                        <?php
                                switch ($this->ion_auth->get_users_groups()->row()->id) {
                                  case 2:
                                    echo "<a href=" . base_url('telaah/disposisi/index/kabid') . ">";
                                    break;
                                  case 3:
                                    echo "<a href=" . base_url('telaah/disposisi/index/sekdis') . ">";
                                    break;
                                  case 4:
                                    echo "<a href=" . base_url('telaah/disposisi/index/kadis') . ">";
                                    break;
                                  case 5:
                                    echo "<a href=" . base_url('telaah/disposisi/index/asisten') . ">";
                                    break;
                                  case 6:
                                    echo "<a href=" . base_url('telaah/disposisi/index/sekda') . ">";
                                    break;
                                  case 7:
                                    echo "<a href=" . base_url('telaah/disposisi/index/kadprd') . ">";
                                    break;
                                  case 10:
                                    echo "<a href=" . base_url('telaah/disposisi/index/sekwan') . ">";
                                    break;
                                  case 11:
                                    echo "<a href=" . base_url('telaah/disposisi/index/camat') . ">";
                                    break;
                                  case 12:
                                    echo "<a href=" . base_url('telaah/disposisi/index/sekcam') . ">";
                                    break;
                                  case 16:
                                    echo "<a href=" . base_url('telaah/disposisi/index/kapus') . ">";
                                    break;
                                }
                        ?>
                        <div class="small-box bg-aqua">
                          <div class="inner">
                            <h2><?php echo $total_list_telaah; ?></h2>
                            <br>
                            <p>PERMOHONAN TELAAH STAFF</p>
                          </div>
                          <div class="icon"> <i class="fa fa-bars"></i> </div>
                        </div>
                        </a>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                        <?php
                                switch ($this->ion_auth->get_users_groups()->row()->id) {
                                  case 2:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_disetujui/kabid') . ">";
                                    break;
                                  case 3:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_disetujui/sekdis') . ">";
                                    break;
                                  case 4:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_disetujui/kadis') . ">";
                                    break;
                                  case 5:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_disetujui/asisten') . ">";
                                    break;
                                  case 6:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_disetujui/sekda') . ">";
                                    break;
                                  case 7:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_disetujui/kadprd') . ">";
                                    break;
                                  case 10:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_disetujui/sekwan') . ">";
                                    break;
                                  case 11:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_disetujui/camat') . ">";
                                    break;
                                  case 12:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_disetujui/sekcam') . ">";
                                    break;
                                  case 16:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_disetujui/kapus') . ">";
                                    break;
                                }
                        ?>
                        <div class="small-box bg-green">
                          <div class="inner">
                            <h2><?php echo $total_list_telaah_diterima; ?></h2>
                            <br>
                            <p>TELAAH STAFF DISETUJUI</p>
                          </div>
                          <div class="icon"> <i class="fa fa-check"></i> </div>
                        </div>
                        </a>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                        <?php
                                switch ($this->ion_auth->get_users_groups()->row()->id) {
                                  case 2:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_ditolak/kabid') . ">";
                                    break;
                                  case 3:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_ditolak/sekdis') . ">";
                                    break;
                                  case 4:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_ditolak/kadis') . ">";
                                    break;
                                  case 5:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_ditolak/asisten') . ">";
                                    break;
                                  case 6:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_ditolak/sekda') . ">";
                                    break;
                                  case 7:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_ditolak/kadprd') . ">";
                                    break;
                                  case 10:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_ditolak/sekwan') . ">";
                                    break;
                                  case 11:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_ditolak/camat') . ">";
                                    break;
                                  case 12:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_ditolak/sekcam') . ">";
                                    break;
                                  case 16:
                                    echo "<a href=" . base_url('telaah/disposisi/telaah_ditolak/kapus') . ">";
                                    break;
                                }
                        ?>
                        <div class="small-box bg-red">
                          <div class="inner">
                            <h2><?php echo $total_list_telaah_ditolak; ?></h2>
                            <br>
                            <p>TELAAH STAFF DITOLAK</p>
                          </div>
                          <div class="icon"> <i class="ion ion-close"></i> </div>
                        </div>
                        </a>
                      </div>

                      <?php if ($this->ion_auth->get_users_groups()->row()->id == 4) { ?>
                        <?php $this->load->view('grafik_anggaran'); ?>
                        <?php $this->load->view('kegiatan'); ?>
                      <?php } ?>
                      <!-- ./col -->
                    </div>
                    <!-- /.row --> <?php } else if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id == "") || ($this->ion_auth->get_users_groups()->row()->id == 100)) { ?>
                    <div class="row">
                      <!-- ./col -->
                      <div class="col-lg-6 col-xs-6">
                        <!-- small box -->
                        <a href="">
                          <div class="small-box bg-aqua">
                            <div class="inner">
                              <h3><?php echo $total_pegawai[0]['total_pegawai']; ?></h3>
                              <br>
                              <p>JUMLAH PEGAWAI</p>
                            </div>
                            <div class="icon"> <i class="fa fa-users"></i> </div>
                          </div>
                        </a>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-6 col-xs-6">
                        <!-- small box -->
                        <a href="">
                          <div class="small-box bg-yellow">
                            <div class="inner">
                              <h3><?php echo $total_skpd[0]['total_skpd']; ?></h3>
                              <br>
                              <p>JUMLAH SKPD</p>
                            </div>
                            <div class="icon"> <i class="fa fa-home"></i> </div>
                          </div>
                        </a>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                        <a href="">
                          <div class="small-box bg-primary">
                            <div class="inner">
                              <h3><?php echo $total_list_telaah[0]['total_list_telaah']; ?></h3>
                              <br>
                              <p>JUMLAH LIST TELAAH</p>
                            </div>
                            <div class="icon"> <i class="fa fa-bars"></i> </div>
                          </div>
                        </a>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                        <a href="">
                          <div class="small-box bg-green">
                            <div class="inner">
                              <h3><?php echo $total_list_telaah_diterima[0]['total_list_telaah_diterima']; ?></h3>
                              <br>
                              <p>JUMLAH LIST TELAAH DITERIMA</p>
                            </div>
                            <div class="icon"> <i class="fa fa-check"></i> </div>
                          </div>
                        </a>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                        <a href="">
                          <div class="small-box bg-red">
                            <div class="inner">
                              <h3><?php echo $total_list_telaah_ditolak[0]['total_list_telaah_ditolak']; ?></h3>
                              <br>
                              <p>JUMLAH LIST TELAAH DITOLAK</p>
                            </div>
                            <div class="icon"> <i class="ion ion-close"></i> </div>
                          </div>
                        </a>
                      </div>
                      <!-- ./col -->
                    </div>
                    <!-- /.row -->
                  <?php } else if (
                                    $this->ion_auth->get_users_groups()->row()->id == 8
                                    || $this->ion_auth->get_users_groups()->row()->id == 17
                                  ) { ?>

                    <?php if ($this->ion_auth->get_users_groups()->row()->id == 8) { ?>

                      <div class="row">
                        <!-- ./col -->
                        <div class="col-lg-4 col-xs-6">
                          <!-- small box -->
                          <a href="<?php echo site_url('telaah/disposisi/index/walikota') ?>">
                            <div class="small-box bg-aqua">
                              <div class="inner">
                                <h2><?php echo $total_list_telaah; ?></h2>
                                <br>
                                <p>PERMOHONAN TELAAH STAFF</p>
                              </div>
                              <div class="icon"> <i class="fa fa-bars"></i> </div>
                            </div>
                          </a>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-4 col-xs-6">
                          <!-- small box -->
                          <a href="<?php echo site_url('telaah/disposisi/telaah_disetujui/walikota') ?>">
                            <div class="small-box bg-green">
                              <div class="inner">
                                <h2><?php echo $total_list_telaah_diterima; ?></h2>
                                <br>
                                <p>TELAAH STAFF DISETUJUI</p>
                              </div>
                              <div class="icon"> <i class="fa fa-check"></i> </div>
                            </div>
                          </a>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-4 col-xs-6">
                          <!-- small box -->
                          <a href="<?php echo site_url('telaah/disposisi/telaah_ditolak/walikota') ?>">
                            <div class="small-box bg-red">
                              <div class="inner">
                                <h2><?php echo $total_list_telaah_ditolak; ?></h2>
                                <br>
                                <p>TELAAH STAFF DITOLAK</p>
                              </div>
                              <div class="icon"> <i class="ion ion-close"></i> </div>
                            </div>
                          </a>
                        </div>
                        <!-- ./col -->
                      </div>

                    <?php } ?>

                    <div class="row">
                      <div class="col-md-3">
                        <!-- general form elements -->
                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>SPPD WALIKOTA</b></h3>
                          </div>
                          <!-- /.box-header -->
                          <!-- form start -->
                          <form role="form">
                            <div class="box-body">
                              <label>TELAAH</label>
                              <table class="table">
                                <tr>
                                  <td>Total</td>
                                  <td><span class="badge bg-aqua"><?php echo number_format($total8[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Masuk</td>
                                  <td><span class="badge bg-default"><?php echo number_format($totalMasuk8[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Proses</td>
                                  <td><span class="badge bg-yellow"><?php echo number_format($totalProses8[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Selesai</td>
                                  <td><span class="badge bg-green"><?php echo number_format($totalTerima8[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Tolak</td>
                                  <td><span class="badge bg-red"><?php echo number_format($totalTolak8[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                              </table>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                              <a href="<?php echo base_url(); ?>telaah/disposisi/data/8" class="btn btn-primary btn-flat btn-block">Lihat Telaah</a>
                            </div>
                          </form>
                        </div>

                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>SPPD KEPALA OPD</b></h3>
                          </div>
                          <!-- /.box-header -->
                          <!-- form start -->
                          <form role="form">
                            <div class="box-body">
                              <label>TELAAH</label>
                              <table class="table">
                                <tr>
                                  <td>Total</td>
                                  <td><span class="badge bg-aqua"><?php echo number_format($total2[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Masuk</td>
                                  <td><span class="badge bg-default"><?php echo number_format($totalMasuk2[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Proses</td>
                                  <td><span class="badge bg-yellow"><?php echo number_format($totalProses2[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Selesai</td>
                                  <td><span class="badge bg-green"><?php echo number_format($totalTerima2[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Tolak</td>
                                  <td><span class="badge bg-red"><?php echo number_format($totalTolak2[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                              </table>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                              <a href="<?php echo base_url(); ?>telaah/disposisi/data/2" class="btn btn-primary btn-flat btn-block">Lihat Telaah</a>
                            </div>
                          </form>
                        </div>

                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>SPPD SEKDA/ASISTEN/KABAG</b></h3>
                          </div>
                          <!-- /.box-header -->
                          <!-- form start -->
                          <form role="form">
                            <div class="box-body">
                              <label>TELAAH</label>
                              <table class="table">
                                <tr>
                                  <td>Total</td>
                                  <td><span class="badge bg-aqua"><?php echo number_format($total4[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Masuk</td>
                                  <td><span class="badge bg-default"><?php echo number_format($totalMasuk4[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Proses</td>
                                  <td><span class="badge bg-yellow"><?php echo number_format($totalProses4[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Selesai</td>
                                  <td><span class="badge bg-green"><?php echo number_format($totalTerima4[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Tolak</td>
                                  <td><span class="badge bg-red"><?php echo number_format($totalTolak4[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                              </table>
                            </div>
                            <!-- /.box-body -->
                            <!-- /.box-body -->

                            <div class="box-footer">
                              <a href="<?php echo base_url(); ?>telaah/disposisi/data/4" class="btn btn-primary btn-flat btn-block">Lihat Telaah</a>
                            </div>
                          </form>
                        </div>

                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>SPPD ANGGOTA DPRD</b></h3>
                          </div>
                          <!-- /.box-header -->
                          <!-- form start -->
                          <form role="form">
                            <div class="box-body">
                              <label>TELAAH</label>
                              <table class="table">
                                <tr>
                                  <td>Total</td>
                                  <td><span class="badge bg-aqua"><?php echo number_format($total3[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Masuk</td>
                                  <td><span class="badge bg-default"><?php echo number_format($totalMasuk3[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Proses</td>
                                  <td><span class="badge bg-yellow"><?php echo number_format($totalProses3[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Selesai</td>
                                  <td><span class="badge bg-green"><?php echo number_format($totalTerima3[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Tolak</td>
                                  <td><span class="badge bg-red"><?php echo number_format($totalTolak3[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                              </table>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                              <a href="<?php echo base_url(); ?>telaah/disposisi/data/3" class="btn btn-primary btn-flat btn-block">Lihat Telaah</a>
                            </div>
                          </form>
                        </div>

                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>CAMAT & LURAH</b></h3>
                          </div>
                          <!-- /.box-header -->
                          <!-- form start -->
                          <form role="form">
                            <div class="box-body">
                              <label>TELAAH</label>
                              <table class="table">
                                <tr>
                                  <td>Total</td>
                                  <td><span class="badge bg-aqua"><?php echo number_format($total5[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Masuk</td>
                                  <td><span class="badge bg-default"><?php echo number_format($totalMasuk5[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Proses</td>
                                  <td><span class="badge bg-yellow"><?php echo number_format($totalProses5[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Selesai</td>
                                  <td><span class="badge bg-green"><?php echo number_format($totalTerima5[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Tolak</td>
                                  <td><span class="badge bg-red"><?php echo number_format($totalTolak5[0]['jumlah'], 0, ",", "."); ?></span></td>
                                  </tr-->
                              </table>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                              <a href="<?php echo base_url(); ?>telaah/disposisi/data/5" class="btn btn-primary btn-flat btn-block">Lihat Telaah</a>
                            </div>
                          </form>
                        </div>

                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>SPPD ESELON III, IV & STAF</b></h3>
                          </div>
                          <!-- /.box-header -->
                          <!-- form start -->
                          <form role="form">
                            <div class="box-body">
                              <label>TELAAH</label>
                              <table class="table">
                                <tr>
                                  <td>Total</td>
                                  <td><span class="badge bg-aqua"><?php echo number_format($total1[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Masuk</td>
                                  <td><span class="badge bg-default"><?php echo number_format($totalMasuk1[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Proses</td>
                                  <td><span class="badge bg-yellow"><?php echo number_format($totalProses1[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Selesai</td>
                                  <td><span class="badge bg-green"><?php echo number_format($totalTerima1[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Tolak</td>
                                  <td><span class="badge bg-red"><?php echo number_format($totalTolak1[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                              </table>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                              <a href="<?php echo base_url(); ?>telaah/disposisi/data/1" class="btn btn-primary btn-flat btn-block">Lihat Telaah</a>
                            </div>
                          </form>
                        </div>

                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>SPPD STAF DPRD</b></h3>
                          </div>
                          <!-- /.box-header -->
                          <!-- form start -->
                          <form role="form">
                            <div class="box-body">
                              <label>TELAAH</label>
                              <table class="table">
                                <tr>
                                  <td>Total</td>
                                  <td><span class="badge bg-aqua"><?php echo number_format($total6[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Masuk</td>
                                  <td><span class="badge bg-default"><?php echo number_format($totalMasuk6[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Proses</td>
                                  <td><span class="badge bg-yellow"><?php echo number_format($totalProses6[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Selesai</td>
                                  <td><span class="badge bg-green"><?php echo number_format($totalTerima6[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Tolak</td>
                                  <td><span class="badge bg-red"><?php echo number_format($totalTolak6[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                              </table>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                              <a href="<?php echo base_url(); ?>telaah/disposisi/data/6" class="btn btn-primary btn-flat btn-block">Lihat Telaah</a>
                            </div>
                          </form>
                        </div>

                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>SPPD STAF CAMAT & LURAH</b></h3>
                          </div>
                          <!-- /.box-header -->
                          <!-- form start -->
                          <form role="form">
                            <div class="box-body">
                              <label>TELAAH</label>
                              <table class="table">
                                <tr>
                                  <td>Total</td>
                                  <td><span class="badge bg-aqua"><?php echo number_format($total7[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Masuk</td>
                                  <td><span class="badge bg-default"><?php echo number_format($totalMasuk7[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Proses</td>
                                  <td><span class="badge bg-yellow"><?php echo number_format($totalProses7[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Selesai</td>
                                  <td><span class="badge bg-green"><?php echo number_format($totalTerima7[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Tolak</td>
                                  <td><span class="badge bg-red"><?php echo number_format($totalTolak7[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                              </table>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                              <a href="<?php echo base_url(); ?>telaah/disposisi/data/7" class="btn btn-primary btn-flat btn-block">Lihat Telaah</a>
                            </div>
                          </form>
                        </div>

                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>SPPD STAF SETDA</b></h3>
                          </div>
                          <!-- /.box-header -->
                          <!-- form start -->
                          <form role="form">
                            <div class="box-body">
                              <label>TELAAH</label>
                              <table class="table">
                                <tr>
                                  <td>Total</td>
                                  <td><span class="badge bg-aqua"><?php echo number_format($total9[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Masuk</td>
                                  <td><span class="badge bg-default"><?php echo number_format($totalMasuk9[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Proses</td>
                                  <td><span class="badge bg-yellow"><?php echo number_format($totalProses9[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Selesai</td>
                                  <td><span class="badge bg-green"><?php echo number_format($totalTerima9[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Tolak</td>
                                  <td><span class="badge bg-red"><?php echo number_format($totalTolak9[0]['jumlah'], 0, ",", "."); ?></span></td>
                                  </tr-->
                              </table>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                              <a href="<?php echo base_url(); ?>walikota/list_telaah/data/9" class="btn btn-primary btn-flat btn-block">Lihat Telaah</a>
                            </div>
                          </form>
                        </div>

                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>SPPD SEKWAN</b></h3>
                          </div>
                          <!-- /.box-header -->
                          <!-- form start -->
                          <form role="form">
                            <div class="box-body">
                              <label>TELAAH</label>
                              <table class="table">
                                <tr>
                                  <td>Total</td>
                                  <td><span class="badge bg-aqua"><?php echo number_format($total10[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Masuk</td>
                                  <td><span class="badge bg-default"><?php echo number_format($totalMasuk10[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Proses</td>
                                  <td><span class="badge bg-yellow"><?php echo number_format($totalProses10[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Selesai</td>
                                  <td><span class="badge bg-green"><?php echo number_format($totalTerima10[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Tolak</td>
                                  <td><span class="badge bg-red"><?php echo number_format($totalTolak10[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                              </table>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                              <a href="<?php echo base_url(); ?>telaah/disposisi/data/10" class="btn btn-primary btn-flat btn-block">Lihat Telaah</a>
                            </div>
                          </form>
                        </div>

                        <div class="box box-primary">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>SPPD PUSKESMAS (JKN)</b></h3>
                          </div>
                          <!-- /.box-header -->
                          <!-- form start -->
                          <form role="form">
                            <div class="box-body">
                              <label>TELAAH</label>
                              <table class="table">
                                <tr>
                                  <td>Total</td>
                                  <td><span class="badge bg-aqua"><?php echo number_format($total11[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Masuk</td>
                                  <td><span class="badge bg-default"><?php echo number_format($totalMasuk11[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Proses</td>
                                  <td><span class="badge bg-yellow"><?php echo number_format($totalProses11[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Selesai</td>
                                  <td><span class="badge bg-green"><?php echo number_format($totalTerima11[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                                <tr>
                                  <td>Tolak</td>
                                  <td><span class="badge bg-red"><?php echo number_format($totalTolak11[0]['jumlah'], 0, ",", "."); ?></span></td>
                                </tr>
                              </table>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                              <a href="<?php echo base_url(); ?>telaah/disposisi/data/11" class="btn btn-primary btn-flat btn-block">Lihat Telaah</a>
                            </div>
                          </form>
                        </div>

                      </div>

                      <!-- right column -->
                      <div class="col-md-9">
                        <!-- general form elements disabled -->
                        <div class="box box-warning">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>PERSEBARAN SPPD</b> </h3>
                          </div>
                          <!-- /.box-header -->
                          <div class="box-body">
                            <div id="map"></div>
                          </div>
                          <!-- /.box-body -->
                        </div>
                        <!-- /.box -->

                        <div class="box box-warning">
                          <!-- /.box-header -->
                          <div class="box-body">

                            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                              <!-- Indicators -->
                              <ol class="carousel-indicators">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                <li data-target="#myCarousel" data-slide-to="1"></li>
                                <li data-target="#myCarousel" data-slide-to="2"></li>
                                <li data-target="#myCarousel" data-slide-to="3"></li>
                              </ol>

                              <!-- Wrapper for slides -->
                              <div class="carousel-inner">
                                <div class="item active">
                                  <div id="container1" style="min-width: 310px; max-width: 900px; height: 800px; margin: 0 auto"></div>
                                </div>

                                <div class="item">
                                  <div id="container2" style="min-width: 310px; max-width: 800px; height: 600px; margin: 0 auto"></div>
                                </div>

                                <div class="item">
                                  <div id="container3" style="min-width: 310px; max-width: 800px; height: 600px; margin: 0 auto"></div>
                                </div>

                                <div class="item">
                                  <div id="container4" style="min-width: 310px; max-width: 800px; height: 600px; margin: 0 auto"></div>
                                </div>
                              </div>

                              <!-- Left and right controls -->
                              <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                                <span class="sr-only">Previous</span>
                              </a>
                              <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                                <span class="sr-only">Next</span>
                              </a>
                            </div>

                          </div>
                          <!-- /.box-body -->
                        </div>
                        <!-- /.box -->

                        <div class="box box-warning">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>DALAM PERJALANAN</b> </h3>
                          </div>
                          <!-- /.box-header -->
                          <div class="box-body">
                            <p>Jumlah Kepala OPD Yang Sedang Melakukan Perjalanan = <b><?php echo count($dataMap); ?></b> Dari <b><?php echo count($skpd); ?></b> OPD</p>
                            <div class="table-responsive box-body">
                              <table class="table table-bordered table-striped table-hover">
                                <tr class='info'>
                                  <th style="width: 5px">#</th>
                                  <th style="width: 40px">Pelaksana / OPD</th>
                                  <th style="width: 250px">Perihal (Maksud Perjalanan Dinas)</th>
                                  <th style="width: 10px">Lokasi</th>
                                  <th style="width: 10px">Berangkat</th>
                                  <th style="width: 10px">Kembali</th>
                                </tr>
                                <?php
                                    $no = 1;
                                    foreach ($dataMap as $d) {
                                ?>
                                  <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $d->pegawai_nama . " <br> <b>" . $d->skpd_nama . "</b>" ?></td>
                                    <td><?php echo $d->telaah_perihal ?></td>
                                    <td><?php echo $d->provinsi ?></td>
                                    <td><?php echo $d->telaah_tanggalberangkat ?></td>
                                    <td><?php echo $d->telaah_tanggalkembali ?></td>
                                  </tr>
                                <?php
                                      $no++;
                                    }
                                ?>
                              </table>
                            </div>
                          </div>
                          <!-- /.box-body -->
                        </div>

                        <div class="box box-warning">
                          <div class="box-header with-border">
                            <h3 class="box-title"><b>PERMOHONAN SPPD</b> </h3>
                          </div>
                          <!-- /.box-header -->
                          <div class="box-body">
                            <div class="table-responsive box-body">
                              <table class="table table-bordered table-striped table-hover">
                                <tr class='info'>
                                  <th style="width: 5px">#</th>
                                  <th style="width: 40px">Pelaksana / OPD</th>
                                  <th style="width: 250px">Perihal (Maksud Perjalanan Dinas)</th>
                                  <th style="width: 10px">Lokasi</th>
                                  <th style="width: 10px">Berangkat</th>
                                  <th style="width: 10px">Kembali</th>
                                </tr>
                                <?php
                                    $no = 1;
                                    foreach ($dataTelaah as $d) {
                                ?>
                                  <tr>
                                    <td><?php echo $no; ?></td>
                                    <td>
                                      <?php
                                      if ($d->telaah_kategori == 3) {
                                        echo $d->anggotadprd_name . " <br> <b>SEKRETARIAT DPRD</b>";
                                      } else {
                                        echo $d->pegawai_nama . " <br> <b>" . $d->skpd_nama . "</b>";
                                      } ?>
                                    </td>
                                    <td><?php echo $d->telaah_perihal ?></td>
                                    <td><?php echo $d->provinsi ?></td>
                                    <td><?php echo $d->telaah_tanggalberangkat ?></td>
                                    <td><?php echo $d->telaah_tanggalkembali ?></td>
                                  </tr>
                                <?php
                                      $no++;
                                    }
                                ?>
                              </table>
                            </div>
                          </div>
                          <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                      </div>
                      <!--/.col (right) -->

                    <?php } ?>
  </section>
  <!-- /.content -->
</div> <!-- /.content-wrapper -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#walikota').on('click', function() {
      var checkStatus = this.checked ? '0' : '1';
      $.post("<?php echo base_url(); ?>beranda/update_posisi_walikota", {
          "status": checkStatus
        },
        function(data) {
          $('#resultWalikota').html(data);
        });
    });
  });
</script>
<!-- /.content-wrapper -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#kaopd').on('click', function() {
      var checkStatus = this.checked ? '0' : '1';
      $.post("<?php echo base_url(); ?>beranda/update_posisi_kaopd", {
          "status": checkStatus
        },
        function(data) {
          $('#resultKaopd').html(data);
        });
    });
  });
</script> <!-- /.content-wrapper -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#kadprd').on('click', function() {
      var checkStatus = this.checked ? '0' : '1';
      $.post("<?php echo base_url(); ?>beranda/update_posisi_kadprd", {
        "status": checkStatus
      }, function(data) {
        $('#resultKadprd').html(data);
      });
    });
  });
</script> <!-- /.content-wrapper -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#sekda').on('click', function() {
      var checkStatus = this.checked ? '0' : '1';
      $.post("<?php echo base_url(); ?>beranda/update_posisi_sekda", {
        "status": checkStatus
      }, function(data) {
        $('#resultSekda').html(data);
      });
    });
  });
</script>

<?php if (isset($grafik_opd)) { ?>
  <?php for ($i = 1; $i <= 4; $i++) { ?>
    <script>
      Highcharts.chart('container<?php echo $i; ?>', {
        chart: {
          type: 'bar'
        },
        title: {
          text: '<?php
                  if ($i == 1) {
                    echo "JUMLAH PERJALANAN OPD";
                  } else if ($i == 2) {
                    echo "JUMLAH PERJALANAN PUSKESMAS";
                  } else if ($i == 3) {
                    echo "JUMLAH PERJALANAN KECAMATAN";
                  } else if ($i == 4) {
                    echo "JUMLAH PERJALANAN KELURAHAN";
                  }
                  ?>
          '
        },
        xAxis: {
          categories: [
            <?php
            if ($i == 1) {
              foreach ($grafik_opd as $v) {
                echo "'" . $v->skpd_nama . "',";
              }
            } else if ($i == 2) {
              foreach ($grafik_puskesmas as $v) {
                echo "'" . $v->skpd_nama . "',";
              }
            } else if ($i == 3) {
              foreach ($grafik_kecamatan as $v) {
                echo "'" . $v->skpd_nama . "',";
              }
            } else if ($i == 4) {
              foreach ($grafik_kelurahan as $v) {
                echo "'" . $v->skpd_nama . "',";
              }
            }
            ?>
          ],
          title: {
            text: null
          }
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Jumlah Perjalanan',
            align: 'high'
          },
          labels: {
            overflow: 'justify'
          }
        },
        tooltip: {
          valueSuffix: ' Jumlah Perjalanan'
        },
        plotOptions: {
          bar: {
            dataLabels: {
              enabled: true
            }
          }
        },
        legend: {
          layout: 'vertical',
          align: 'right',
          verticalAlign: 'top',
          x: -40,
          y: 300,
          floating: true,
          borderWidth: 1,
          backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
          shadow: true
        },
        credits: {
          enabled: false
        },
        series: [{
          name: 'Jumlah Perjalanan Luar Daerah',
          data: [
            <?php
            if ($i == 1) {
              foreach ($grafik_opd as $v) {
                $grafik_pdld = $this->m_walikota->grafik_pdld($v->skpd_id);
                echo (isset($grafik_pdld[0]['total']) ? $grafik_pdld[0]['total'] : 0) . ",";
              }
            } else if ($i == 2) {
              foreach ($grafik_puskesmas as $v) {
                $grafik_pdld = $this->m_walikota->grafik_pdld($v->skpd_id);
                echo (isset($grafik_pdld[0]['total']) ? $grafik_pdld[0]['total'] : 0) . ",";
              }
            } else if ($i == 3) {
              foreach ($grafik_kecamatan as $v) {
                $grafik_pdld = $this->m_walikota->grafik_pdld($v->skpd_id);
                echo (isset($grafik_pdld[0]['total']) ? $grafik_pdld[0]['total'] : 0) . ",";
              }
            } else if ($i == 4) {
              foreach ($grafik_kelurahan as $v) {
                $grafik_pdld = $this->m_walikota->grafik_pdld($v->skpd_id);
                echo (isset($grafik_pdld[0]['total']) ? $grafik_pdld[0]['total'] : 0) . ",";
              }
            }
            ?>
          ],
          color: '#00a65a',
        }, {
          name: 'Jumlah Perjalanan Dalam Daerah',
          data: [
            <?php
            if ($i == 1) {
              foreach ($grafik_opd as $v) {
                $grafik_pddd = $this->m_walikota->grafik_pddd($v->skpd_id);
                echo (isset($grafik_pddd[0]['total']) ? $grafik_pddd[0]['total'] : 0) . ",";
              }
            } else if ($i == 2) {
              foreach ($grafik_puskesmas as $v) {
                $grafik_pddd = $this->m_walikota->grafik_pddd($v->skpd_id);
                echo (isset($grafik_pddd[0]['total']) ? $grafik_pddd[0]['total'] : 0) . ",";
              }
            } else if ($i == 3) {
              foreach ($grafik_kecamatan as $v) {
                $grafik_pddd = $this->m_walikota->grafik_pddd($v->skpd_id);
                echo (isset($grafik_pddd[0]['total']) ? $grafik_pddd[0]['total'] : 0) . ",";
              }
            } else if ($i == 4) {
              foreach ($grafik_kelurahan as $v) {
                $grafik_pddd = $this->m_walikota->grafik_pddd($v->skpd_id);
                echo (isset($grafik_pddd[0]['total']) ? $grafik_pddd[0]['total'] : 0) . ",";
              }
            }
            ?>
          ],
          color: '#f39c12',
        }]
      });
    </script>
  <?php } ?>
<?php } ?>
<?php if (isset($dataMap)) { ?>
  <script>
    // Initialize and add the map
    function initMap() {
      var locations = [
        <?php
        foreach ($dataMap as $d) {
          echo "['$d->pegawai_nama','$d->skpd_nama', '$d->latitude', '$d->longitude', '$d->provinsi'],";
        }


        //echo "['','', NULL, NULL, '']";
        ?>
        // ['Walikota','Jawa Tengah', -7.146486, 111.517846],
        // ['BPKAD','Jambi', -2.180481, 102.310901],
        // ['DPRD','Bali', -8.269607, 115.395621]
      ];

      /*Map Dasar Kendari*/
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 5,
        center: new google.maps.LatLng(-4.0294360, 122.4979070),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });


      var infowindow = new google.maps.InfoWindow();
      var marker, i, iconbase, news;


      /*Looping Location*/
      for (i = 0; i < locations.length; i++) {
        /*Load Semua Lokasi Deteksi*/
        marker = new google.maps.Marker({
          position: new google.maps.LatLng(locations[i][2], locations[i][3]),
          icon: iconbase,
          title: 'SPPD ' + locations[i][0],
          map: map
        });

        /**/
        /*marker.setAnimation(google.maps.Animation.BOUNCE);*/
        infowindow.setContent("<b>" + locations[i][0] + "<br>" + locations[i][1] + "<br>Lokasi : " + locations[i][4] + "</b>");
        infowindow.open(map, marker);

        /*Tampilkan Info Ketika Marker di klik*/
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          marker.setAnimation(google.maps.Animation.BOUNCE);
          return function() {

            infowindow.setContent("<b>" + locations[i][0] + "<br>" + locations[i][1] + "<br>Lokasi : " + locations[i][4] + "</b>");
            infowindow.open(map, marker);
          }
        })(marker, i));
      }



    }
  </script>
  <!--Load the API from the specified URL
    * The async attribute allows the browser to render the page while the API loads
    * The key parameter will contain your own API key (which is not needed for this tutorial)
    * The callback parameter executes the initMap() function
    -->
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8aB4MpC1orBp300KQQAiVEnWdpry4OPg&callback=initMap">
  </script>
<?php } ?>