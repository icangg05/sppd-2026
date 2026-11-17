<script>
	// Tombol submit passphrase TTE: animasi loading & nonaktif selama proses tanda tangan (mencegah klik ganda).
	// Aktif kembali bila server TTE tidak merespons dalam batas waktu, koneksi terputus, atau halaman
	// ditampilkan lagi lewat tombol Back. Error dari server tampil di halaman baru (flash), tombolnya pun aktif.
	(function($) {
		if (window.tteTombolLoading) {
			return; // sudah dipasang (partial dimuat lebih dari sekali)
		}
		window.tteTombolLoading = true;
		var BATAS_WAKTU = 90000; // ms

		function pulihkan(form, pesan) {
			var $form = $(form);
			clearTimeout($form.data('tte-timer'));
			$form.data('tte-proses', false);
			$form.find('.tte-nilai-tombol').remove();
			$(form.elements).filter(':submit').each(function() {
				var $tombol = $(this);
				if ($tombol.data('tte-label') !== undefined) {
					$tombol.prop('disabled', false).html($tombol.data('tte-label'));
				}
			});
			var $pass = $(form.elements).filter('[name="passphrase"]');
			$pass.siblings('.tte-pesan').remove();
			if (pesan) {
				$('<p class="text-danger tte-pesan" style="margin-top:6px;"></p>').text(pesan).insertAfter($pass);
			}
		}

		$(document).on('submit', 'form[action*="generate_tte"]', function() {
			var form = this, $form = $(this);
			if ($form.data('tte-proses')) {
				return false;
			}
			$form.data('tte-proses', true);
			$(form.elements).filter('[name="passphrase"]').siblings('.tte-pesan').remove();

			$(form.elements).filter(':submit').each(function() {
				var $tombol = $(this);
				// tombol nonaktif tidak ikut terkirim: nilainya dititipkan lewat input hidden
				if ($tombol.attr('name')) {
					$('<input type="hidden" class="tte-nilai-tombol">').attr('name', $tombol.attr('name')).val($tombol.val()).appendTo($form);
				}
				$tombol.data('tte-label', $tombol.html()).prop('disabled', true)
					.html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
			});

			$form.data('tte-timer', setTimeout(function() {
				pulihkan(form, 'Server TTE belum merespons. Periksa status dokumen, lalu coba lagi bila belum tertandatangani.');
			}, BATAS_WAKTU));
		});

		$(window).on('offline', function() {
			$('form[action*="generate_tte"]').each(function() {
				if ($(this).data('tte-proses')) {
					pulihkan(this, 'Koneksi internet terputus. Silakan coba lagi.');
				}
			});
		});

		$(window).on('pageshow', function(e) {
			if (e.originalEvent.persisted) {
				$('form[action*="generate_tte"]').each(function() {
					pulihkan(this, '');
				});
			}
		});
	})(jQuery);
</script>
