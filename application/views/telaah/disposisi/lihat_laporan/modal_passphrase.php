<div class="modal fade" id="passphrase" role="dialog">
  <div class="modal-dialog">
	<!--?php echo form_open_multipart('telaah/disposisi/disposisi_update');?--> 
	<?php echo form_open_multipart('telaah/laporan/qr_new/generate_tte');?> 
	<div class="modal-content">
	  <div class="modal-header">
		<h3 class="modal-title">Penandatanganan Dokumen Perjalanan</h3>
	  </div>
	  <div class="modal-body">
		<input type="hidden" name="telaah_id" value="<?php echo $telaah_id ?>">
		<input type="hidden" name="telaah_kategori" value="<?php echo $telaah_kategori?>"><br>
		<input type="hidden" name="telaah_disetujui" value="<?php echo $this->input->get('telaah_disetujui')?>">
		<input type="hidden" name="telaah_ditolak" value="<?php echo $this->input->get('telaah_ditolak')?>">
		<input type="hidden" name="posisi" value="<?php echo $this->uri->segment(4)?>">
		<input type="hidden" name="skpd_id" value="<?php echo $entry[0]['skpd']?>">
		<input type="hidden" name="jenis_skpd" value="<?php echo $entry[0]['jenis_skpd']?>">
		Masukkan Password
		<input type="password" name="passphrase" id="inputPassword6" class="form-control mx-sm-3" aria-describedby="passwordHelpInline">
		<small id="passwordHelpInline" class="text-muted">
		  Must be 6-8 characters long.
		</small>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<button type="submit" name="acc" class="btn btn-success" value="Acc dan Lanjutkan">Tanda Tangani</button>
	
	<?php echo form_close(); ?>
		
		
		
		<!--button class="btn btn-warning" data-toggle="collapse" data-target="#demo">Kuasakan <span class="fa fa-caret-down"></span></button><br><br-->
		<?php echo form_open_multipart('telaah/disposisi/kuasakan');?> 
		  <div id="demo" class="collapse">
				<input type="hidden" name="telaah_id" value="<?php echo $telaah_id ?>">
				<input type="hidden" name="telaah_kategori" value="<?php echo $telaah_kategori?>">
				<input type="hidden" name="telaah_disetujui" value="<?php echo $this->input->get('telaah_disetujui')?>">
				<input type="hidden" name="telaah_ditolak" value="<?php echo $this->input->get('telaah_ditolak')?>">
				<input type="hidden" name="posisi" value="<?php echo $this->uri->segment(4)?>">
				<select class="form-control select2" name="tanda_tangan_spd" required>
				<?php if ($telaah_kategori== "esselon" || $this->uri->segment(4)== "kadis"){ ?>
					<option value="">- Pilih-</option>
					<option value="3,<?php echo $sekretaris_opd[0]['pegawai_id']?>">Sekretaris OPD</option>
					<?php foreach ($kabid as $v){ ?>
						<option value="2,<?php echo $v->pegawai_id?>">Kabid (<?php echo $v->pegawai_nama;?>)</option>
					<?php } ?>
				<?php } else if ($this->uri->segment(4)== "walikota" || $this->uri->segment(4)== "sekda" || $this->uri->segment(4)== "staff_sekda"){ ?>
					<option value="">- Pilih-</option>
					<?php if($this->uri->segment(4)!= "staff_sekda" ) { ?>
						<option value="6,<?php echo $sekda[0]['pegawai_id']?>">Sekretaris Daerah</option>
					<?php } ?>
					<option value="5,<?php echo $asisten1[0]['pegawai_id']?>">Asisten I</option>
					<option value="5,<?php echo $asisten2[0]['pegawai_id']?>">Asisten II</option>
					<option value="5,<?php echo $asisten3[0]['pegawai_id']?>">Asisten III</option>
				<?php } else if ($this->uri->segment(4)== "dprd" || $this->uri->segment(4)== "sekwan" || $this->uri->segment(4)== "staff_dprd"){ ?>
					<option value="">- Pilih-</option>
					<?php if($this->uri->segment(5)!= 6 ) { ?>
					<option value="10,<?php echo $sekwan[0]['pegawai_id']?>">Sekwan</option>
					<?php } ?>
					<?php foreach ($kabid as $v){ ?>
						<option value="2,<?php echo $v->pegawai_id?>">Kabid (<?php echo $v->pegawai_nama;?>)</option>
					<?php } ?>
				<?php } else if ($this->uri->segment(4)== "camat" || $this->uri->segment(4)== "staff_camat"){ ?>
					<option value="">- Pilih-</option>
					<option value="11,<?php echo $camat[0]['pegawai_id']?>">Camat</option>
					<option value="12,<?php echo $sekcam[0]['pegawai_id']?>">Sekcam</option>
				<?php } else if ($this->uri->segment(4)== "lurah"){ ?>
					<option value="">- Pilih-</option>
					<option value="11,<?php echo $camat[0]['pegawai_id']?>">Camat</option>
				<?php } else if ($this->uri->segment(4)== "staff_lurah"){ ?>
					<option value="">- Pilih-</option>
					<option value="13,<?php echo $lurah[0]['pegawai_id']?>">Lurah</option>
					<?php foreach ($kabid as $v){ ?>
						<option value="2,<?php echo $v->pegawai_id?>">Kabid (<?php echo $v->pegawai_nama;?>)</option>
					<?php } ?>
				<?php } else if ($this->uri->segment(4)== "kapus"){ ?>
					<option value="">- Pilih-</option>
					<option value="16,<?php echo $kapus[0]['pegawai_id']?>">Kepala Puskesmas</option>
				<?php } ?>
				</select><br>
				<button type="submit" class="btn btn-success">Kirim</button>
			</div>
			
		<?php echo form_close(); ?>
	  </div>
	</div>
  </div>
</div>
<!-- Modal -->

<script>
function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>
