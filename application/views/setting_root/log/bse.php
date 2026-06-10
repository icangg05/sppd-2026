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
              <h3 class="box-title">STATUS LOG PROSES TTE</h3>
            </div>
            <div class="box-header with-border">
             <?php echo form_open("setting_root/log/search");?>
             <div class="col-md-7">
              <a href="<?php echo base_url();?>setting_root/log" class="btn btn-warning btn-flat">Kembali</a>
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
         <table class="table table-bordered table-striped table-hover">
          <tr class='info'>
            <!--th style="width: 300px">Kode SKPD</th-->
            <th style="width: 10px">Waktu</th>
            <th style="width: 500px">Pesan Error</th>
          </tr>
          <tr >
            <td>2019-06-21 14:00:25</td>
            <td>Passphrase anda salah !!!</td>
          </tr>
          <tr >
            <td>2019-06-21 14:05:40</td>
            <td>Passphrase anda salah !!!</td>
          </tr>
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