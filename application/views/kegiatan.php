<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
  <div class="box box-primary">
	<div class="box-header with-border">
	  <h3 class="box-title">Data Anggaran</h3>
	</div>
	<!-- /.box-header -->
	<!-- form start -->
	<center>
	  <div class="box-body">
		<div class="form-group">
		<div class="table-responsive box-body" style="height:385px; overflow:auto;">
		  <table class="table table-bordered table-striped table-hover" >
			<tr>
				<th class="col-md-10">Nama Program</th>
				<th class="col-md-1">Realisasi</th>
				<th class="col-md-1">#</th>
			</tr>
			<?php foreach($anggaran as $v){ ?>
			<tr>
				<td><?php echo $v->nama_program;?></td>
				<td><?php 
						$rincian_biaya =  $this->m_anggaran->cek_sisa_anggaran_skpd($v->id_anggaran);
						$pengeluaran_rill =  $this->m_anggaran->cek_pengeluaran_rill_skpd($v->id_anggaran);
						$rincian = $rincian_biaya[0]->jumlah + $pengeluaran_rill[0]->jumlah;
						$total = round(($rincian/$v->pagu)*100,2);
						if($total >= 0 && $total <= 50){
							echo "<span class='label label-success'>$total %</span>";
						} else if($total > 50 && $total <= 75){
							echo "<span class='label label-warning'>$total %</span>";
						} else if($total > 75 && $total <= 100){
							echo "<span class='label label-danger'>$total %</span>";
						} else if($total > 100){
							echo "<span class='label label-danger'>$total %</span>";
						} 
					?>	
				</td>
				<td> <a href="#"  class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal<?php echo $v->id_anggaran;?>"><i class="fa fa-eyes"></i> Lihat</a></td>
			</tr>
			<?php } ?>
		  </table>
		  
			<?php foreach($anggaran as $v){ ?>
			<!-- Modal -->
			<div id="myModal<?php echo $v->id_anggaran;?>" class="modal fade" role="dialog">
			  <div class="modal-dialog">

			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Detail Anggaran</h4>
				</div>
				<div class="modal-body">
				  <table class="table table-striped table-bordered">
					<tr>
					  <td class="col-md-3">Program</td>
					  <td><?php echo $v->nama_program;?></td>
					</tr>
					<tr>
					  <td class="col-md-3">Kegiatan</td>
					  <td><?php echo $v->nama_kegiatan;?></td>
					</tr>
					<tr>
					  <td class="col-md-3">Kode Rekening</td>
					  <td><?php echo $v->no_rekening;?></td>
					</tr>
					<tr>
					  <td class="col-md-3">Uraian</td>
					  <td><?php echo $v->uraian;?></td>
					</tr>
					<tr>
					  <td class="col-md-3">Total Anggaran</td>
					  <td><?php echo number_format($v->pagu, 0, ',', '.');?></td>
					</tr>
					<tr>
					  <td class="col-md-3">Realisasi Anggaran</td>
					  <td>
							<?php 
								$rincian_biaya =  $this->m_anggaran->cek_sisa_anggaran_skpd($v->id_anggaran);
								$pengeluaran_rill =  $this->m_anggaran->cek_pengeluaran_rill_skpd($v->id_anggaran);
								echo number_format($rincian_biaya[0]->jumlah + $pengeluaran_rill[0]->jumlah, 0, ',', '.') ;
							?>
					  </td>
					</tr>
					<tr>
					  <td class="col-md-3">Sisa Anggaran</td>
					  <td><?php  echo number_format($v->pagu - ($rincian_biaya[0]->jumlah + $pengeluaran_rill[0]->jumlah), 0, ',', '.'); ?></td>
					</tr>
					<tr>
					  <td class="col-md-3">Jumlah Perjalanan</td>
					  <td><?php $count_jumlah_perjalanan =  $this->m_beranda->count_jumlah_perjalanan($v->id_anggaran);
								echo number_format($count_jumlah_perjalanan, 0, ',', '.'); ?></td>
					</tr>
				  </table>
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			  </div>

			  </div>
			</div>
			<?php } ?>
		</div>
		</div>
		<!-- /.box-body -->
	  </div>
	</center>
	<br> <!-- /.box -->
  </div>
</div>