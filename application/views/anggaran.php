<?php
if(!isset($pagu[0]) || $pagu[0] == ""){
} else {
	echo "<br>";
	$sisa = $pagu[0]['pagu']-($rincian_biaya[0]->jumlah + $pengeluaran_rill[0]->jumlah);
	$rincian_biaya =  $this->m_anggaran->cek_sisa_anggaran_skpd($id_anggaran);
	$pengeluaran_rill =  $this->m_anggaran->cek_pengeluaran_rill_skpd($id_anggaran);
	$rincian = $rincian_biaya[0]->jumlah + $pengeluaran_rill[0]->jumlah;
	$total = round(($rincian/$pagu[0]['pagu'])*100,2);
		
		if($total >= 0 && $total <= 50){
			echo "	<table class='table table-bordered table-striped'>
					<tr>
						<th class='col-md-2'>Total Anggaran</th>
						<th class='col-md-10'><button type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($pagu[0]['pagu'], 0, ',', '.')." </button></th>
					</tr>
					<tr>
						<th class='col-md-2'>Realisasi Anggaran</th>
						<th class='col-md-4'>
						<button type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($rincian, 0, ',', '.')." </b></button>
						 &nbsp;&nbsp; Persentase : <button type='button' class='btn btn-sm btn-success'><b>$total %</b></button>
						</th>
					</tr>
					<tr>
						<th class='col-md-2'>Anggaran Tersedia</th>
						<th class='col-md-10'><button  type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($sisa, 0, ',', '.')."<b></button></th>
					</tr>
				</table>";
		} else if($total > 50 && $total <= 75){
			echo "	<table class='table table-bordered table-striped'>
					<tr>
						<th class='col-md-2'>Total Anggaran</th>
						<th class='col-md-10'><button type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($pagu[0]['pagu'], 0, ',', '.')." </button></th>
					</tr>
					<tr>
						<th class='col-md-2'>Realisasi Anggaran</th>
						<th class='col-md-4'>
						<button type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($rincian, 0, ',', '.')." </b></button>
						 &nbsp;&nbsp; Persentase : <button type='button' class='btn btn-sm btn-warning'><b>$total %</b></button>
						</th>
					</tr>
					<tr>
						<th class='col-md-2'>Anggaran Tersedia</th>
						<th class='col-md-10'><button  type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($sisa, 0, ',', '.')."<b></button></th>
					</tr>
				</table>";
		} else if($total > 75 && $total <= 100){
			echo "	<table class='table table-bordered table-striped'>
					<tr>
						<th class='col-md-2'>Total Anggaran</th>
						<th class='col-md-10'><button type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($pagu[0]['pagu'], 0, ',', '.')." </button></th>
					</tr>
					<tr>
						<th class='col-md-2'>Realisasi Anggaran</th>
						<th class='col-md-4'>
						<button type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($rincian, 0, ',', '.')." </b></button>
						 &nbsp;&nbsp; Persentase : <button type='button' class='btn btn-sm btn-danger'><b>$total %</b></button>
						</th>
					</tr>
					<tr>
						<th class='col-md-2'>Anggaran Tersedia</th>
						<th class='col-md-10'><button  type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($sisa, 0, ',', '.')."<b></button></th>
					</tr>
				</table>";
		} else if($total > 100){
			echo "	<table class='table table-bordered table-striped'>
					<tr>
						<th class='col-md-2'>Total Anggaran</th>
						<th class='col-md-10'><button type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($pagu[0]['pagu'], 0, ',', '.')." </button></th>
					</tr>
					<tr>
						<th class='col-md-2'>Realisasi Anggaran</th>
						<th class='col-md-4'>
						<button type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($rincian, 0, ',', '.')." </b></button>
						 &nbsp;&nbsp; Persentase : <button type='button' class='btn btn-sm btn-danger'><b>$total %</b></button>
						</th>
					</tr>
					<tr>
						<th class='col-md-2'>Anggaran Yang Tersedia</th>
						<th class='col-md-10'><button  type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($sisa, 0, ',', '.')."<b></button></th>
					</tr>
				</table>";
		} 
		
	}
	?>