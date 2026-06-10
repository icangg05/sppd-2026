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
             <?php echo form_open("setting_root/log_tte/search");?>
             <div class="col-md-9">
              <a href="<?php echo base_url();?>setting_root/log_tte" class="btn btn-warning btn-flat">Refresh</a>
            </div>
          <div class="col-md-3">
            <div class="input-group">
              <input type="text" class="form-control" name="data" placeholder="Nama Pelaksana">
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
            <th style="width: 5px">No</th>
			<th style="width: 40px">Tanggal Pengajuan</th>
			<th style="width: 150px">Perihal (Maksud Perjalanan Dinas)</th>
			<th style="width: 200px">Pelaksana</th>
			<?php 
				if($this->ion_auth->user()->row()->id==1){
					echo '<th style="width: 200px">OPD</th>';
				} 
			?>
			<th style="width: 40px">Aksi</th>
          </tr>
          <?php $number=$number+1;
		  foreach($data as $v){	
           ?>
           <tr>
            <td><?php echo $number++;?></td>
            <td>
				<?php 
					$date = substr($v->telaah_waktuinput, 0, 10);
					$time = substr($v->telaah_waktuinput, 11, 19);
					$telaah_waktuinput =  $this->waktu->date_indo($date);
					echo $telaah_waktuinput.' '.$time;
				?>
			</td>
            <td><?php echo $v->telaah_perihal?></td>
			<td><?php echo $v->pegawai_nama?></td>
			<?php 
				if($this->ion_auth->user()->row()->id==1){
					echo "<td>".$v->skpd_nama."</td>";
				} 
			?>
			<td> <a href="<?php echo base_url();?>setting_root/log_tte/list_log_tte/<?php echo $v->telaah_id?>" class="btn btn-primary btn-sm btn-flat">Lihat Log TTE</a></td>
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