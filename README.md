# SPPD Kota Kendari

Alur: **Pengajuan → Persetujuan → TTE → QR code**

## File yang terpakai

| File | Fungsi |
|---|---|
| `controllers/telaah/List_telaah.php` | Input pengajuan perjalanan + membuat draf PDF SPPD/SPT (`upload/doc_perjalanan/`) |
| `controllers/telaah/Disposisi.php` | Persetujuan berjenjang (terima / tolak / perbaiki) + pratinjau PDF |
| `controllers/telaah/Detail_anggaran.php` | Detail anggaran saat persetujuan |
| `controllers/telaah/laporan/Qr_new.php` | TTE: membuat PDF final, mengirim ke server TTE, menyimpan hasilnya (`upload/doc_TTE/`) |
| `controllers/telaah/History.php` | Halaman yang terbuka saat QR code di-scan |
| `controllers/telaah/laporan/Spd.php`, `Spt.php` | Tombol unduh SPPD/SPT yang sudah di-TTE |
| `controllers/telaah/laporan/Laporan.php` | Cetak kuitansi, rincian biaya, pengeluaran riil |

Ubah isi cetakan SPPD di `List_telaah::cetak_spd` (draf) dan `Qr_new::cetak_spd` (final TTE).

## File yang tidak terpakai

| File | Keterangan |
|---|---|
| `controllers/telaah/laporan/Qr.php`, `Qr2.php`, `Qr3.php` | Versi lama `Qr_new.php` |
| `controllers/api/Api.php`, `Api_tte.php`, `Api_tte_old.php` | API mobile, tidak dipakai (terbuka tanpa login, sebaiknya dinonaktifkan) |
| `controllers/telaah/Tte.php` | Menu TTE lama, sudah dimatikan |
| `controllers/telaah/list_telaah/*.php` | Tertutup oleh `List_telaah.php` |
| `controllers/telaah/laporan/Laporan_walikota.php` | Tidak ada link |
