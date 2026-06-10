  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <!-- ./col -->
        <div class="col-lg-12 col-xs-12">
        <?php 
          if($this->uri->segment(4)==2){
            $label="Kepala OPD";
          }elseif($this->uri->segment(4)==4){
            $label="SEKDA, ASISTEN & KABAG di SEKRETARIAT";
          }elseif($this->uri->segment(4)==5){
            $label="CAMAT & LURAH";
          }elseif($this->uri->segment(4)==8){
            $label="WALIKOTA & WAKIL";
          }
        ?>
        <h2>Rekap Data SPPD Kendari : <?php echo $label?></h2>
        
          
          
        </div>
        <div class="col-lg-12 col-xs-12">
          <div class="box box-success">
           <div class="box-header with-border">
            <h3 class="box-title">REKAP DATA SPPD</h3>
            <br><a href="<?php echo site_url('telaah/list_telaah/rekap_data')?>" class="btn btn-success">Kembali</a>
          </div>
          <div class="box-header with-border">
            <?php echo form_open("telaah/list_telaah/search");?>
            <div class="col-md-9">
            </div>
            <?php echo form_close();?>
          </div>
          <!-- /.box-header -->
          <div class="table-responsive box-body" style="overflow-x:auto;">
            <table class="table table-bordered table-striped table-hover">
                <tr class='info'>
                  <th style="width: 5px">No</th>
                  <th style="width: 40px">Tanggal Pengajuan</th>
                  <th style="width: 200px">Pelaksana Perjalanan Dinas</th>
                  <th style="width: 300px">Jabatan</th>
                  <th style="width: 300px">Perihal (Maksud Perjalanan Dinas)</th>
                  <th style="width: 300px">SKPD</th>
                  <th style="width: 100px">Status</th>
                </tr>
                <?php 
                $no=1;
                foreach($rekap as $v){
                  
                  ?>
                  <tr>
                    
                    <td><?php echo $no?></td>
                    <td><?php echo $v->telaah_waktuinput; ?></td>
                    <td><?php echo $v->pegawai_nama; ?></td>
                    <td><?php echo $v->pegawai_namajabatan; ?></td>
                    <td><?php echo $v->telaah_perihal?></td>
                    <td><?php echo $v->skpd_nama?></td>
                    <td>
                      <?php
                      if($v->telaah_status==0) {
                        echo '<span class="label label-default">Belum Diterima</span></td>';
                      } else if($v->telaah_status==1) {
                        echo '<span class="label label-warning">Dalam Proses</span></td>';
                      } else if($v->telaah_status==2) {
                        echo '<span class="label label-success">Selesai</span></td>';
                      } else if($v->telaah_status==3){
                        echo '<span class="label label-danger">Tidak Diterima</span>';
                      } else if($v->telaah_status==5){
                        echo '<span class="label label-primary">Perbaikan</span>';
                      }
                      ?>

                    </td>
                    
                  </tr>
                  <?php 
                  $no++;
                } 
                ?>
              </table>

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