<style>
	.cek-dokumen .hasil-banner {
		display: flex;
		align-items: center;
		gap: 14px;
		padding: 16px 18px;
		border-radius: 4px;
		margin: 0 0 15px;
		border: 1px solid transparent;
	}
	.cek-dokumen .hasil-banner .ikon { font-size: 34px; line-height: 1; }
	.cek-dokumen .hasil-banner h4 { margin: 0 0 3px; font-weight: 700; word-break: break-word; }
	.cek-dokumen .hasil-banner p { margin: 0; font-size: 12px; opacity: .85; }
	.cek-dokumen .valid { background: #eaf7ef; border-color: #b9e2c7; color: #1e7e44; }
	.cek-dokumen .tidak-valid { background: #fdf0ef; border-color: #f2c3be; color: #b03a2e; }
	.cek-dokumen .netral { background: #f4f6f8; border-color: #dde2e7; color: #455a64; }
	.cek-dokumen .tabel-ringkas th { width: 200px; color: #666; font-weight: 600; }
	.cek-dokumen .tabel-ringkas td { word-break: break-word; }
	.cek-dokumen .judul-rincian { font-weight: 700; text-transform: uppercase; color: #546e7a; margin: 15px 0 8px; }
	.cek-dokumen .kartu-ttd {
		border: 1px solid #e3e7eb;
		background: #fafbfc;
		border-radius: 4px;
		padding: 10px 14px;
		margin-bottom: 10px;
	}
	.cek-dokumen .kartu-ttd h5 { margin: 0 0 8px; font-weight: 700; color: #37474f; }
	.cek-dokumen .kartu-ttd dl { margin: 0; }
	.cek-dokumen .kartu-ttd dt { color: #78909c; font-weight: 400; font-size: 12px; }
	.cek-dokumen .kartu-ttd dd { margin-bottom: 6px; word-break: break-word; }
	.cek-dokumen .ok { color: #1e7e44; font-weight: 700; }
	.cek-dokumen .buruk { color: #b03a2e; font-weight: 700; }
	.cek-dokumen .waspada { color: #b7791f; font-weight: 700; }

	/* Pratinjau dokumen */
	.cek-dokumen .box-pratinjau .box-body { padding: 0; }
	.cek-dokumen .pratinjau-kosong {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		gap: 10px;
		height: 65vh;
		min-height: 360px;
		color: #90a4ae;
		background: #f7f9fa;
		text-align: center;
		padding: 20px;
	}
	.cek-dokumen .pratinjau-kosong .fa { font-size: 48px; }
	.cek-dokumen .pratinjau-pdf {
		display: block;
		width: 100%;
		height: 65vh;
		min-height: 360px;
		border: 0;
		background: #525659;
	}
	.cek-dokumen .pratinjau-info {
		font-size: 12px;
		color: #78909c;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.cek-dokumen .pratinjau-info:empty { display: none; }
	@media (min-width: 992px) {
		.cek-dokumen .kolom-pratinjau { position: sticky; top: 65px; }
	}
</style>

<div class="content-wrapper">
	<section class="content cek-dokumen">
		<div class="row">
			<!-- Kiri: unggah + hasil verifikasi -->
			<div class="col-md-8 col-xs-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">CEK DOKUMEN TTE</h3>
					</div>
					<div class="box-body">
						<p class="text-muted">
							Unggah berkas PDF yang sudah ditandatangani elektronik untuk memeriksa keasliannya langsung ke BSrE.
							Berkas hanya dikirim untuk diverifikasi dan tidak disimpan di server.
						</p>
						<?php echo form_open_multipart('setting_admin/dokumen/cek', array('id' => 'form-cek-dokumen')); ?>
						<div class="form-group">
							<label for="userfile">Berkas PDF <span style="color:#dd4b39">*</span></label>
							<input class="form-control" type="file" name="userfile" id="userfile" data-maks-mb="10" data-tanpa-keterangan accept=".pdf,application/pdf" required>
							<p class="help-block">Format PDF, maksimal <?php echo html_escape($batas_ukuran); ?>.</p>
						</div>
						<button type="submit" class="btn btn-success" id="btn-cek-dokumen"><i class="fa fa-shield-alt"></i> Cek Dokumen</button>
						<?php echo form_close(); ?>
					</div>
				</div>

				<div id="hasil-cek-dokumen" aria-live="polite">
					<?php if (is_array($hasil)) { $this->load->view('setting_admin/dokumen/hasil', array('hasil' => $hasil)); } ?>
				</div>
			</div>

			<!-- Kanan: pratinjau dokumen (dibuat di browser, tidak diunggah ulang) -->
			<div class="col-md-4 col-xs-12">
				<div class="kolom-pratinjau">
					<div class="box box-primary box-pratinjau">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-file-pdf"></i> PRATINJAU DOKUMEN</h3>
							<div class="pratinjau-info" id="pratinjau-info"></div>
						</div>
						<div class="box-body">
							<div class="pratinjau-kosong" id="pratinjau-kosong">
								<i class="fa fa-file-pdf"></i>
								<div id="pratinjau-pesan">Pilih berkas PDF untuk melihat pratinjaunya di sini.</div>
							</div>
							<object class="pratinjau-pdf" id="pratinjau-pdf" type="application/pdf" style="display:none" aria-label="Pratinjau dokumen PDF">
								<div class="pratinjau-kosong">
									<i class="fa fa-file-pdf"></i>
									<div>Browser ini tidak dapat menampilkan PDF di halaman.<br>
										<a href="#" id="pratinjau-tautan" target="_blank" rel="noopener">Buka pratinjau di tab baru</a></div>
								</div>
							</object>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
	(function () {
		var form = document.getElementById('form-cek-dokumen');
		var input = document.getElementById('userfile');
		var btn = document.getElementById('btn-cek-dokumen');
		var hasil = document.getElementById('hasil-cek-dokumen');
		var kosong = document.getElementById('pratinjau-kosong');
		var pesan = document.getElementById('pratinjau-pesan');
		var obj = document.getElementById('pratinjau-pdf');
		var tautan = document.getElementById('pratinjau-tautan');
		var info = document.getElementById('pratinjau-info');
		var teksTombol = btn.innerHTML;
		var urlPratinjau = null;

		function ukuran(b) {
			return b >= 1048576 ? (b / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(b / 1024)) + ' KB';
		}

		function tampilKosong(teks) {
			if (urlPratinjau) { URL.revokeObjectURL(urlPratinjau); urlPratinjau = null; }
			obj.style.display = 'none';
			obj.removeAttribute('data');
			kosong.style.display = '';
			pesan.textContent = teks;
			info.textContent = '';
		}

		// Pratinjau langsung dari berkas yang dipilih; hasil lama dibersihkan
		input.addEventListener('change', function () {
			hasil.innerHTML = '';
			var f = input.files && input.files[0];
			if (!f) { tampilKosong('Pilih berkas PDF untuk melihat pratinjaunya di sini.'); return; }
			var pdf = f.type === 'application/pdf' || /\.pdf$/i.test(f.name);
			if (!pdf) { tampilKosong('Berkas "' + f.name + '" bukan PDF, pratinjau tidak tersedia.'); return; }
			if (urlPratinjau) { URL.revokeObjectURL(urlPratinjau); }
			urlPratinjau = URL.createObjectURL(f);
			// object perlu diganti agar browser memuat data baru
			var baru = obj.cloneNode(true);
			baru.setAttribute('data', urlPratinjau);
			baru.style.display = 'block';
			obj.parentNode.replaceChild(baru, obj);
			obj = baru;
			tautan = obj.querySelector('#pratinjau-tautan');
			if (tautan) { tautan.href = urlPratinjau; }
			kosong.style.display = 'none';
			info.textContent = f.name + ' (' + ukuran(f.size) + ')';
			info.title = f.name;
		});

		// Kirim lewat AJAX agar pratinjau tetap tampil; tanpa JS form tetap terkirim biasa
		form.addEventListener('submit', function (ev) {
			if (!window.fetch || !window.FormData) {
				btn.disabled = true;
				return;
			}
			ev.preventDefault();
			btn.disabled = true;
			btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memeriksa ke BSrE...';
			fetch(form.action, {
				method: 'POST',
				body: new FormData(form),
				credentials: 'same-origin',
				headers: { 'X-Requested-With': 'XMLHttpRequest' }
			}).then(function (r) {
				return r.text();
			}).then(function (html) {
				if (html.indexOf('data-hasil-cek-dokumen') === -1) {
					throw new Error('sesi');
				}
				hasil.innerHTML = html;
			}).catch(function (err) {
				hasil.innerHTML = '<div class="callout callout-danger"><h4><i class="fa fa-exclamation-circle"></i> Verifikasi gagal</h4><p>' +
					(err && err.message === 'sesi' ? 'Sesi login berakhir. Muat ulang halaman lalu masuk kembali.' : 'Tidak dapat menghubungi server. Coba lagi.') + '</p></div>';
			}).then(function () {
				btn.disabled = false;
				btn.innerHTML = teksTombol;
			});
		});
	})();
</script>
