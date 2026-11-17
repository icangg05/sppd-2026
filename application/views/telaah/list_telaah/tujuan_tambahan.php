<?php
// Tujuan tambahan yang sudah diisi sebelum validasi gagal; markup sama dengan baris dari tombol "Tambah Tujuan"
$provinsi_tambahan = array_values((array) $this->input->post('telaah_provinsitujuan2'));
$kota_tambahan = array_values((array) $this->input->post('telaah_kotatujuan3'));
foreach ($provinsi_tambahan as $i => $provinsi_id) {
	$n = $i + 1;
	$kota_id = $kota_tambahan[$i] ?? '';
?>
	<div class='col-md-6'>
		<div class='form-group'>
			<label>Provinsi</label>
			<select class='form-control select2' name='telaah_provinsitujuan2[]' id='provinsi_id<?php echo $n; ?>' onChange='tampil_kabupaten<?php echo $n; ?>()' style='width: 100%;'>
				<option value=''>- Pilih Provinsi -</option>
				<?php foreach ($provinsi as $v) {
					$selected = ($provinsi_id == $v->provinsi_id) ? ' selected' : '';
					echo "<option value='$v->provinsi_id'$selected>$v->provinsi</option>";
				} ?>
			</select>
		</div>
	</div>
	<div class='col-md-6'>
		<div class='form-group'>
			<label>Kota Tujuan</label>
			<select class='form-control select2' name='telaah_kotatujuan3[]' id='kabkot_id<?php echo $n; ?>' style='width: 100%;'>
				<option value=''>- Pilih Kabupaten/Kota -</option>
				<?php if ($provinsi_id) {
					foreach ($this->m_kabupaten->get_kabupaten2($provinsi_id) as $v) {
						$selected = ($kota_id == $v->kabkot_id) ? ' selected' : '';
						echo "<option value='$v->kabkot_id'$selected>$v->kabupaten_kota</option>";
					}
				} ?>
			</select>
		</div>
	</div>
<?php } ?>
