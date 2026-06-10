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
              <h3 class="box-title">OPD</h3>
            </div>
            <div class="box-header with-border">
             <?php echo form_open("setting_root/skpd/search");?>
             <div class="col-md-9">
              <a href="<?php echo base_url();?>setting_root/skpd/create_view" class="btn btn-success btn-flat">Tambah Data</a>
              <a href="<?php echo base_url();?>setting_root/skpd" class="btn btn-warning btn-flat">Refresh</a>
            </div>
            <div class="col-md-3">
              <div class="input-group">
                <input type="text" class="form-control" name="data" placeholder="Nama OPD ...">
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
           <table class="table table-bordered table-striped table-hover">
            <tr class='info'>
              <!--th style="width: 300px">Kode SKPD</th-->
              <th style="width: 200px">Nama OPD</th>
              <th style="width: 100px">Aksi</th>
            </tr>
            <?php foreach($skpd as $v){
             $skpd_id = base64_encode($this->encrypt->encode($v->skpd_id, $this->session->userdata('encrypt_key')));	
             ?>
             <tr>
              <!--td><?php// echo $v->skpd_kode;?></td-->
              <td><?php echo $v->skpd_nama;?></td>
              <td><a href="<?php echo base_url();?>setting_root/skpd/update_view?skpd_id=<?php echo $skpd_id?>" class="btn btn-sm btn-flat btn-warning"><i class="fa fa-edit"></i> EDIT</a>
               <a href="<?php echo base_url();?>setting_root/skpd/delete?skpd_id=<?php echo $skpd_id?>" class="btn btn-sm btn-flat btn-danger" onclick="return confirm('Anda Yakin ?');"><i class="fa fa-trash"></i> HAPUS</a>
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