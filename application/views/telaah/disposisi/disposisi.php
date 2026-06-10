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
 						<h3 class="box-title"><?php 

 							$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
 							$telaah_kategori = $this->encrypt->decode(base64_decode($this->input->get('telaah_kategori')), $this->session->userdata('encrypt_key'));

 							if($this->ion_auth->get_users_groups()->row()->id == 1){
 								echo "KONFIRMASI DISPOSISI KASUBID / KASUBAG";
 							} else if($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id != 2 && $this->ion_auth->user()->row()->skpd_id != 3){
 								echo "KONFIRMASI DISPOSISI KABID / IRBAN / KABAG";
 							} else if($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 2){
 								echo "KONFIRMASI DISPOSISI KABAG DPR";
 							} else if($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 3){
 								echo "KONFIRMASI DISPOSISI KABAG SEKRETARIAT DAERAH";
 							} else if($this->ion_auth->get_users_groups()->row()->id == 3){
 								echo "KONFIRMASI DISPOSISI SEKRETARIS OPD";
 							}  else if($this->ion_auth->get_users_groups()->row()->id == 4 && $this->ion_auth->user()->row()->skpd_id != 2){
 								echo "KONFIRMASI DISPOSISI KEPALA OPD";
 							}  else if($this->ion_auth->get_users_groups()->row()->id == 10 && $this->ion_auth->user()->row()->skpd_id == 2){
 								echo "KONFIRMASI DISPOSISI SEKWAN";
 							}  else if($this->ion_auth->get_users_groups()->row()->id == 5){
 								echo "KONFIRMASI DISPOSISI ASISTEN";
 							}  else if($this->ion_auth->get_users_groups()->row()->id == 6){
 								echo "KONFIRMASI DISPOSISI SEKDA";
 							}  else if($this->ion_auth->get_users_groups()->row()->id == 7){
 								echo "KONFIRMASI DISPOSISI PIMPINAN DPRD";
 							}  else if($this->ion_auth->get_users_groups()->row()->id == 8){
 								echo "KONFIRMASI DISPOSISI WALIKOTA / WAKIL WALIKOTA";
 							}  else if($this->ion_auth->get_users_groups()->row()->id == 11){
 								echo "KONFIRMASI DISPOSISI CAMAT";
 							}  else if($this->ion_auth->get_users_groups()->row()->id == 12){
 								echo "KONFIRMASI DISPOSISI SEKCAM";
 							}  else if($this->ion_auth->get_users_groups()->row()->id == 13){
 								echo "KONFIRMASI DISPOSISI LURAH";
 							}  else if($this->ion_auth->get_users_groups()->row()->id == 14){
 								echo "KONFIRMASI DISPOSISI BENDAHARA CAMAT";
 							}   else if($this->ion_auth->get_users_groups()->row()->id == 15){
 								echo "KONFIRMASI DISPOSISI BENDAHARA LURAH";
 							}  else if($this->ion_auth->get_users_groups()->row()->id == 16){
 								echo "KONFIRMASI DISPOSISI KEPALA PUSKESMAS";
 							}  
 							?></h3>
 						</div>
 						<!-- /.box-header -->
 						<!-- form start -->
 						<?php echo form_open_multipart('telaah/disposisi/disposisi_update'); 			
 						?>
 						<div class="table-responsive box-body">
 							<table class="table table-bordered ">
 								<table class="table table-bordered ">
								<?php
								 $message = $this->session->flashdata('notif');
								 if($message){
								   echo '<p class="alert alert-danger text-center"><b>'.$message .'</b></p>';
								 }
								 ?>
 									<tr>
 										<th class="col-md-3">Disposisi</th>
 										<td>
 											<input type="hidden" name="telaah_id" value="<?php echo $telaah_id?>">
 											<input type="hidden" name="telaah_kategori" value="<?php echo $telaah_kategori?>">
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
 										</td>
 									</tr>
 								</table>
 							</table>
 						</div>
 						<!-- /.box-body -->

 						<div class="box-footer">
 							<div class="col-md-12">
								<!-- Button trigger modal --> 
								<?php if($this->uri->segment(4)=="kadis" && $telaah_kategori==1){ ?>
								<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModalScrollable">
								  Acc dan Lanjutkan
								</button>
								<?php } else { ?>
								<input type="submit" name="acc" class="btn btn-success btn-sm" value="Acc dan Lanjutkan" onclick="return confirm('Apakah anda yakin untuk menyetujui perjalanan ini ?');"/>
								<?php } ?>
 								<input type="submit" name="acc" class="btn btn-danger btn-sm" value="Tidak Acc" onclick="return confirm('Apakah anda yakin untuk menolak perjalanan ini ?');">
 								<input type="submit" name="acc" class="btn btn-primary btn-sm" value="Perbaikan" onclick="return confirm('Apakah anda yakin untuk memperbaiki perjalanan ini  ?');">
								<a href="<?php echo base_url();?>telaah/disposisi/detail/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>?telaah_id=<?php echo $this->input->get('telaah_id')?>" class="btn btn-warning btn-sm"><i class="fa fa-close"></i> Kembali</a>
 							</div>
 						</div>
 						<?php echo form_close(); ?>
						
						
						<!-- Modal -->
						<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
						  <div class="modal-dialog modal-dialog-scrollable" role="document">
							<div class="modal-content">
							  <div class="modal-header">
								<h5 class="modal-title" id="exampleModalScrollableTitle"></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								  <span aria-hidden="true">&times;</span>
								</button>
							  </div>
							  <div class="modal-body">
								<?php echo form_open_multipart('telaah/laporan/qr/generate_tte');?> 
								<div class="table-responsive box-body">
									<table class="table table-bordered ">
										<table class="table table-bordered ">
											<tr>
												<th class="col-md-3">Masukkan Password</th>
												<td><input type="hidden" name="telaah_id" value="<?php echo $telaah_id?>">
												<input type="hidden" name="telaah_disetujui" value="<?php echo $this->input->get('telaah_disetujui')?>">
												<input type="hidden" name="telaah_ditolak" value="<?php echo $this->input->get('telaah_ditolak')?>">
												<input type="hidden" name="posisi" value="<?php echo $this->uri->segment(4)?>">
												<input type="hidden" name="jabatan" value="<?php echo $this->uri->segment(4);?>">
												<input type="hidden" name="posisi" value="<?php echo $this->uri->segment(5);?>">
												<input type="hidden" name="kategori" value="<?php echo $this->input->get('telaah_kategori');?>">
												<small id="passwordHelpInline" class="text-muted"><input type="password" name="passphrase" id="inputPassword6" class="form-control mx-sm-3" aria-describedby="passwordHelpInline" >
												<small id="passwordHelpInline" class="text-muted">
												  Must be 6-8 characters long.
												</small></td>
											</tr>
										</table>
									</table>
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
 		$(function() {
 			$('#datepicker2').datepicker({
 				format:'yyyy-mm-dd',
 				autoclose: true
 			});
 		});
 	</script>
