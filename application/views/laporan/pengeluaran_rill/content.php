
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
              <h3 class="box-title">LAPORAN PENGELUARAN RILL</h3>
            </div>
			<div class="box-header with-border">
				<div class="col-md-7">
					<a href="<?php echo base_url();?>telaah/list_telaah/laporan/<?php echo $this->uri->segment(5)?>?telaah_id=<?php echo $this->input->get('telaah_id');?>" class="btn btn-danger btn-sm "><i class="fa fa-close"></i> Kembali</a>
					<?php if (($this->ion_auth->user()->row()->id!=1) && ($this->ion_auth->get_users_groups()->row()->id != 100) && ($this->ion_auth->get_users_groups()->row()->id != 9)){ ?>
						<?php if($data[0]['telaah_ttdpptk']==0) { ?>
							<a href="<?php echo base_url();?>telaah/laporan/pptk_pengeluaran_rill/create_view/<?php echo $this->uri->segment(5);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>" class="btn btn-success btn-sm "> Pilih PPTK</a>
						<?php } else { ?>
							<a href="<?php echo base_url();?>telaah/laporan/pptk_pengeluaran_rill/update_view/<?php echo $this->uri->segment(5);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>" class="btn btn-sm btn-warning"> Edit PPTK</a>	
						<?php } ?>
					<?php } ?>
				</div>
			</div>
            <!-- /.box-header -->
            <div class="box-body">
			<?php
				$message = $this->session->flashdata('notif');
						if($message){
							echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
						}
			?>
			<div class="table-responsive box-body">	
			<div class="col-lg-7 col-xs-12">
			</div>
			
			<div class="col-lg-5 col-xs-12">
				<table class="table">
					<tr>
						<td style="width: 10px">Penanda Tangan PPTK</td>
						<td style="width: 20px"> : <b><?php echo $tanda_tangan_pptk[0]['pegawai_nama']; ?></b></td>
					</tr>
				</table>
			</div>		
              <table class="table table-bordered table-hover">
                <tr class='info'>
					<td style="width: 5px" colspan="3">Pelaksana : <?php echo $pelaksana[0]['pegawai_nama'] ?></td>
					<th style="width: 40px" colspan="1">
						 <?php if (($this->ion_auth->user()->row()->id!=1) && ($this->ion_auth->get_users_groups()->row()->id != 100) && ($this->ion_auth->get_users_groups()->row()->id != 9)){ ?>
							<a href="<?php echo base_url();?>telaah/laporan/pengeluaran_rill/create_view/<?php echo $this->uri->segment(5);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>" class="btn btn-success btn-sm ">Tambah Data</a>
						 <?php } ?>
						 <?php if($pptk[0]['telaah_ttdpptk']==0) { ?>
							<a href="#" data-toggle="modal" data-target="#1" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Data</a>
						 <?php } else { ?>
							<a href="<?php echo base_url();?>telaah/laporan/laporan/laporan_pengeluaran_rill/<?php echo $this->uri->segment(5);?>/1?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Data</a>
						 <?php } ?>
					</th>
                </tr>
				<tr>
					<th style="width: 1px">No</th>
					<th style="width: 200px">Uraian</th>
					<th style="width: 100px">Tarif</th>
					<th style="width: 10px">#</th>
				</tr>
				<?php 
					$no=1;
					foreach($rincian_pelaksana as $v){
					$pengeluaran_rill_id = base64_encode($this->encrypt->encode($v->pengeluaran_rill_id, $this->session->userdata('encrypt_key')));	
				?>
               <tr>
					<td><?php echo $no;?></td>
					<td><?php echo $v->uraian?></td>
					<td>Rp. <?php echo number_format($v->tarif,0,",",".")?></td>
					<td><a href="<?php echo base_url();?>telaah/laporan/pengeluaran_rill/update_view/<?php echo $this->uri->segment(5);?>?pengeluaran_rill_id=<?php echo $pengeluaran_rill_id;?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</a>
						<a href="<?php echo base_url();?>telaah/laporan/pengeluaran_rill/delete/<?php echo $this->uri->segment(5);?>?pengeluaran_rill_id=<?php echo $pengeluaran_rill_id;?>&&telaah_id=<?php echo $v->telaah_id;?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin ?');"><i class="fa fa-trash"></i> Hapus</a>
					</td>
                </tr>
				<?php
				$no++;				
				} 
				?>
			  </table>
            </div>
			<?php
				for($i=0;$i<$jumlah_pengikut;$i++){
			?>
			<div class="table-responsive box-body">
              <table class="table table-bordered table-hover">
                <tr class='info'>
					<td style="width: 5px" colspan="3">Pengikut : <?php echo $pengikut[$i]['pegawai_nama'] ?></td>
					<th style="width: 40px" colspan="1">
						 <?php if (($this->ion_auth->user()->row()->id!=1) && ($this->ion_auth->get_users_groups()->row()->id != 100) && ($this->ion_auth->get_users_groups()->row()->id != 9)){ ?>
							<a href="<?php echo base_url();?>telaah/laporan/pengeluaran_rill/create_view/<?php echo $this->uri->segment(5);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pengikut[$i]['pegawai_id'] ?>" class="btn btn-success btn-sm ">Tambah Data</a>
						 <?php } ?>
						 <?php if($pptk[0]['telaah_ttdpptk']==0) { ?>
							<a href="#" data-toggle="modal" data-target="#1" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Data</a>
						 <?php } else { ?>
							<a href="<?php echo base_url();?>telaah/laporan/laporan/laporan_pengeluaran_rill/<?php echo $this->uri->segment(5);?>/2?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&pengikut_id=<?php echo $pengikut[$i]['pegawai_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Data</a>
						 <?php } ?>
					</th>
                </tr>
				<tr>
					<th style="width: 1px">No</th>
					<th style="width: 200px">Uraian</th>
					<th style="width: 100px">Tarif</th>
					<th style="width: 10px">#</th>
				</tr>
				<?php 
					$no=1;
					if($telaah_kategori==3){
						$rincian_pengikut = $this->m_pengeluaran_rill->get_rincian_dprd($pengikut[$i]['telaah_id'],$pengikut[$i]['pegawai_id']);
					} else {
						$rincian_pengikut = $this->m_pengeluaran_rill->get_rincian($pengikut[$i]['telaah_id'],$pengikut[$i]['pegawai_id']);
					}
					
					foreach($rincian_pengikut as $v){
					$pengeluaran_rill_id = base64_encode($this->encrypt->encode($v->pengeluaran_rill_id, $this->session->userdata('encrypt_key')));	
					$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));	
				?>
                <tr>
					<td><?php echo $no;?></td>
					<td><?php echo $v->uraian?></td>
					<td>Rp. <?php echo number_format($v->tarif,0,",",".")?></td>
					<td>
					<a href="<?php echo base_url();?>telaah/laporan/pengeluaran_rill/update_view/<?php echo $this->uri->segment(5);?>?pengeluaran_rill_id=<?php echo $pengeluaran_rill_id;?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</a>
						<a href="<?php echo base_url();?>telaah/laporan/pengeluaran_rill/delete/<?php echo $this->uri->segment(5);?>?pengeluaran_rill_id=<?php echo $pengeluaran_rill_id;?>&&telaah_id=<?php echo $v->telaah_id;?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin ?');"><i class="fa fa-trash"></i> Hapus</a>
					</td>
                </tr>
				<?php
				$no++;				
				} 
				?>
			  </table>
            </div>
			<?php
				}
			?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
			<p><b><i>*Catatan : Untuk mencetak Laporan Pengeluaran Rill Silahkan Input Pejabat Pelaksana Teknis Kegiatan (PPTK)</i></b></p>
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
  
  <div class="modal fade" id="1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <b>Pejabat Pelaksana Teknis Kegiatan (PPTK)</b> Belum dipilih
        </div>
      </div>
    </div>
  </div>