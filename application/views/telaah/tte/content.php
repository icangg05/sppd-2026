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
              <h3 class="box-title">Tanda Tangan Elektronik</h3>
            </div>
			<div class="box-header with-border">
				 <?php echo form_open("telaah/tte/search/".$this->uri->segment(4));?>
				 <div class="col-md-9">
				  <a href="<?php echo base_url();?>telaah/tte/index/<?php echo $this->uri->segment(4)?>" class="btn btn-warning btn-flat">Refresh</a>
				</div>	
			  <div class="col-md-3">
				<div class="input-group">
				  <input type="text" class="form-control" name="data" placeholder="Cari Nama Pelaksana">
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
           echo $message;
         }
         ?>
         <table class="table table-bordered table-striped table-hover">
          <tr class='info'>
			<th style="width: 5px">No</th>
			<th style="width: 40px">Tanggal Pengajuan</th>
			<th style="width: 200px">Pelaksana Perjalanan Dinas</th>
			<th style="width: 300px">Jabatan</th>
			<th style="width: 300px">Perihal (Maksud Perjalanan Dinas)</th>
			<th style="width: 300px">Penanda Tangan</th>
			<th style="width: 100px">Status</th>
			<th style="width: 20px">Aksi</th>
          </tr>
          <?php 
		  $number = $number + 1;
		  foreach($data as $v){
           $telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));	
           ?>
			<tr>
				<td><?php echo $number++?></td>
				<td><?php echo $v->telaah_waktuinput; ?></td>
				<?php if ($v->telaah_kategori==3){ ?>
						<td><?php echo $v->pegawai_nama_dprd; ?></td>
						<td><?php echo $v->jabatan_dprd; ?></td>
				<?php } else if ($v->telaah_kategori==8){  ?>
						<td><?php echo $v->pegawai_nama_walikota; ?></td>
						<td><?php echo $v->jabatan_walikota; ?></td>
				<?php } else { ?>
						<td><?php echo $v->pegawai_nama_opd; ?></td>
						<td><?php echo $v->jabatan_opd; ?></td>
				<?php } ?>
				
				<td><?php echo $v->telaah_perihal?></td>
				<td><?php $penandatangan = $this->m_pegawai->get($v->penandatangan);
							echo $penandatangan[0]['pegawai_nama'];
					?></td>
				<td>
					<?php if($v->status_tte==1){
						echo "<span class='label label-success'>Sudah TTE</span>";
					} else {
						echo "<span class='label label-default'>Belum TTE</span>";
					}
					?>
				</td>
				<td><button class="btn btn-sm btn-block btn-info" data-toggle="modal" data-target="#myModal<?php echo $v->telaah_id?>" ><i class="fa fa-pencil"></i> TTE </button></td>
			</tr>
			
			<!-- Modal TTE -->
			<div class="modal fade" id="myModal<?php echo $v->telaah_id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-scrollable" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h4 class="modal-title" id="exampleModalScrollableTitle">Masukkan Passphrase </h4>
				  </div>
				  <div class="modal-body">
					<?php echo form_open_multipart('telaah/laporan/qr/generate_tte');?> 
					<div class="table-responsive box-body">
						<input type="hidden" name="tte_id" value="<?php echo $v->tte_id?>">
						<input type="hidden" name="telaah_id" value="<?php echo $v->telaah_id?>">
						<input type="hidden" name="telaah_kategori" value="<?php echo $v->telaah_kategori?>">
						<input type="hidden" name="pegawai_id" value="<?php echo $penandatangan[0]['pegawai_id']?>">
						<input type="hidden" name="tte" value="<?php echo $this->uri->segment(2)?>">
						<input type="hidden" name="posisi" value="<?php echo $this->uri->segment(4)?>">
							<input type="password" name="passphrase" id="inputPassword6" class="form-control mx-sm-3" aria-describedby="passwordHelpInline" >
						<small id="passwordHelpInline" class="text-muted">
						  Must be 6-8 characters long.
						</small>
					</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" name="acc" class="btn btn-success" value="Acc dan Lanjutkan">Ok</button>
				  </div>
				</div>
				
					<?php echo form_close(); ?>
			  </div>
			</div>
			
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