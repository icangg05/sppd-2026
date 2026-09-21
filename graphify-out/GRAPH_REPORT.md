# Graph Report - application  (2026-09-21)

## Corpus Check
- Large corpus: 704 files · ~701,540 words. Semantic extraction will be expensive (many Claude tokens). Consider running on a subfolder.

## Summary
- 3216 nodes · 4515 edges · 399 communities (252 shown, 147 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 24 edges (avg confidence: 0.85)
- Token cost: 55,954 input · 0 output

## Community Hubs (Navigation)
- Root Admin Model
- API Data Model
- Ion Auth Authentication
- Sekda Telaah Model
- Eselon Telaah Model
- SPD Print & SPPD Form
- Root Admin Controller
- TCPDF QR Encoder
- Report Data Model
- REST API Controller
- Kadis Telaah Model
- Approved Telaah Model
- Rejected Telaah Model
- Core Telaah Model
- Telaah List Controller
- QR Input Encoding
- PHPExcel Workbook
- DPRD Telaah Model
- Kapus Telaah Model
- Walikota Telaah Model
- SPD Report Model
- phpqrcode QR Spec
- Pegawai (Employee) Model
- QR Spec Library
- Sekwan Telaah Model
- QR Frame Filler
- Disposisi Timeline Model
- Root User Model
- Curl HTTP Library
- phpqrcode QR Input
- DPRD Staff Model
- QR Print Controller v2
- Camat Telaah Model
- Lurah Telaah Model
- QR Print Controller (new)
- phpqrcode Frame Filler
- Camat Staff Model
- Lurah Staff Model
- Legacy TTE API
- QR Print Controller
- Sekda Telaah Controller
- Anggaran (Budget) Model
- phpqrcode
- M_pengeluaran_rill
- Api_tte
- phpqrcode (2)
- qrmask
- M_kuitansi
- M_rincian
- Log
- M_log_tte
- Disposisi
- qrsplit
- Pegawai
- Staff_sekda
- M_beranda
- M_tanda_tangan
- M_log
- Qr3
- Dprd
- Esselon
- Kadis
- Kapus
- Sekwan
- Staff_dprd
- Ion_auth
- M_export
- M_timeline
- User
- Kuitansi
- Camat
- Lurah
- HttpClient
- phpqrcode (3)
- M_asisten
- M_kabupaten
- M_skpd
- M_pengikut
- Beranda
- Anggaran
- Detail_anggaran
- Laporan
- Staff_camat
- Staff_lurah
- Walikota
- M_anggota
- M_rekening
- M_menu
- M_log (2)
- M_spt
- M_jenis_skpd
- M_bagian
- M_provinsi
- M_sub_bagian
- MY_Controller
- Anggota
- Anggotadprd
- Rekening
- Tanda_tangan
- Walikota (2)
- Asisten
- Bagian
- Export
- Kabupaten
- Provinsi
- Skpd
- Sub_bagian
- phpqrcode (4)
- qrbitstream
- qrtools
- M_walikota
- M_relasi_sekda
- Pengeluaran_rill
- Rincian
- Spd
- Spt
- phpqrcode (5)
- M_laporan_perjalanan
- M_verifikasi
- Dokumen
- Pptk_pengeluaran_rill
- Bcrypt
- PHPExcel
- M_pptk_pengeluaran_rill
- M_history
- M_history (2)
- MY_Loader
- Utilitas
- qrrscode
- Login
- Setuju_bayar
- Kalender
- Log_tte
- Verifikasi
- M_widget
- M_widget (2)
- M_lokasi_tujuan
- Tte
- ckeditor_helper
- qrimage
- M_relasi_kelurahan
- M_tte
- PHPExcel (3)
- Waktu
- composer
- MY_file_helper
- Excel
- Paging
- Pdf
- Pdf2
- qrconst
- Qrlib
- PHPExcel (5)
- PHPExcel (9)
- PHPExcel (10)
- merge

## God Nodes (most connected - your core abstractions)
1. `M_admin` - 145 edges
2. `M_api` - 136 edges
3. `QRcode` - 99 edges
4. `M_telaah` - 77 edges
5. `Ion_auth_model` - 74 edges
6. `M_sekda` - 68 edges
7. `PHPExcel` - 65 edges
8. `M_walikota` - 65 edges
9. `Api` - 60 edges
10. `M_esselon` - 56 edges

## Surprising Connections (you probably didn't know these)
- `Beranda` --inherits--> `Public_Controller`  [EXTRACTED]
  controllers/Beranda.php → core/MY_Controller.php
- `Login` --inherits--> `MY_Controller`  [EXTRACTED]
  controllers/Login.php → core/MY_Controller.php
- `Api_tte` --inherits--> `Qr_Controller`  [EXTRACTED]
  controllers/api/Api_tte.php → core/MY_Controller.php
- `Api_tte_old` --inherits--> `Qr_Controller`  [EXTRACTED]
  controllers/api/Api_tte_old.php → core/MY_Controller.php
- `Anggaran` --inherits--> `Public_Controller`  [EXTRACTED]
  controllers/setting_admin/Anggaran.php → core/MY_Controller.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **SPD print variants producing the SPPD form** — controllers_telaah_laporan_laporan_laporan_cetak_spd, controllers_telaah_laporan_laporan_dprd_laporan_dprd_cetak_spd_dprd, controllers_telaah_laporan_laporan_walikota_laporan_walikota_cetak_spdwalikota, controllers_telaah_laporan_qr_qr_cetak_spd, controllers_telaah_laporan_qr3_qr3_cetak_spd, controllers_telaah_laporan_qr_new_qr_new_cetak_spd [INFERRED 0.75]

## Communities (399 total, 147 thin omitted)

### Community 5 - "SPD Print & SPPD Form"
Cohesion: 0.05
Nodes (16): Laporan_dprd, Laporan, Laporan_walikota, Alat angkutan, Kop surat Pemerintah Kota Kendari (letterhead), Lama perjalanan & tanggal berangkat/kembali, Maksud perjalanan dinas, Pangkat dan golongan / jabatan / tingkat biaya (+8 more)

### Community 27 - "QR Frame Filler"
Cohesion: 0.13
Nodes (7): FrameFiller, QRinput, QRcode, QRencode, QRrawcode, QRrsblock, QRrsItem

### Community 37 - "phpqrcode Frame Filler"
Cohesion: 0.11
Nodes (6): FrameFiller, QRimage, QRrawcode, QRrs, QRrsblock, QRrsItem

### Community 45 - "phpqrcode"
Cohesion: 0.17
Nodes (3): QRcode, QRencode, QRtools

### Community 52 - "Log"
Cohesion: 0.11
Nodes (4): Log, History, Lokasi_tujuan, Public_Controller

### Community 53 - "M_log_tte"
Cohesion: 0.11
Nodes (5): CI_Model, M_setuju_bayar, M_kalender, M_log_tte, m_utilitas

### Community 55 - "qrsplit"
Cohesion: 0.29
Nodes (3): str_split(), QRinput, QRsplit

### Community 98 - "MY_Controller"
Cohesion: 0.24
Nodes (4): CI_Controller, Admin_Controller, MY_Controller, Qr_Controller

### Community 145 - "ckeditor_helper"
Cohesion: 0.70
Nodes (4): cke_create_instance(), cke_initialize(), config_data(), display_ckeditor()

## Knowledge Gaps
- **13 isolated node(s):** `phpoffice/phpword`, `merge.sh script`, `Kop surat Pemerintah Kota Kendari (letterhead)`, `Pejabat berwenang pemberi perintah`, `Pegawai yang diperintahkan` (+8 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **147 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Public_Controller` connect `Log` to `Utilitas`, `SPD Print & SPPD Form`, `Root Admin Controller`, `Setuju_bayar`, `Kalender`, `Log_tte`, `Verifikasi`, `Telaah List Controller`, `Tte`, `Sekda Telaah Controller`, `Disposisi`, `Pegawai`, `Staff_sekda`, `Dprd`, `Esselon`, `Kadis`, `Kapus`, `Sekwan`, `Staff_dprd`, `Kuitansi`, `Camat`, `Lurah`, `Beranda`, `Anggaran`, `Detail_anggaran`, `Laporan`, `Staff_camat`, `Staff_lurah`, `Walikota`, `MY_Controller`, `Anggota`, `Anggotadprd`, `Rekening`, `Tanda_tangan`, `Walikota (2)`, `Asisten`, `Bagian`, `Export`, `Provinsi`, `Skpd`, `Sub_bagian`, `Pengeluaran_rill`, `Rincian`, `Spd`, `Spt`, `Dokumen`, `Pptk_pengeluaran_rill`?**
  _High betweenness centrality (0.081) - this node is a cross-community bridge._
- **Why does `Qr_Controller` connect `MY_Controller` to `QR Print Controller v2`, `QR Print Controller (new)`, `Legacy TTE API`, `QR Print Controller`, `Api_tte`, `Qr3`?**
  _High betweenness centrality (0.034) - this node is a cross-community bridge._
- **Why does `M_admin` connect `Root Admin Model` to `M_log_tte`?**
  _High betweenness centrality (0.033) - this node is a cross-community bridge._
- **What connects `phpoffice/phpword`, `merge.sh script`, `Kop surat Pemerintah Kota Kendari (letterhead)` to the rest of the system?**
  _13 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Root Admin Model` be split into smaller, more focused modules?**
  _Cohesion score 0.013793103448275862 - nodes in this community are weakly interconnected._
- **Should `API Data Model` be split into smaller, more focused modules?**
  _Cohesion score 0.014705882352941176 - nodes in this community are weakly interconnected._
- **Should `Ion Auth Authentication` be split into smaller, more focused modules?**
  _Cohesion score 0.10440577563865235 - nodes in this community are weakly interconnected._