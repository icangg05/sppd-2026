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
              <h3 class="box-title">STATUS LOG</h3>
            </div>
            <div class="box-header with-border">
             <div class="col-md-9">
              <a href="<?php echo base_url();?>setting_root/log_tte" class="btn btn-warning btn-flat">Kembali</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="table-responsive box-body">
         <?php
         $message = $this->session->flashdata('notif');
         if($message){
           echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
         }
         ?>
         <table class="table table-bordered table-striped table-hover">
          <tr class='info'>
            <!--th style="width: 300px">Kode SKPD</th-->
            <th style="width: 200px">Tanggal</th>
            <th style="width: 200px">Jam</th>
            <th style="width: 200px">Pelaksana</th>
            <th style="width: 200px">TTE Status</th>
          </tr>
          <?php foreach($data as $v){	
           ?>
           <tr>
            <td><?php echo $v->date;?></td>
            <td><?php echo $v->time;?></td>
            <td><?php echo $v->pegawai_nama;?></td>
            <td><?php echo $v->action;?></td>
          </tr>
          <?php } ?>
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