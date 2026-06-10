 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">TAMBAH TANGGAL SPPD</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
			<?php echo form_open_multipart('telaah/laporan/spd/update'); ?>
              <div class="box-body">
			 <?php if(validation_errors()){?>
				  <div class="alert alert-danger text-center">
					<?php echo validation_errors(); ?>
				  </div>
			 <?php }?>
				 <table class="table table-bordered table-striped">
					<tr class="info">
					  <th class="col-md-3" colspan="2"><center>DATA TANGGAL SPPD</center></th>
					</tr>
					<?php 
						$telaah_id2 = base64_encode($this->encrypt->encode($entry[0]['telaah_id'], $this->session->userdata('encrypt_key')));	
					?>
					<input type="hidden" class="form-control" name="telaah_id" value="<?php echo $entry[0]['telaah_id'];?>">
					<input type="hidden" class="form-control" name="posisi" value="<?php echo $posisi;?>">  
					<tr>
					  <th class="col-md-3">Tanggal SPPD</th>
					  <td><input type="text" class="form-control" name="telaah_tanggalspd" value="<?php echo $entry[0]['telaah_tanggalspd'];?>" id="datepicker"></td>
					</tr>
					<tr>
					  <th class="col-md-3">Tanda Tangan</th>
					  <td>
						<select class="form-control select2" name="telaah_ttdspd">
						<?php if ($this->uri->segment(5)== "esselon" || $this->uri->segment(5)== "kadis"){ ?>
							<option value="">- Pilih-</option>
							<option value="<?php echo $kepala_opd[0]['pegawai_id']?>" <?php if($entry[0]['telaah_ttdspd']==$kepala_opd[0]['pegawai_id']){ echo "selected";}?> >Kepala OPD (<?php echo $kepala_opd[0]['pegawai_nama'];?>)</option>
							<option value="<?php echo $sekretaris_opd[0]['pegawai_id']?>" <?php if($entry[0]['telaah_ttdspd']==$sekretaris_opd[0]['pegawai_id']){ echo "selected";}?> >Sekretaris OPD (<?php echo $sekretaris_opd[0]['pegawai_nama'];?>)</option>
							<?php foreach ($kabid as $v){ ?>
								<option value="<?php echo $v->pegawai_id?>"  
								<?php if($entry[0]['telaah_ttdspd']==$v->pegawai_id)
								{ echo "selected";}?> > Kabid (<?php echo $v->pegawai_nama;?>)</option>
							<?php } ?>
							
						<?php } else if ($this->uri->segment(5)== "walikota" 
										|| $this->uri->segment(5)== "sekda" 
										|| $this->uri->segment(5)== "staff_setda"){ ?>
							<option value="">- Pilih-</option>
							<option value="<?php echo $sekda[0]['pegawai_id']?>" <?php if($entry[0]['telaah_ttdspd']==$sekda[0]['pegawai_id']){ echo "selected";}?>>Sekretaris Daerah (<?php echo $sekda[0]['pegawai_nama'];?>)</option>
							<option value="<?php echo $asisten1[0]['pegawai_id']?>" <?php if($entry[0]['telaah_ttdspd']==$asisten1[0]['pegawai_id']){ echo "selected";}?>>Asisten I (<?php echo $asisten1[0]['pegawai_nama'];?>)</option>
							<option value="<?php echo $asisten2[0]['pegawai_id']?>" <?php if($entry[0]['telaah_ttdspd']==$asisten2[0]['pegawai_id']){ echo "selected";}?>>Asisten II (<?php echo $asisten2[0]['pegawai_nama'];?>)</option>
							<option value="<?php echo $asisten3[0]['pegawai_id']?>" <?php if($entry[0]['telaah_ttdspd']==$asisten3[0]['pegawai_id']){ echo "selected";}?>>Asisten III (<?php echo $asisten3[0]['pegawai_nama'];?>)</option>
							
						<?php } else if ($this->uri->segment(5)== "dprd" 
										|| $this->uri->segment(5)== "sekwan"
										|| $this->uri->segment(5)== "staff_dprd"){ ?>
							<option value="">- Pilih-</option>
							<option value="<?php echo $sekwan[0]['pegawai_id']?>" <?php if($entry[0]['telaah_ttdspd']==$sekwan[0]['pegawai_id']){ echo "selected";}?>>Sekwan (<?php echo $sekwan[0]['pegawai_nama'];?>)</option>
							<?php foreach ($kabid as $v){ ?>
								<option value="<?php echo $v->pegawai_id?>"  
								<?php if($entry[0]['telaah_ttdspd']==$v->pegawai_id)
								{ echo "selected";}?> > Kabid (<?php echo $v->pegawai_nama;?>)</option>
							<?php } ?>
							
						<?php } else if ($this->uri->segment(5)== "camat" || $this->uri->segment(5)== "staff_camat"){ ?>
							<option value="">- Pilih-</option>
							<option value="<?php echo $camat[0]['pegawai_id']?>" <?php if($entry[0]['telaah_ttdspd']==$camat[0]['pegawai_id']){ echo "selected";}?> >Camat (<?php echo $camat[0]['pegawai_nama'];?>)</option>
							<option value="<?php echo $sekcam[0]['pegawai_id']?>" <?php if($entry[0]['telaah_ttdspd']==$sekcam[0]['pegawai_id']){ echo "selected";}?> >Sekcam (<?php echo $sekcam[0]['pegawai_nama'];?>)</option>
							
						<?php } else if ($this->uri->segment(5)== "lurah" || $this->uri->segment(5)== "staff_lurah"){ ?>
							<option value="">- Pilih-</option>
							<option value="<?php echo $lurah[0]['pegawai_id']?>" <?php if($entry[0]['telaah_ttdspd']==$lurah[0]['pegawai_id']){ echo "selected";}?> >Lurah (<?php echo $lurah[0]['pegawai_nama'];?>)</option>
							
						<?php } else if ($this->uri->segment(5)== "kapus"){ ?>
							<option value="">- Pilih-</option>
							<option value="<?php echo $kapus[0]['pegawai_id']?>" <?php if($entry[0]['telaah_ttdspd']==$kapus[0]['pegawai_id']){ echo "selected";}?> >Kepala Puskesmas (<?php echo $kapus[0]['pegawai_nama'];?>)</option>
							
						<?php } ?>
						</select>
					  </td>
					</tr>
				</table>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
				 <div class="col-md-6">					
					<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
					<button type="reset" class="btn btn-warning btn-sm" ><i class="fa fa-repeat"></i> Reset</button>
					<a href="<?php echo base_url();?>telaah/laporan/spd/index/<?php echo $this->uri->segment(5);?>?telaah_id=<?php echo $telaah_id2;?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
				 </div>
              </div>
            <?php echo form_close(); ?>
          </div>
          <!-- /.box -->
		  
        </div>
        <!--/.col (left) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
		var ckeditor = CKEDITOR.replace('ckeditor');
  </script>
  <script type="text/javascript">
	   $(function() {
		 $('#datepicker').datepicker({
			 format:'yyyy-mm-dd',
			 autoclose: true
		});
	   });
   </script>
<script>
	$(function () {
		//Initialize Select2 Elements
		$(".select2").select2();
	});
</script>