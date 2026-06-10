
<!-- Modal -->
<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Detail Perjalanan Dinas</h4>
	</div>
	<div class="modal-body">
	<?php if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 1) || 
			($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 1) || 
			($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 10) ||
			($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 10)) { ?>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>KEPALA OPD</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah_kaopd[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses_kaopd[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima_kaopd[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak_kaopd[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>ESSELON III, IV & STAF</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah_esselon[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses_esselon[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima_esselon[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak_esselon[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	<?php } else if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 2) || 
					($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 2)) { ?>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>DPRD</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah_dprd[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses_dprd[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima_dprd[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak_dprd[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>STAFF DPRD</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah_staff_dprd[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses_staff_dprd[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima_staff_dprd[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak_staff_dprd[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>SEKWAN</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah_sekwan[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses_sekwan[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima_sekwan[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak_sekwan[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	   </table>
	<?php } else if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 3) || 
					($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 3)) { ?>
	  
		<?php	//cek kalo kasubag rt id 671 
			if($this->ion_auth->user()->row()->id == 671) {
		?>
		<table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>WALIKOTA</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah_walikota[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses_walikota[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima_walikota[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak_walikota[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br>
	  <?php } ?>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>SEKDA, ASISTEN & KABAG</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>KASUBAG DAN STAF</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah_staff_sekda[0]['total_list_telaah_staff'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses_staff_sekda[0]['total_list_telaah_diproses_staff'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima_staff_sekda[0]['total_list_telaah_diterima_staff'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak_staff_sekda[0]['total_list_telaah_ditolak_staff'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br> 
	<?php 
	## KECAMATAN
	} else if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 4)
				||($this->ion_auth->get_users_groups()->row()->id == 14 && $this->ion_auth->user()->row()->jenis_skpd == 4)
			||($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 4)) { ?>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>CAMAT</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>STAF CAMAT</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah_staff_camat[0]['total_list_telaah_staff'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses_staff_camat[0]['total_list_telaah_diproses_staff'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima_staff_camat[0]['total_list_telaah_diterima_staff'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak_staff_camat[0]['total_list_telaah_ditolak_staff'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br> 		
	<?php 
	## KELURAHAN
	} else if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 5)
				|| ($this->ion_auth->get_users_groups()->row()->id == 13 && $this->ion_auth->user()->row()->jenis_skpd == 5)
				|| ($this->ion_auth->get_users_groups()->row()->id == 15 && $this->ion_auth->user()->row()->jenis_skpd == 5)) { ?>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>LURAH</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>STAF LURAH</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah_staff_lurah[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses_staff_lurah[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima_staff_lurah[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak_staff_lurah[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br> 	
	<?php 
	## PUSKESMAS
	} else if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd == 7)
				||($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 7)) { ?>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>PUSKESMAS (JKN)</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br>
	  <table class="table table-striped table-bordered">
		<tr class="info">
		  <td colspan=2 style="text-align:center"><b>PUSKESMAS (BOK)</b></td>
		</tr>
		<tr>
		  <td class="col-md-6">Total</td>
		  <td><span class="badge bg-aqua"><?php echo number_format($total_list_telaah_bok[0]['total_list_telaah'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Proses</td>
		  <td><span class="badge bg-yellow"><?php echo number_format($total_list_telaah_diproses_bok[0]['total_list_telaah_diproses'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Selesai</td>
		  <td><span class="badge bg-green"><?php echo number_format($total_list_telaah_diterima_bok[0]['total_list_telaah_diterima'], 0, ",", "."); ?></span></td>
		</tr>
		<tr>
		  <td class="col-md-6">Tolak</td>
		  <td><span class="badge bg-red"><?php echo number_format($total_list_telaah_ditolak_bok[0]['total_list_telaah_ditolak'], 0, ",", "."); ?></span></td>
		</tr>
	  </table>
	  <br> 
	<?php } ?>
	</div>
	<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
	</div>
  </div>

  </div>
</div>