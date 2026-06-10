<div class="modal fade" id="terima">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h3 class="modal-title">Terima Perjalanan</h3>
	  </div>
	  <div class="modal-body">
		<?php echo form_open_multipart('telaah/disposisi/disposisi_update');?> 
		<input type="hidden" name="telaah_id" value="<?php echo $telaah_id ?>">
		<input type="hidden" name="telaah_kategori" value="<?php echo $telaah_kategori?>">
		<input type="hidden" name="skpd_id" value="<?php echo $entry[0]['skpd']?>">
		<input type="hidden" name="jenis_skpd" value="<?php echo $entry[0]['jenis_skpd']?>">
		<input type="hidden" name="telaah_disetujui" value="<?php echo $this->input->get('telaah_disetujui')?>">
		<input type="hidden" name="telaah_ditolak" value="<?php echo $this->input->get('telaah_ditolak')?>">
		<input type="hidden" name="posisi" value="<?php echo $this->uri->segment(4)?>">
		<?php 
			if($this->ion_auth->get_users_groups()->row()->id == 1){
				if($this->ion_auth->user()->row()->jenis_skpd == 4 || $this->ion_auth->user()->row()->jenis_skpd == 5){
					echo "<textarea class='form-control' name='timeline_lurah_disposisi' required>";
					echo $timeline[0]['timeline_lurah_disposisi'];
				} else {
					echo "<textarea class='form-control' name='timeline_kasubid_disposisi' required>";
					echo $timeline[0]['timeline_kasubid_disposisi'];
				}
			} else if($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id != 2 && $this->ion_auth->user()->row()->skpd_id != 3){
				echo "<textarea class='form-control' name='timeline_kabid_disposisi' required>";
				echo $timeline[0]['timeline_kabid_disposisi'];
			} else if($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 2){
				//Pimpinan DPRD (3) dan Staff DPRD (6)
				if($telaah_kategori==3){
					echo "<textarea class='form-control' name='timeline_kasubid_disposisi' required>";
					echo $timeline[0]['timeline_kasubid_disposisi'];
				} else {
					echo "<textarea class='form-control' name='timeline_kabag_disposisi' required>";
					echo $timeline[0]['timeline_kabag_disposisi'];
				}
			} else if($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 3){
				echo "<textarea class='form-control' name='timeline_kabag_disposisi' required>";
				echo $timeline[0]['timeline_kabag_disposisi'];
			} else if($this->ion_auth->get_users_groups()->row()->id == 3){
				echo "<textarea class='form-control' name='timeline_sekdis_disposisi'required>";
				echo $timeline[0]['timeline_sekdis_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 4 && $this->ion_auth->user()->row()->skpd_id != 2){
				echo "<input type='hidden' class='form-control' name='telaah_domainperjalanan' value='".$entry[0]['telaah_domainperjalanan']."'>";
				echo "<textarea class='form-control' name='timeline_kadis_disposisi' required>";
				echo $timeline[0]['timeline_kadis_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 4 && $this->ion_auth->user()->row()->skpd_id == 2){
				echo "<textarea class='form-control' name='timeline_sekwan_disposisi' required>";
				echo $timeline[0]['timeline_sekwan_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 5){
				echo "<textarea class='form-control' name='timeline_asisten_disposisi' required>";
				echo $timeline[0]['timeline_asisten_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 6){
				echo "<textarea class='form-control' name='timeline_sekda_disposisi' required>";
				echo $timeline[0]['timeline_sekda_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 7){
				echo "<textarea class='form-control' name='timeline_kadprd_disposisi' required>";
				echo $timeline[0]['timeline_kadprd_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 8){
				echo "<textarea class='form-control' name='timeline_walikota_disposisi' required>";
				echo $timeline[0]['timeline_walikota_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 10 && $this->ion_auth->user()->row()->skpd_id == 2){
				echo "<textarea class='form-control' name='timeline_sekwan_disposisi' required>";
				echo $timeline[0]['timeline_sekwan_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 11){
				echo "<textarea class='form-control' name='timeline_camat_disposisi' required>";
				echo $timeline[0]['timeline_camat_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 12){
				echo "<textarea class='form-control' name='timeline_sekcam_disposisi' required>";
				echo $timeline[0]['timeline_sekcam_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 13){
				echo "<textarea class='form-control' name='timeline_lurah_disposisi' required>";
				echo $timeline[0]['timeline_lurah_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 14){
				echo "<textarea class='form-control' name='timeline_lurah_disposisi' required>";
				echo $timeline[0]['timeline_lurah_disposisi'];
			}  else if($this->ion_auth->get_users_groups()->row()->id == 16){
				echo "<textarea class='form-control' name='timeline_kapus_disposisi' required>";
				echo $timeline[0]['timeline_kapus_disposisi'];
			}  
			?></textarea>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<button type="submit" name="acc" class="btn btn-success" value="Acc dan Lanjutkan">Terima Perjalanan</button>
	  </div>
	</div>
	
		<?php echo form_close(); ?>
  </div>
</div>