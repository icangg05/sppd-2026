<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Verifikasi keaslian dokumen PDF ber-TTE ke BSrE
 * (padanan BsreVerificationService di sppd-update).
 *
 * Berkas diunggah sebagai field 'signed_file' ke proxy TTE yang sama dengan
 * proses tanda tangan (Qr_new: http://103.85.5.99/api/sign/...), memakai
 * Basic auth yang sama. Respons BSrE: summary, notes, nama_dokumen,
 * jumlah_signature, details[].
 *
 * Konfigurasi lewat environment (opsional):
 *   TTE_VERIFY_ENDPOINT  default http://103.85.5.99/api/sign/verify
 *   TTE_BASIC_AUTH       kredensial Basic (sama dengan signing di Qr_new)
 *   TTE_VERIFY_TIMEOUT   detik, default 30
 */
class Tte_verifikasi
{
	const ENDPOINT_DEFAULT = 'http://103.85.5.99/api/sign/verify';

	public function endpoint()
	{
		return getenv('TTE_VERIFY_ENDPOINT') ?: self::ENDPOINT_DEFAULT;
	}

	private function basic_auth()
	{
		return getenv('TTE_BASIC_AUTH') ?: 'ZXNwcGQ6TUEzNDVnRmJCR0hUTWRkeDdlVXI=';
	}

	private function timeout()
	{
		return (int) (getenv('TTE_VERIFY_TIMEOUT') ?: 30);
	}

	private function hasil_dasar()
	{
		return array(
			'ok'              => FALSE,
			'summary'         => NULL,
			'valid'           => NULL,
			'document_name'   => NULL,
			'signature_count' => NULL,
			'notes'           => NULL,
			'details'         => array(),
			'error'           => NULL,
		);
	}

	public function gagal($pesan)
	{
		return array_merge($this->hasil_dasar(), array('error' => $pesan));
	}

	/**
	 * @param string $path      path berkas PDF lokal (sementara)
	 * @param string $nama_file nama berkas yang dikirim ke BSrE
	 */
	public function verifikasi($path, $nama_file = 'document.pdf')
	{
		if (!function_exists('curl_init')) {
			return $this->gagal('Ekstensi cURL tidak tersedia di server.');
		}

		// nama berkas hanya untuk header multipart: buang karakter yang bisa merusak header
		$nama_file = preg_replace('/[^A-Za-z0-9._ -]+/', '_', basename((string) $nama_file));
		if ($nama_file === '' || $nama_file === '.pdf') {
			$nama_file = 'document.pdf';
		}

		$ch = curl_init($this->endpoint());
		curl_setopt_array($ch, array(
			CURLOPT_POST           => TRUE,
			CURLOPT_POSTFIELDS     => array('signed_file' => new CURLFile($path, 'application/pdf', $nama_file)),
			CURLOPT_HTTPHEADER     => array(
				'Authorization: Basic ' . $this->basic_auth(),
				'Accept: application/json',
			),
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_TIMEOUT        => $this->timeout(),
		));
		$body   = curl_exec($ch);
		$err    = curl_error($ch);
		$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($body === FALSE) {
			log_message('error', 'Tte_verifikasi: gagal terhubung - ' . $err);
			return $this->gagal('Tidak dapat terhubung ke server verifikasi: ' . $err);
		}
		if ($status >= 400) {
			log_message('error', 'Tte_verifikasi: HTTP ' . $status . ' - ' . substr((string) $body, 0, 500));
			return $this->gagal('Server verifikasi menolak permintaan (HTTP ' . $status . ').');
		}

		$json = json_decode($body, TRUE);
		if (!is_array($json)) {
			log_message('error', 'Tte_verifikasi: respons bukan JSON - ' . substr((string) $body, 0, 500));
			return $this->gagal('Respons verifikasi tidak dapat dibaca.');
		}

		$summary = isset($json['summary']) ? $json['summary'] : (isset($json['SUMMARY']) ? $json['SUMMARY'] : NULL);

		return array_merge($this->hasil_dasar(), array(
			'ok'              => TRUE,
			'summary'         => $summary,
			'valid'           => $this->tafsir_summary($summary),
			'document_name'   => isset($json['nama_dokumen']) ? $json['nama_dokumen'] : (isset($json['document_name']) ? $json['document_name'] : NULL),
			'signature_count' => isset($json['jumlah_signature']) ? (int) $json['jumlah_signature'] : NULL,
			'notes'           => isset($json['notes']) ? $json['notes'] : NULL,
			'details'         => (isset($json['details']) && is_array($json['details'])) ? $json['details'] : array(),
		));
	}

	// Sama dengan sppd-update: "VALID" -> TRUE; "INVALID"/"NOT VALID"/"TIDAK VALID"/
	// "NO_SIGNATURE"/lainnya -> FALSE; summary kosong -> NULL
	public function tafsir_summary($summary)
	{
		if (!is_string($summary) || $summary === '') {
			return NULL;
		}
		if (preg_match('/INVALID|NOT\s+VALID|TIDAK\s+VALID/i', $summary)) {
			return FALSE;
		}
		return (bool) preg_match('/\bVALID\b/i', $summary);
	}
}
