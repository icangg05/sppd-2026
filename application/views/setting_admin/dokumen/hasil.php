<?php
// Hasil verifikasi BSrE (dipakai halaman penuh dan respons AJAX). Menerima: $hasil
$e = function ($v) { return html_escape((string) $v); };
$ambil = function ($arr, $jalur) {
	foreach (explode('.', $jalur) as $k) {
		if (!is_array($arr) || !array_key_exists($k, $arr)) {
			return NULL;
		}
		$arr = $arr[$k];
	}
	return $arr;
};
?>
<div data-hasil-cek-dokumen="1">
<?php if (!$hasil['ok']) { ?>
	<div class="callout callout-danger">
		<h4><i class="fa fa-exclamation-circle"></i> Verifikasi gagal</h4>
		<p><?php echo $e($hasil['error']); ?></p>
	</div>
<?php } else {
	$valid = $hasil['valid'];
	$kelas = $valid === TRUE ? 'valid' : ($valid === FALSE ? 'tidak-valid' : 'netral');
	$ikon = $valid === TRUE ? 'fa-check-circle' : ($valid === FALSE ? 'fa-times-circle' : 'fa-info-circle');
?>
	<div class="box box-default">
		<div class="box-body">
			<div class="hasil-banner <?php echo $kelas; ?>">
				<i class="fa <?php echo $ikon; ?> ikon"></i>
				<div>
					<h4><?php echo $e($hasil['summary'] !== NULL && $hasil['summary'] !== '' ? $hasil['summary'] : 'Hasil Verifikasi BSrE'); ?></h4>
					<p><i class="fa fa-certificate"></i> Diverifikasi langsung oleh BSrE</p>
				</div>
			</div>

			<table class="table table-condensed tabel-ringkas">
				<?php if ($hasil['document_name'] !== NULL && $hasil['document_name'] !== '') { ?>
					<tr><th>Nama Dokumen</th><td><?php echo $e($hasil['document_name']); ?></td></tr>
				<?php } ?>
				<?php if ($hasil['signature_count'] !== NULL) { ?>
					<tr><th>Jumlah Tanda Tangan</th><td><b><?php echo (int) $hasil['signature_count']; ?></b></td></tr>
				<?php } ?>
				<?php if ($hasil['notes'] !== NULL && $hasil['notes'] !== '') { ?>
					<tr><th>Catatan</th><td><?php echo $e(is_array($hasil['notes']) ? implode('; ', $hasil['notes']) : $hasil['notes']); ?></td></tr>
				<?php } ?>
			</table>

			<?php if (!empty($hasil['details'])) { ?>
				<h5 class="judul-rincian">Rincian Tanda Tangan</h5>
				<?php foreach (array_values($hasil['details']) as $i => $d) {
					$penanda    = $ambil($d, 'info_signer.signer_name');
					$penerbit   = $ambil($d, 'info_signer.issuer_dn');
					$tepercaya  = $ambil($d, 'info_signer.cert_user_certified');
					$integritas = $ambil($d, 'signature_document.document_integrity');
					$waktu      = $ambil($d, 'signature_document.signed_in');
					$pakai_tsa  = $ambil($d, 'signature_document.signed_using_tsa');
					$tsa        = $ambil($d, 'info_tsa.name');
				?>
					<div class="kartu-ttd">
						<h5>Tanda tangan #<?php echo $i + 1; ?><?php echo $penanda ? ' oleh ' . $e($penanda) : ''; ?></h5>
						<dl>
							<?php if ($penerbit) { ?><dt>Penerbit Sertifikat</dt><dd><?php echo $e($penerbit); ?></dd><?php } ?>
							<?php if ($integritas !== NULL) { ?>
								<dt>Integritas Dokumen</dt>
								<dd class="<?php echo $integritas ? 'ok' : 'buruk'; ?>"><?php echo $integritas ? 'Utuh (tidak diubah)' : 'Berubah'; ?></dd>
							<?php } ?>
							<?php if ($tepercaya !== NULL) { ?>
								<dt>Sertifikat Tepercaya</dt>
								<dd class="<?php echo $tepercaya ? 'ok' : 'waspada'; ?>"><?php echo $tepercaya ? 'Ya' : 'Tidak'; ?></dd>
							<?php } ?>
							<?php if ($waktu) { ?><dt>Ditandatangani</dt><dd><?php echo $e($waktu); ?></dd><?php } ?>
							<?php if ($pakai_tsa && $tsa) { ?><dt>Stempel Waktu (TSA)</dt><dd><?php echo $e($tsa); ?></dd><?php } ?>
						</dl>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
<?php } ?>
</div>
