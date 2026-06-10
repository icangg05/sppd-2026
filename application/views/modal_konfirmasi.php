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
		$warna = "success";
	} else if($total > 50 && $total <= 75){
		$warna = "warning";
	} else if($total > 75 && $total <= 100){
		$warna = "danger";
	} else if($total > 100){
		$warna = "danger";
	} 
	
	if(isset($telaah_pelaksana[0]) && isset($telaah_pelaksana[0]['status'])){
		if($telaah_pelaksana[0]['status']==1){
			$pelaksana = $telaah_pelaksana[0]['pegawai_nama']." <i class='fa fa-check-circle text-red'></i>";
		} else {
			$pelaksana = $telaah_pelaksana[0]['pegawai_nama']." <i class='fa fa-check-circle text-green'></i>";
		}
	} else {
		$pelaksana = isset($telaah_pelaksana[0]['pegawai_nama']) ? $telaah_pelaksana[0]['pegawai_nama'] : "-";
	}
	
	echo "	
			
			<table class='table table-bordered table-striped'>
				<tr class='info'>
					<th colspan=2><center>ANGGARAN</center></th>
				</tr>
				<tr>
					<th class='col-md-3'>Total Anggaran</th>
					<th class='col-md-9'><button type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($pagu[0]['pagu'], 0, ',', '.')." </button></th>
				</tr>
				<tr>
					<th class='col-md-3'>Realisasi Anggaran</th>
					<th class='col-md-4'>
					<button type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($rincian, 0, ',', '.')." </b></button>
					 &nbsp;&nbsp; Persentase : <button type='button' class='btn btn-sm btn-$warna'><b>$total %</b></button>
					</th>
				</tr>
				<tr>
					<th class='col-md-3'>Anggaran Tersedia</th>
					<th class='col-md-10'><button  type='button' class='btn btn-sm btn-default'><b> Rp. ".number_format($sisa, 0, ',', '.')."<b></button></th>
				</tr>
			</table>
		 
		 ";
		
	}
	?>