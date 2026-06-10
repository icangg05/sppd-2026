<!-- Left side column. contains the logo and sidebar -->
<style>
	/* Modern Sidebar Styles */
	.main-sidebar {
		box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
	}

	.user-panel {
		padding: 20px;
		border-bottom: 1px solid rgba(255, 255, 255, 0.05);
		margin-bottom: 10px;
	}

	.sidebar-menu>li.header {
		background-color: transparent;
		color: #b8c7ce;
		font-weight: 600;
		text-transform: uppercase;
		padding: 15px 25px 10px;
		font-size: 11px;
		letter-spacing: 1px;
		opacity: 0.7;
	}

	.sidebar-menu>li>a {
		border-radius: 0 30px 30px 0;
		margin-right: 10px;
		padding: 12px 20px;
		transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
		border-left: 3px solid transparent;
	}

	.sidebar-menu>li:hover>a {
		background-color: rgba(255, 255, 255, 0.05);
		color: #fff;
		transform: translateX(3px);
	}

	.sidebar-menu>li.active>a {
		background-color: rgba(255, 255, 255, 0.15);
		color: #fff;
		font-weight: 600;
		border-left-color: #3c8dbc;
		box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
	}

	.sidebar-menu>li>.treeview-menu {
		margin: 5px 10px;
		background: rgba(0, 0, 0, 0.2);
		border-radius: 10px;
		padding: 10px 0;
	}

	.sidebar-menu .treeview-menu>li>a {
		padding: 8px 15px 8px 25px;
		font-size: 13px;
		border-radius: 8px;
		margin: 2px 8px;
		transition: all 0.2s;
		white-space: normal;
		line-height: 1.4;
	}

	.sidebar-menu .treeview-menu>li>a:hover {
		background-color: rgba(255, 255, 255, 0.08);
		color: #3c8dbc;
		padding-left: 30px;
	}

	.sidebar-menu .treeview-menu>li.active>a {
		color: #fff;
		font-weight: 600;
		background-color: #3c8dbc;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	}

	.sidebar-menu li>a>.fa,
	.sidebar-menu li>a>.glyphicon,
	.sidebar-menu li>a>.ion {
		width: 25px;
		font-size: 16px;
		margin-right: 5px;
		text-align: center;
		vertical-align: middle;
		transition: transform 0.3s;
	}

	.sidebar-menu li:hover>a>.fa {
		transform: scale(1.15);
	}
</style>

<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?php echo cek_file('upload/profil/' . $this->ion_auth->user()->row()->photo, 'assets2/dist/img/user.png'); ?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p><?php echo $this->ion_auth->user()->row()->username; ?></p>
				<a href="#"><i class="fa fa-dot-circle text-success"></i> Online</a>
			</div>
		</div>
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>

			<!----------------------- Kasi dan Kasubag --------------------------->
			<?php if ($this->ion_auth->get_users_groups()->row()->id == 1) { ?>
				<li class="<?php if ($this->uri->segment(2) == "") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if (
											$this->uri->segment(4) == "esselon"
											|| $this->uri->segment(4) == "puskesmas"
											|| $this->uri->segment(4) == "kadis"
											|| $this->uri->segment(4) == "dprd"
											|| $this->uri->segment(4) == "staff_dprd"
											|| $this->uri->segment(4) == "sekwan"
											|| $this->uri->segment(4) == "walikota"
											|| $this->uri->segment(4) == "sekda"
											|| $this->uri->segment(4) == "staff_setda"
											|| $this->uri->segment(4) == "camat"
											|| $this->uri->segment(4) == "kapus"
										) {
											echo "active";
										} ?>" class="treeview">
					<a href="#">
						<i class="fa fa-list text-yellow"></i> <span>List Telaah</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<!-- DPRD -->
						<?php if ($this->ion_auth->user()->row()->jenis_skpd == 1) { ?>
							<li class="<?php if ($this->uri->segment(4) == "kadis") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/kadis"><i class="fa fa-dot-circle"></i> Kepala OPD</a></li>
							<li class="<?php if ($this->uri->segment(4) == "esselon") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/esselon"><i class="fa fa-dot-circle"></i> Esselon III, IV & Staf</a></li>

						<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 10) { ?>
							<li class="<?php if ($this->uri->segment(4) == "kadis") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/kadis"><i class="fa fa-dot-circle"></i> Kepala OPD</a></li>
							<li class="<?php if ($this->uri->segment(4) == "esselon") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/esselon"><i class="fa fa-dot-circle"></i> Esselon III, IV & Staf</a></li>
							<li class="<?php if ($this->uri->segment(4) == "puskesmas") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/puskesmas"><i class="fa fa-dot-circle"></i> Puskesmas BOK</a></li>

							<!-- Puskesmas -->
						<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 7) { ?>
							<li class="<?php if ($this->uri->segment(4) == "kapus") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/kapus"><i class="fa fa-dot-circle"></i> Puskesmas (JKN)</a></li>
							<li class="<?php if ($this->uri->segment(4) == "esselon") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/esselon"><i class="fa fa-dot-circle"></i> Puskesmas (BOK)</a></li>

							<!-- DPRD -->
						<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 2) { ?>
							<li class="<?php if ($this->uri->segment(4) == "dprd") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/dprd"><i class="fa fa-dot-circle"></i> DPRD</a></li>
							<li class="<?php if ($this->uri->segment(4) == "staff_dprd") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/staff_dprd"><i class="fa fa-dot-circle"></i> Staff DPRD</a></li>
							<li class="<?php if ($this->uri->segment(4) == "sekwan") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/sekwan"><i class="fa fa-dot-circle"></i> Sekwan</a></li>
							<!-- Sekda -->
						<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 3) { ?>
							<?php
							//cek kalo kasubag rt id 671 
							if ($this->ion_auth->user()->row()->id == 671) {
							?>
								<li class="<?php if ($this->uri->segment(4) == "walikota") {
															echo "active";
														} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/walikota"><i class="fa fa-dot-circle"></i> Walikota</a></li>
							<?php
							}
							?>
							<li class="<?php if ($this->uri->segment(4) == "sekda") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/sekda"><i class="fa fa-dot-circle"></i> Sekda, Asisten & Kabag (SETDA)</a></li>
							<li class="<?php if ($this->uri->segment(4) == "staff_setda") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/staff_setda"><i class="fa fa-dot-circle"></i> Kasubag dan Staf (SETDA)</a></li>

							<!-- Kecamatan -->
						<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 4) { ?>
							<li class="<?php if ($this->uri->segment(4) == "camat") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/camat"><i class="fa fa-dot-circle"></i> Camat</a></li>
						<?php } ?>

					</ul>
				<li class="<?php if ($this->uri->segment(2) == "kalender") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>setting_root/kalender"><i class="fa fa-calendar"></i> Kalender</a></li>

				</li>
				<?php if ($this->ion_auth->user()->row()->jenis_skpd == 4) { ?>
					<li class="<?php if ($this->uri->segment(2) == "list_telaah") {
												echo "active";
											} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/kasubag_camat"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
					<li class="<?php if ($this->uri->segment(2) == "telaah_disetujui") {
												echo "active";
											} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/kasubag_camat"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
					<li class="<?php if ($this->uri->segment(2) == "telaah_ditolak") {
												echo "active";
											} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/kasubag_camat"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>

				<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 5) { ?>
					<li class="<?php if ($this->uri->segment(2) == "list_telaah") {
												echo "active";
											} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/kasubag_lurah"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
					<li class="<?php if ($this->uri->segment(2) == "telaah_disetujui") {
												echo "active";
											} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/kasubag_lurah"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
					<li class="<?php if ($this->uri->segment(2) == "telaah_ditolak") {
												echo "active";
											} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/kasubag_lurah"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>

				<?php } ?>


				<!----------------------- Kabid / Kabag di luar DPRD  dan Sekretariat Daerah--------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 2) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<?php
				//cek kalo kasubag umum & perlengkapan id 638 
				if ($this->ion_auth->user()->row()->id == 638) {
				?>
					<li class="<?php if ($this->uri->segment(2) == "sekda") {
												echo "active";
											} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/walikota2"><i class="fa fa-dot-circle"></i> Walikota</a></li>
				<?php
				}
				?>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/kabid"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/kabid"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/kabid"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>
				<!--li class="<!--?php if($this->uri->segment(3)=="tte"){echo "active";}?>"><a href="<!--?php echo base_url();?>telaah/tte/index/kabid"><i class="fa fa-pencil text-blue"></i> <span>TTE</span></i></a></li-->

				<!----------------------- Sekretaris OPD --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 3) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/sekdis"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/sekdis"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/sekdis"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>
				<li class="header">SETTING</li>
				<li class="<?php if ($this->uri->segment(2) == "verifikasi") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/verifikasi/index/sekdis"><i class="fa fa-file-alt text-green"></i> <span>Laporan Perjalanan Dinas</span></i></a></li>

				<!----------------------- Kepala OPD --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 4 && $this->ion_auth->user()->row()->jenis_skpd != 2) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/kadis"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/kadis"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/kadis"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>
				<li class="header">SETTING</li>
				<li class="<?php if ($this->uri->segment(2) == "verifikasi") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/verifikasi/index/kadis"><i class="fa fa-file-alt text-green"></i> <span>Laporan Perjalanan Dinas</span></i></a></li>


				<!----------------------- Sekwan --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 10 && $this->ion_auth->user()->row()->jenis_skpd == 2) { ?>

				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/sekwan"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/sekwan"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/sekwan"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>
				<li class="header">SETTING</li>
				<li class="<?php if ($this->uri->segment(2) == "verifikasi") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/verifikasi/index/sekwan"><i class="fa fa-file-alt text-green"></i> <span>Laporan Perjalanan Dinas</span></i></a></li>






				<!----------------------- Asisten --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 5) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/asisten"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/asisten"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/asisten"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>
				<!--li class="<!--?php if($this->uri->segment(3)=="tte"){echo "active";}?>"><a href="<!--?php echo base_url();?>telaah/tte/index/asisten"><i class="fa fa-pencil text-blue"></i> <span>TTE</span></i></a></li>
	
	
	
	<!----------------------- Sekda --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 6) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/sekda"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/sekda"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/sekda"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>
				<!-- walikota -->
				<li class="<?php if ($this->uri->segment(2) == "sekda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/walikota2"><i class="fa fa-dot-circle"></i> Walikota</a></li>
				<li class="header">SETTING</li>
				<li class="<?php if ($this->uri->segment(2) == "verifikasi") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/verifikasi/index/sekda"><i class="fa fa-file-alt text-green"></i> <span>Laporan Perjalanan Dinas</span></i></a></li>



				<!----------------------- Pimpinan DPRD --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 7) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/kadprd"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/kadprd"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/kadprd"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>



				<!----------------------- Walikota --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 8) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/walikota"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staffa</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/walikota"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/walikota"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>

				<!-- walikota -->
				<li class="<?php if ($this->uri->segment(2) == "sekda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/walikota2"><i class="fa fa-dot-circle"></i> Walikota</a></li>
				<li class="<?php if ($this->uri->segment(2) == "sekda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/rekap_data"><i class="fa fa-dot-circle"></i> Report</a></li>
				<li class="<?php if ($this->uri->segment(2) == "verifikasi") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/verifikasi/index/walikota"><i class="fa fa-file-alt text-green"></i> <span>Laporan Perjalanan Dinas</span></i></a></li>

				<!----------------------- Inspektorat --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 17) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>

				<!----------------------- Camat --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 11) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/camat"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/camat"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/camat"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>
				<!--li class="<!--?php if($this->uri->segment(3)=="tte"){echo "active";}?>"><a href="<!--?php echo base_url();?>telaah/tte/index/camat"><i class="fa fa-pencil text-blue"></i> <span>TTE</span></i></a></li>
	
	
	
	<!----------------------- Sekcam --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 12) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/sekcam"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/sekcam"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/sekcam"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>



				<!----------------------- Lurah --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 13) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(4) == "lurah") {
											echo "active";
										} ?>" class="treeview">
					<a href="#">
						<i class="fa fa-list text-yellow"></i> <span>List Telaah</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?php if ($this->uri->segment(4) == "lurah") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/lurah"><i class="fa fa-dot-circle"></i> Lurah</a></li>
					</ul>
				</li>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/lurah"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/lurah"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/lurah"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>



				<!----------------------- Bendahara Camat --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 14) { ?>
				<li class="<?php if ($this->uri->segment(4) == "") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(4) == "staff_camat") {
											echo "active";
										} ?>" class="treeview">
					<a href="#">
						<i class="fa fa-list text-yellow"></i> <span>List Telaah</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?php if ($this->uri->segment(4) == "staff_camat") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/staff_camat"><i class="fa fa-dot-circle"></i> Staff Camat</a></li>
					</ul>
				</li>



				<!----------------------- Bendahara Lurah --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 15) { ?>
				<li class="<?php if ($this->uri->segment(4) == "") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(4) == "staff_lurah") {
											echo "active";
										} ?>" class="treeview">
					<a href="#">
						<i class="fa fa-list text-yellow"></i> <span>List Telaah</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?php if ($this->uri->segment(4) == "staff_lurah") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/staff_lurah"><i class="fa fa-dot-circle"></i> Staff Lurah</a></li>
					</ul>
				</li>


				<!----------------------- Kepala Puskesmas --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 16) { ?>
				<li class="<?php if ($this->uri->segment(1) == "beranda") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(3) == "index") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/index/kapus"><i class="fa fa-list text-yellow"></i> <span>Permohonan Telaah Staff</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_disetujui") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_disetujui/kapus"><i class="fa fa-check text-green"></i> <span>Telaah Staff Disetujui</span></i></a></li>
				<li class="<?php if ($this->uri->segment(3) == "telaah_ditolak") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>telaah/disposisi/telaah_ditolak/kapus"><i class="fa fa-times text-red"></i> <span>Telaah Staff Ditolak</span></i></a></li>









				<!----------------------- Administrator Full --------------------------->
			<?php } else if (($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id == 1)
				|| ($this->ion_auth->get_users_groups()->row()->id == 100)
			) { ?>
				<li class="<?php if ($this->uri->segment(2) == "") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if (
											$this->uri->segment(4) == "walikota"
											|| $this->uri->segment(4) == "dprd"
											|| $this->uri->segment(4) == "sekwan"
											|| $this->uri->segment(4) == "staff_dprd"
											|| $this->uri->segment(4) == "sekda"
											|| $this->uri->segment(4) == "staff_setda"
											|| $this->uri->segment(4) == "kadis"
											|| $this->uri->segment(4) == "esselon"
											|| $this->uri->segment(4) == "camat"
											|| $this->uri->segment(4) == "lurah"
											|| $this->uri->segment(4) == "staff_camat"
											|| $this->uri->segment(4) == "staff_lurah"
											|| $this->uri->segment(4) == "kapus"
										) {
											echo "active";
										} ?>" class="treeview">
					<a href="#">
						<i class="fa fa-list text-yellow"></i> <span>List Telaah</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?php if ($this->uri->segment(4) == "walikota") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/walikota"><i class="fa fa-dot-circle"></i> WALIKOTA & WAKIL WALIKOTA</a></li>
						<li class="<?php if ($this->uri->segment(4) == "dprd") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/dprd"><i class="fa fa-dot-circle"></i> ANGGOTA DPRD</a></li>
						<li class="<?php if ($this->uri->segment(4) == "sekwan") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/sekwan"><i class="fa fa-dot-circle"></i> SEKWAN DPRD</a></li>
						<li class="<?php if ($this->uri->segment(4) == "staff_dprd") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/staff_dprd"><i class="fa fa-dot-circle"></i> SEKRETARIAT DPRD</a></li>
						<li class="<?php if ($this->uri->segment(4) == "sekda") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/sekda"><i class="fa fa-dot-circle"></i> SEKDA,ASISTEN&KABAG(SETDA)</a></li>
						<li class="<?php if ($this->uri->segment(4) == "staff_setda") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/staff_setda"><i class="fa fa-dot-circle"></i> KASUBAG dan STAF (SETDA)</a></li>
						<li class="<?php if ($this->uri->segment(4) == "kadis") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/kadis"><i class="fa fa-dot-circle"></i> KEPALA OPD</a></li>
						<li class="<?php if ($this->uri->segment(4) == "esselon") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/esselon"><i class="fa fa-dot-circle"></i> ESSELON III, IV & STAFF</a></li>
						<li class="<?php if ($this->uri->segment(4) == "camat") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/camat"><i class="fa fa-dot-circle"></i> CAMAT</a></li>
						<li class="<?php if ($this->uri->segment(4) == "lurah") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/lurah"><i class="fa fa-dot-circle"></i> LURAH</a></li>
						<li class="<?php if ($this->uri->segment(4) == "staff_camat") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/staff_camat"><i class="fa fa-dot-circle"></i> STAFF CAMAT</a></li>
						<li class="<?php if ($this->uri->segment(4) == "staff_lurah") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/staff_lurah"><i class="fa fa-dot-circle"></i> STAFF LURAH</a></li>
						<li class="<?php if ($this->uri->segment(4) == "kapus") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index_admin/kapus"><i class="fa fa-dot-circle"></i> PUSKESMAS</a></li>

					</ul>
				</li>
				<li class="header">PENGATURAN</li>
				<li class="<?php if (
											$this->uri->segment(2) == "log"
											|| $this->uri->segment(2) == "log_tte"
											|| $this->uri->segment(2) == "user"
											|| $this->uri->segment(2) == "skpd"
											|| $this->uri->segment(2) == "asisten"
											|| $this->uri->segment(2) == "bagian"
											|| $this->uri->segment(2) == "sub_bagian"
											|| $this->uri->segment(2) == "provinsi"
											|| $this->uri->segment(2) == "kabupaten"
											|| $this->uri->segment(2) == "utilitas"
										) {
											echo "active";
										} ?>" class="treeview">
					<a href="#">
						<i class="fa fa-cogs"></i> <span>Setting</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?php if ($this->uri->segment(2) == "log" || $this->uri->segment(2) == "log_tte") {
													echo "active";
												} ?>" class="treeview">
							<a href="#">
								<i class="fa fa-dot-circle"></i> <span>Log</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="<?php if ($this->uri->segment(2) == "log") {
															echo "active";
														} ?>"><a href="<?php echo base_url(); ?>setting_root/log"><i class="fa fa-dot-circle"></i> Log</a></li>
								<li class="<?php if ($this->uri->segment(2) == "log_tte") {
															echo "active";
														} ?>"><a href="<?php echo base_url(); ?>setting_root/log_tte"><i class="fa fa-dot-circle"></i> Log TTE</a></li>
							</ul>
						</li>
						<li class="<?php if ($this->uri->segment(2) == "user") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>setting_root/user"><i class="fa fa-dot-circle"></i> User</a></li>
						<li class="<?php if ($this->uri->segment(2) == "skpd") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>setting_root/skpd"><i class="fa fa-dot-circle"></i> OPD</a></li>
						<li class="<?php if ($this->uri->segment(2) == "asisten" || $this->uri->segment(2) == "bagian" || $this->uri->segment(2) == "sub_bagian") {
													echo "active";
												} ?>" class="treeview">
							<a href="#">
								<i class="fa fa-dot-circle"></i> <span>Sekretariat</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="<?php if ($this->uri->segment(2) == "asisten") {
															echo "active";
														} ?>"><a href="<?php echo base_url(); ?>setting_root/asisten"><i class="fa fa-dot-circle"></i> Asisten</a></li>
								<li class="<?php if ($this->uri->segment(2) == "bagian") {
															echo "active";
														} ?>"><a href="<?php echo base_url(); ?>setting_root/bagian"><i class="fa fa-dot-circle"></i> Bagian</a></li>
								<li class="<?php if ($this->uri->segment(2) == "sub_bagian") {
															echo "active";
														} ?>"><a href="<?php echo base_url(); ?>setting_root/sub_bagian"><i class="fa fa-dot-circle"></i> Sub Bagian</a></li>
							</ul>
						</li>
						<li class="<?php if ($this->uri->segment(2) == "provinsi") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>setting_root/provinsi"><i class="fa fa-dot-circle"></i> Provinsi</a></li>
						<li class="<?php if ($this->uri->segment(2) == "kabupaten") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>setting_root/kabupaten"><i class="fa fa-dot-circle"></i> Kabupaten/Kota</a></li>
						<li class="<?php if ($this->uri->segment(2) == "utilitas") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>setting_root/utilitas"><i class="fa fa-dot-circle"></i> Back Up dan Restore</a></li>
					</ul>
				</li>



				<!----------------------- Administrator Walikota --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id == 177) { ?>
				<li class="<?php if ($this->uri->segment(2) == "") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="<?php if ($this->uri->segment(1) == "user") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>user"><i class="fa fa-dot-circle"></i> User</a></li>
				<li class="<?php if ($this->uri->segment(1) == "pegawai") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>pegawai"><i class="fa fa-dot-circle"></i> Walikota/Wakil Walikota</a></li>
				<li class="<?php if ($this->uri->segment(1) == "tanda_tangan") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>tanda_tangan"><i class="fa fa-dot-circle"></i> Tanda Tangan</a></li>



				<!----------------------- Administrator OPD --------------------------->
			<?php } else if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id != "") { ?>
				<li class="<?php if ($this->uri->segment(2) == "") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>beranda"><i class="fa fa-home text-blue"></i> <span>Beranda</span></a></li>
				<li class="
	<?php if (
					$this->uri->segment(4) == "esselon"
					|| $this->uri->segment(4) == "puskesmas"
					|| $this->uri->segment(4) == "kadis"
					|| $this->uri->segment(4) == "dprd"
					|| $this->uri->segment(4) == "walikota"
					|| $this->uri->segment(4) == "staff_dprd"
					|| $this->uri->segment(4) == "staff_setda"
					|| $this->uri->segment(4) == "sekwan"
					|| $this->uri->segment(4) == "sekda"
					|| $this->uri->segment(4) == "camat"
					|| $this->uri->segment(4) == "staff_camat"
					|| $this->uri->segment(4) == "lurah"
					|| $this->uri->segment(4) == "staff_lurah"
					|| $this->uri->segment(4) == "kapus"
					|| $this->uri->segment(5) == "esselon"
					|| $this->uri->segment(5) == "kadis"
					|| $this->uri->segment(5) == "dprd"
					|| $this->uri->segment(5) == "walikota"
					|| $this->uri->segment(5) == "staff_dprd"
					|| $this->uri->segment(5) == "staff_setda"
					|| $this->uri->segment(5) == "sekwan"
					|| $this->uri->segment(5) == "sekda"
					|| $this->uri->segment(5) == "camat"
					|| $this->uri->segment(5) == "staff_camat"
					|| $this->uri->segment(5) == "lurah"
					|| $this->uri->segment(5) == "staff_lurah"
					|| $this->uri->segment(5) == "kapus"
				) {
					echo "active";
				} ?>" class="treeview">
					<a href="#">
						<i class="fa fa-list text-yellow"></i> <span>List Telaah</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<!-- esselon -->
						<?php if ($this->ion_auth->user()->row()->jenis_skpd == 1) { ?>
							<li class="<?php if ($this->uri->segment(4) == "kadis" || $this->uri->segment(5) == "kadis") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/kadis"><i class="fa fa-dot-circle"></i> Kepala OPD</a></li>
							<li class="<?php if ($this->uri->segment(4) == "esselon" || $this->uri->segment(5) == "esselon") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/esselon"><i class="fa fa-dot-circle"></i> Esselon III, IV & Staf</a></li>

						<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 10) { ?>
							<li class="<?php if ($this->uri->segment(4) == "kadis") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/kadis"><i class="fa fa-dot-circle"></i> Kepala OPD</a></li>
							<li class="<?php if ($this->uri->segment(4) == "esselon") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/esselon"><i class="fa fa-dot-circle"></i> Esselon III, IV & Staf</a></li>
							<li class="<?php if ($this->uri->segment(4) == "puskesmas") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/puskesmas"><i class="fa fa-dot-circle"></i> Puskesmas BOK</a></li>

							<!-- DPRD -->
						<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 2) { ?>
							<li class="<?php if ($this->uri->segment(4) == "dprd" || $this->uri->segment(5) == "dprd") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/dprd"><i class="fa fa-dot-circle"></i> DPRD</a></li>
							<li class="<?php if ($this->uri->segment(4) == "staff_dprd" || $this->uri->segment(5) == "staff_dprd") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/staff_dprd"><i class="fa fa-dot-circle"></i> Staff DPRD</a></li>
							<li class="<?php if ($this->uri->segment(4) == "sekwan" || $this->uri->segment(5) == "sekwan") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/sekwan"><i class="fa fa-dot-circle"></i> Sekwan</a></li>
							<!-- Sekda -->
						<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 3) { ?>
							<?php
							//cek kalo admin sekda id 31 
							if ($this->ion_auth->user()->row()->id == 31) {
							?>
								<li class="<?php if ($this->uri->segment(4) == "walikota" || $this->uri->segment(5) == "walikota") {
															echo "active";
														} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/walikota"><i class="fa fa-dot-circle"></i> Walikota</a></li>
							<?php } ?>
							<li class="<?php if ($this->uri->segment(4) == "sekda" || $this->uri->segment(5) == "sekda") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/sekda"><i class="fa fa-dot-circle"></i> Sekda, Asisten & Kabag (SETDA)</a></li>
							<li class="<?php if ($this->uri->segment(4) == "staff_setda" || $this->uri->segment(5) == "staff_setda") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/staff_setda"><i class="fa fa-dot-circle"></i> Kasubag dan Staf (SETDA)</a></li>
							<!-- Kecamatan -->
						<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 4) { ?>
							<li class="<?php if ($this->uri->segment(4) == "camat" || $this->uri->segment(5) == "camat") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/camat"><i class="fa fa-dot-circle"></i> Camat</a></li>
							<li class="<?php if ($this->uri->segment(4) == "staff_camat" || $this->uri->segment(5) == "staff_camat") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/staff_camat"><i class="fa fa-dot-circle"></i> Staff Camat</a></li>
							<!-- Kelurahan -->
						<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 5) { ?>
							<li class="<?php if ($this->uri->segment(4) == "lurah" || $this->uri->segment(5) == "lurah") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/lurah"><i class="fa fa-dot-circle"></i> Lurah</a></li>
							<li class="<?php if ($this->uri->segment(4) == "staff_lurah" || $this->uri->segment(5) == "staff_lurah") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/staff_lurah"><i class="fa fa-dot-circle"></i> Staff Lurah</a></li>
							<!-- Puskesmas -->
						<?php } else if ($this->ion_auth->user()->row()->jenis_skpd == 7) { ?>
							<li class="<?php if ($this->uri->segment(4) == "kapus" || $this->uri->segment(5) == "kapus") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/kapus"><i class="fa fa-dot-circle"></i> Puskesmas (JKN)</a></li>
							<li class="<?php if ($this->uri->segment(4) == "esselon" || $this->uri->segment(5) == "esselon") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>telaah/list_telaah/index/esselon"><i class="fa fa-dot-circle"></i> Puskesmas (BOK)</a></li>
						<?php } ?>

					</ul>
				</li>
				<li class="<?php if ($this->uri->segment(2) == "kalender") {
											echo "active";
										} ?>"><a href="<?php echo base_url(); ?>setting_root/kalender"><i class="fa fa-calendar"></i> Kalender</a></li>

				<!-- <li class="<?php if ($this->uri->segment(1) == "log") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>log"><i class="fa fa-list "></i> Status Log</a></li> -->
				<li class="header">PENGATURAN</li>
				<li class="<?php if (
											$this->uri->segment(2) == "log"
											|| $this->uri->segment(2) == "log_tte"
											|| $this->uri->segment(2) == "export"
											|| $this->uri->segment(2) == "user"
											|| $this->uri->segment(2) == "pegawai"
											|| $this->uri->segment(2) == "skpd"
											|| $this->uri->segment(2) == "anggaran"
										) {
											echo "active";
										} ?>" class="treeview">
					<a href="#">
						<i class="fa fa-cogs"></i> <span>Setting</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<?php $skpd_id = base64_encode($this->encrypt->encode($this->ion_auth->user()->row()->skpd_id, $this->session->userdata('encrypt_key')));	?>
						<li class="<?php if ($this->uri->segment(2) == "log" || $this->uri->segment(2) == "log_tte") {
													echo "active";
												} ?>" class="treeview">
							<a href="#">
								<i class="fa fa-dot-circle"></i> <span>Log</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="<?php if ($this->uri->segment(2) == "log") {
															echo "active";
														} ?>"><a href="<?php echo base_url(); ?>setting_root/log"><i class="fa fa-dot-circle"></i> Log</a></li>
								<li class="<?php if ($this->uri->segment(2) == "log_tte") {
															echo "active";
														} ?>"><a href="<?php echo base_url(); ?>setting_root/log_tte"><i class="fa fa-dot-circle"></i> Log TTE</a></li>
							</ul>
						</li>
						<li class="<?php if ($this->uri->segment(2) == "export") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>setting_root/export"><i class="fa fa-dot-circle"></i> Export</a></li>
						<li class="<?php if ($this->uri->segment(2) == "user") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>setting_root/user"><i class="fa fa-dot-circle"></i> User</a></li>
						<?php if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id == 3) { ?>
							<li class="<?php if ($this->uri->segment(2) == "walikota") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>setting_admin/walikota"><i class="fa fa-dot-circle"></i> Walikota</a></li>
						<?php } ?>
						<li class="<?php if ($this->uri->segment(2) == "pegawai") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>setting_admin/pegawai"><i class="fa fa-dot-circle"></i> Pegawai</a></li>
						<li class="<?php if ($this->uri->segment(2) == "anggaran") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>setting_admin/anggaran"><i class="fa fa-dot-circle"></i> DPA</a></li>
						<li class="<?php if ($this->uri->segment(2) == "skpd") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>setting_root/skpd/update_view?skpd_id=<?php echo $skpd_id ?>"><i class="fa fa-dot-circle"></i> OPD</a></li>
						<li class="<?php if ($this->uri->segment(2) == "dokumen") {
													echo "active";
												} ?>"><a href="<?php echo base_url(); ?>setting_admin/dokumen"><i class="fa fa-dot-circle"></i> Cek Dokumen</a></li>
						<?php if ($this->ion_auth->user()->row()->jenis_skpd == 2) { ?>
							<!-- DPRD -->
							<li class="<?php if ($this->uri->segment(2) == "anggota") {
														echo "active";
													} ?>"><a href="<?php echo base_url(); ?>setting_admin/anggota"><i class="fa fa-dot-circle"></i> Anggota DPRD</a></li>
						<?php } ?>

					</ul>
				</li>
			<?php } ?>
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>