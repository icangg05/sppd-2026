
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
              <h3 class="box-title">LAPORAN RINCIAN BIAYA PERJALANAN DINAS</h3>
            </div>
			<div class="box-header with-border">
			<div class="col-md-7">
				  <a href="<?php echo base_url();?>telaah/list_telaah/laporan/<?php echo $this->uri->segment(5)?>?telaah_id=<?php echo $this->input->get('telaah_id');?>" class="btn btn-danger btn-sm "><i class="fa fa-close"></i> Kembali</a>
				</div>
			</div>
            <!-- /.box-header -->
            <div class="box-body">
			<?php
				$message = $this->session->flashdata('notif');
						if($message){
							echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
						}
				$error = $this->session->flashdata('error');
				if($error){
					echo $error;
				}
			?>
			
			<div class="table-responsive box-body">
			<p><b><i style="color: #fd0000;">
			<?php  if($data[0]['telaah_ttdpptk']==0){
				echo "PPTK Belum ada (Pilih Di Laporan Pengeluaran Rill)<br>";
			} ?>
			</i></b></p>
              <table class="table table-bordered table-hover">
                <tr class='info'>
					<td style="width: 5px" colspan="6">Pelaksana : <?php echo $pelaksana[0]['pegawai_nama'] ?></td>
					<th style="width: 40px" colspan="2"><?php if (($this->ion_auth->user()->row()->id!=1) && ($this->ion_auth->get_users_groups()->row()->id != 100) && ($this->ion_auth->get_users_groups()->row()->id != 9)){ ?>
							<a href="<?php echo base_url();?>telaah/laporan/rincian/create_view/<?php echo $this->uri->segment(5);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>" class="btn btn-success btn-sm ">Tambah Data</a>
						 <?php } ?>
						 <?php if($data[0]['telaah_ttdpptk']==0) { ?>
							<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Data</a>
						 <?php } else { ?>
							<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_rbpd/<?php echo $this->uri->segment(5);?>/1?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Data</a>
						 <?php } ?>
						 
					</th>
                </tr>
				<tr>
					<th style="width: 1px">No</th>
					<th style="width: 100px">Kategori Biaya</th>
					<th style="width: 100px">Keterangan</th>
					<th style="width: 100px">Item</th>
					<th style="width: 100px">Tarif</th>
					<th style="width: 100px">Total</th>
					<th style="width: 100px">Foto</th>
					<th style="width: 50px">#</th>
				</tr>
				<?php 
					$no=1;
					foreach($rincian_pelaksana as $v){
					$rincian_biaya_id = base64_encode($this->encrypt->encode($v->rincian_biaya_id, $this->session->userdata('encrypt_key')));	
				?>
               <tr>
					<td><?php echo $no;?></td>
					<td><?php if($v->kategori_biaya==1){
							echo "Penginapan";
						} else if($v->kategori_biaya==2){
							echo "Sewa Kendaraan";
						} else if($v->kategori_biaya==3){
							echo "Transport";
						} else if($v->kategori_biaya==4){
							echo "Biaya Lainnya";
						}  ?>
					</td>
					<td><?php echo $v->keterangan?></td>
					<td><?php echo $v->item?></td>
					<td>Rp. <?php echo number_format($v->tarif,0,",",".")?></td>
					<td>Rp. <?php echo number_format($v->item * $v->tarif,0,",",".")?></td>
					<td><img src="<?php echo base_url();?>upload/bukti/<?php echo $v->foto ;?>" width="150" height="100"></td>
					<td><a href="<?php echo base_url();?>telaah/laporan/rincian/update_view/<?php echo $this->uri->segment(5);?>?rincian_biaya_id=<?php echo $rincian_biaya_id;?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</a>
						<a href="<?php echo base_url();?>telaah/laporan/rincian/delete/<?php echo $this->uri->segment(5);?>?rincian_biaya_id=<?php echo $rincian_biaya_id;?>&&telaah_id=<?php echo $v->telaah_id;?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin ?');"><i class="fa fa-trash"></i> Hapus</a>
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
					<td style="width: 5px" colspan="6">Pengikut : <?php echo $pengikut[$i]['pegawai_nama'] ?></td>
					<th style="width: 40px" colspan="2"><?php if (($this->ion_auth->user()->row()->id!=1) && ($this->ion_auth->get_users_groups()->row()->id != 100) && ($this->ion_auth->get_users_groups()->row()->id != 9)){ ?>
							<a href="<?php echo base_url();?>telaah/laporan/rincian/create_view/<?php echo $this->uri->segment(5);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pengikut[$i]['pegawai_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-success btn-sm ">Tambah Data</a>
						 <?php } ?>
						 <?php if($data[0]['telaah_ttdpptk']==0) { ?>
							<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Data</a>
						 <?php } else { ?>
							<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_rbpd/<?php echo $this->uri->segment(5);?>/2?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&pengikut_id=<?php echo $pengikut[$i]['pegawai_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Data</a>
						 <?php } ?>
						 
					</th>
                </tr>
				<tr>
					<th style="width: 1px">No</th>
					<th style="width: 100px">Kategori Biaya</th>
					<th style="width: 100px">Keterangan</th>
					<th style="width: 100px">Item</th>
					<th style="width: 100px">Tarif</th>
					<th style="width: 100px">Total</th>
					<th style="width: 100px">Foto</th>
					<th style="width: 50px">#</th>
				</tr>
				<?php 
					$no=1;
					$rincian_pengikut = $this->m_rincian->get_rincian($pengikut[$i]['telaah_id'],$pengikut[$i]['pegawai_id']);
					foreach($rincian_pengikut as $v){
					$rincian_biaya_id = base64_encode($this->encrypt->encode($v->rincian_biaya_id, $this->session->userdata('encrypt_key')));	
					$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));	
				?>
                <tr>
					<td><?php echo $no;?></td>
					<td><?php if($v->kategori_biaya==1){
							echo "Penginapan";
						} else if($v->kategori_biaya==2){
							echo "Sewa Kendaraan";
						} else if($v->kategori_biaya==3){
							echo "Transport";
						} else if($v->kategori_biaya==4){
							echo "Biaya Lainnya";
						}  ?>
					</td>
					<td><?php echo $v->keterangan?></td>
					<td><?php echo $v->item?></td>
					<td>Rp. <?php echo number_format($v->tarif,0,",",".")?></td>
					<td>Rp. <?php echo number_format($v->item * $v->tarif,0,",",".")?></td>
					<td><img src="<?php echo base_url();?>upload/bukti/<?php echo $v->foto ;?>" width="150" height="100"></td>
					<td><a href="<?php echo base_url();?>telaah/laporan/rincian/update_view/<?php echo $this->uri->segment(5);?>?rincian_biaya_id=<?php echo $rincian_biaya_id;?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</a>
						<a href="<?php echo base_url();?>telaah/laporan/rincian/delete/<?php echo $this->uri->segment(5);?>?rincian_biaya_id=<?php echo $rincian_biaya_id;?>&&telaah_id=<?php echo $v->telaah_id;?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin ?');"><i class="fa fa-trash"></i> Hapus</a>
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