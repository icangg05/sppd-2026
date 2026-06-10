  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	<!-- Content Header (Page header) -->
  	<!-- Main content -->
  	<section class="content">
  		<!-- Small boxes (Stat box) -->
  		<div class="row">
  			<!-- ./col -->
        <div class="col-lg-12 col-xs-12">
        <h2>Rekap Data SPPD Kendari</h2>
        
          <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
              <a href="<?php echo site_url('walikota/list_telaah/detail_rekap_data/2')?>">
                <div class="info-box">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-newspaper-o"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Kepala OPD</span>
                    <span class="info-box-number"><?php echo $rekap[0]->timeline2?> SPPD</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
              </a>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-6 col-sm-6 col-xs-12">
              <a href="<?php echo site_url('walikota/list_telaah/detail_rekap_data/4')?>">
                <div class="info-box">
                  <span class="info-box-icon bg-red"><i class="fa fa-newspaper-o"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">SEKDA, ASISTEN & KABAG di SEKRETARIAT</span>
                    <span class="info-box-number"><?php echo $rekap[0]->timeline4?> SPPD</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </a>
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-6 col-sm-6 col-xs-12">
              <a href="<?php echo site_url('walikota/list_telaah/detail_rekap_data/5')?>">
                <div class="info-box">
                  <span class="info-box-icon bg-green"><i class="fa fa-newspaper-o"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Camat & Lurah</span>
                    <span class="info-box-number"><?php echo $rekap[0]->timeline5?> SPPD</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </a>
            </div>
            <!-- /.col -->
            <div class="col-md-6 col-sm-6 col-xs-12">
              <a href="<?php echo site_url('walikota/list_telaah/detail_rekap_data/8')?>">
                <div class="info-box">
                  <span class="info-box-icon bg-yellow"><i class="fa fa-newspaper-o"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Walikota & Wakil Walikota</span>
                    <span class="info-box-number"><?php echo $rekap[0]->timeline8?> SPPD</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </a>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <div class="col-lg-12 col-xs-12">
          <div class="box box-success">
           <div class="box-header with-border">
            <h3 class="box-title">REKAP DATA SPPD</h3>
          </div>
          <div class="box-header with-border">
            
            <div class="col-md-9">
            </div>
            <?php echo form_close();?>
          </div>
          <!-- /.box-header -->
          <div class="table-responsive box-body" style="overflow-x:auto;">
            
            Filter SPPD by SKPD :<br>
            <?php echo form_open("walikota/list_telaah/getDataBySKPD");?>

            <select class="form-control select2" name="skpd" style="width: 80%">
              <option value="">- Pilih -</option>
              <?php
              foreach ($skpd as $v) {
                echo '<option value="'.$v->skpd_id.'-'.$v->skpd_nama.'">'.$v->skpd_nama.'</option>';
              }
              ?>
            </select>

            <input type="submit" class="btn btn-success" value="Cek SPPD" style="margin-top: 3px">

            <?php echo form_close();?>
          </div>
          <!-- /.box-body -->
          <div class="box-footer clearfix">

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

  <script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
  });
</script>