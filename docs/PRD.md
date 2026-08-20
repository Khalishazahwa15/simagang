SISTEM INFORMASI PENGELOLAAN MAGANG
MAHASISWA — BAPPEDA PROVINSI LAMPUNG
PRD v4.1 — FINAL MICRO-REVISION
Dokumen ini adalah patch terhadap PRD v3.0 berdasarkan 12 poin FINAL
CONFIRMATION dari Anda. Ini bukan PRD baru dari nol — struktur, Business Rule, dan
requirement dari v3.0 dipertahankan kecuali yang secara eksplisit direvisi/dihapus di
bawah.
A. CHANGE LOG — BAGIAN YANG BERUBAH DARI v3.0
#   Bagian v3.0 yang Terdampak            Perubahan                                  Dipicu oleh
                                                                                     Konfirmasi
                                                                                     #

1   BR-007 (rantai paraf/TTD sebagai      DIHAPUS. Paraf & TTD sepenuhnya di         #3
    proses tercatat sistem)               luar sistem; sistem hanya menerima
                                          dokumen final

2   Status Lifecycle — Menunggu           DIHAPUS. Diganti dengan atribut            #3, #4
    Tanda Tangan , Surat Terbit           ketersediaan dokumen (bukan
                                          status/state) pada status Diterima dan
                                          Selesai

3   Status Lifecycle — Ditempatkan        DIGABUNG ke Diterima — begitu              #5, #6
    (status terpisah sebelum              Sekretariat menetapkan penempatan
     Diterima )                           final, status langsung Diterima

4   Status Lifecycle — Ditolak            DISEDERHANAKAN menjadi satu                #1
    (Kelengkapan) dan Ditolak             status Ditolak dengan field alasan
    (Kapasitas/Kesesuaian)                (kategori alasan opsional sebagai
                                          metadata, bukan status terpisah)

5   Actor & Role — Sekretaris dan         DIHAPUS dari aktor sistem. Keduanya        #2, #3
     Kepala Badan sebagai aktor           tetap ada dalam proses administrasi
    sistem                                Bappeda (paraf/TTD fisik) tapi tidak
                                          menjadi user/role di dalam sistem

6   Actor & Role — Kasubag Umum &         DIGABUNG menjadi satu role sistem:         #2
    Kepegawaian dan Pelaksana             Sekretariat (mencakup Kasubag Umum
    Teknis sebagai dua role terpisah      & Kepegawaian beserta staf yang
                                          menjalankan fungsi administrasi,
                                          verifikasi)

7   FR-003 (mencatat status paraf per     DIHAPUS                                    #3
    tahap)

8   FR-018 (generate draf 4 jenis surat   DIREVISI. Sistem tidak men-generate        #3, #4
    via template + mail-merge)            draf surat; dokumen dibuat & diproses di
                                          luar sistem, Sekretariat hanya
                                          mengunggah dokumen final

9   FR-019 (generate draf daftar          DIHAPUS dari MVP. Daftar nominatif         #3, #4
    nominatif)                            (jika dibutuhkan) adalah proses
#    Bagian v3.0 yang Terdampak        Perubahan                                     Dipicu oleh
                                                                                     Konfirmasi
                                                                                     #
                                       administratif internal Bappeda di luar
                                       sistem

10 FR-020 (upload scan surat setelah   DIPERTAHANKAN, direvisi redaksi               #3, #4
   TTD)                                menjadi requirement utama modul
                                       dokumen: Sekretariat mengunggah
                                       dokumen final (bukan "scan setelah TTD
                                       ditandai di sistem", karena tidak ada lagi
                                       penandaan tahap TTD di sistem)

11   Document Requirements — Surat     DIREVISI. Tetap wajib ada, tapi               #4
     Balasan Penerimaan sebagai        sumbernya adalah upload dokumen final
     "wajib bila diterima" dengan      dari Sekretariat, bukan generate sistem
     proses generate

12   Document Requirements — Surat     DIHAPUS sebagai dokumen wajib.                #1
     Penolakan                         Penolakan cukup dicatat sebagai data
                                       (alasan) + notifikasi, tanpa surat terpisah

13 BR-006 (daftar nominatif sebagai    DIREVISI menjadi catatan konteks              #3, #4
   artifact sistem)                    (bukan requirement sistem). Daftar
                                       nominatif tetap ada secara administratif
                                       di Bappeda, tapi di luar cakupan sistem

14 Status Lifecycle & User Journey —   DIKONFIRMASI TETAP TIDAK ADA                  #5
   tidak ada status "Menunggu          (sudah konsisten sejak v3.0, ditegaskan
   Konfirmasi Mahasiswa"               ulang)

15   Role & Permission Matrix          DIROMBAK TOTAL mengikuti 3 role               #2, #3
                                       final: Mahasiswa, Sekretariat, Admin
                                       Sistem

16 Notification Requirements           DISESUAIKAN — notifikasi "surat               #1, #4
                                       terbit" diganti "dokumen tersedia untuk
                                       diunduh"; ditambahkan notifikasi
                                       eksplisit untuk keputusan Ditolak
                                       (alasan + tanpa surat)

17   Edge Cases (Bagian 34 v3.0)       Beberapa skenario terkait                     #1, #3, #5
                                       paraf/TTD/konfirmasi mahasiswa
                                       dihapus/direvisi karena sudah tidak
                                       relevan
 #   Bagian v3.0 yang Terdampak           Perubahan                                  Dipicu oleh
                                                                                     Konfirmasi
                                                                                     #

 18 Open Questions P0 (Part 6 v3.0)       4 dari 5 pertanyaan P0 kini RESOLVED       Semua
                                          oleh konfirmasi ini; sisa jadi P2 minor
                                          (lihat Part C)

 19 Final Readiness Assessment            Diperbarui ke READY, dengan sedikit        Semua
                                          catatan parameter teknis minor



B. UPDATED PRD (v4.0)
1. Document Control
 Item                      Keterangan

 Nama Produk               Sistem Informasi Pengelolaan Magang Mahasiswa — Bappeda
                           Provinsi Lampung

 Versi                     4.1 — Final Micro-Revision

 Status                    READY (business requirement level) — lihat Part D untuk detail

 Tanggal                   Agustus 2026

 Dasar Hukum               UU No. 25 Tahun 2009 tentang Pelayanan Publik; PP No. 96 Tahun
 (CONFIRMED, dari SOP)     2012; Permenpan-RB No. 15 Tahun 2014; Pergub Lampung No. 37
                           Tahun 2024

 Changelog                 v1.0 (baseline rekan) → v2.0 (audit + individual application) → v3.0
                           (rekonsiliasi SOP resmi) → v4.0 (final reconciliation, 12 konfirmasi
                           resmi)

 Klasifikasi Konten        CONFIRMED / DERIVED / PROPOSED / ⚠️ NEED CONFIRMATION



2. Executive Summary
Sistem mendigitalkan kanal pengajuan magang mahasiswa ke Bappeda Provinsi Lampung.
Seluruh proses legalisasi dokumen resmi (paraf, tanda tangan) berada di luar sistem —
sistem hanya menerima dan menyimpan dokumen final yang sudah selesai diproses secara
administratif oleh Bappeda. Aktor internal sistem disederhanakan menjadi satu peran
operasional: Sekretariat (mencakup Kasubag Umum & Kepegawaian beserta staf
administrasi/verifikasi), yang menangani seluruh siklus dari verifikasi berkas, pengecekan
kebutuhan divisi, penetapan penempatan & keputusan, hingga pencatatan pelaksanaan
dan pengunggahan dokumen akhir. Prinsip Individual Application (satu mahasiswa = satu
akun = satu pengajuan independen) tetap menjadi fondasi utama, tanpa mekanisme group
application, tanpa persetujuan berlapis di dalam sistem, dan tanpa konfirmasi ulang dari
mahasiswa setelah dinyatakan diterima.

3–8. Background, Problem Statement, Business Context, Goals, Non-Goals,
Scope
(Tidak berubah secara substansial dari v3.0, dengan penyesuaian berikut.)
Goals (revisi):
  1. Menyediakan kanal pengajuan magang daring untuk mahasiswa. (PROPOSED, tidak
     berubah)
  2. Menjamin setiap pengajuan diproses & diputuskan secara individual. (CONFIRMED)
  3. Menyediakan tempat penyimpanan & distribusi dokumen resmi final kepada mahasiswa, tanpa mengelola proses paraf/tanda tangan atau disposisi di dalam sistem.
     (CONFIRMED — revisi dari Goal #3 v3.0)
  4. Memungkinkan Sekretariat mengelola kebutuhan/kapasitas divisi secara dinamis.
     (CONFIRMED, tidak berubah)
  5. Menghasilkan draf 4 jenis surat resmi berbasis template → DIHAPUS sebagai goal
     sistem — pembuatan dokumen adalah proses internal Bappeda di luar sistem.
Non-Goals (ditambahkan):
     Sistem tidak membangun electronic approval, digital signature, atau workflow
     paraf/tanda tangan dalam bentuk apa pun. (CONFIRMED #3)
     Sistem tidak membangun status "Menunggu Konfirmasi Mahasiswa" atau
     mekanisme approval balik dari mahasiswa setelah diterima. (CONFIRMED #5)
     Sistem tidak mewajibkan surat penolakan formal. (CONFIRMED #1)
     (Non-Goals lain dari v3.0 tetap berlaku: group application, kuota statis, tanda tangan
     elektronik, AI screening, absensi/logbook, periode pendaftaran/H-7.)
Scope — In Scope (revisi):
     Registrasi & login mahasiswa
     Pengajuan magang individual (rolling, tanpa periode pendaftaran) — dokumen wajib:
     surat lamaran, CV, transkrip nilai; dokumen tambahan opsional
     Preferensi divisi (non-mengikat)
     Proses internal Sekretariat: pemeriksaan, revisi, pengecekan kebutuhan divisi,
     penempatan final, keputusan (Diterima/Ditolak dengan alasan)
     Pengunggahan & distribusi dokumen final (surat penerimaan, dokumen akhir
     magang) — bukan pembuatan/generate dokumen
     Data pelaksanaan magang (Sekretariat mencatat pembina/penanggung jawab,
     tanggal, status)
     Pengunduran diri formal (dengan surat resmi dari kampus)
     Notifikasi status
     Dashboard, search/filter, laporan
     Audit trail
Scope — Out of Scope (revisi, ditambah):
     Electronic approval / digital signature / workflow paraf-TTD di dalam sistem
     Generate/mail-merge draf surat oleh sistem
     Status "Menunggu Konfirmasi Mahasiswa"
     Surat penolakan formal
     (item Out of Scope v3.0 lainnya tetap berlaku)
9. Business Rules (Updated)
ID     Rule                                                   Status

BR-    Setiap mahasiswa memiliki satu akun dan satu           CONFIRMED (tidak berubah)
001    pengajuan independen; tidak ada group application.

BR-    Anggota kelompok yang sama dapat menerima              CONFIRMED (tidak berubah)
002    keputusan berbeda-beda tanpa keterkaitan otomatis.

BR-    Keputusan penerimaan mempertimbangkan                  CONFIRMED (tidak berubah,
003    kebutuhan/kapasitas divisi yang dinamis, kesesuaian    ditegaskan ulang #12)
       prodi, dan urutan pengajuan sebagai salah satu
       faktor.

BR-    Dokumen personal adalah tanggung jawab masing-         CONFIRMED (tidak berubah)
004    masing mahasiswa; surat pengantar/lamaran dapat
       direferensikan bersama tanpa mengubah pengajuan
       jadi satu entitas.

BR-    Preferensi divisi non-mengikat; penempatan final       CONFIRMED (ditegaskan ulang
005    adalah keputusan Sekretariat, termasuk ke divisi       #6)
       alternatif tanpa persetujuan ulang mahasiswa.

BR-    Daftar nominatif sebagai artifact keluaran sistem      DIHAPUS dari cakupan sistem.
006                                                           Daftar nominatif tetap ada secara
                                                              administratif di Bappeda tapi
                                                              bukan requirement sistem (lihat
                                                              Change Log #13)

BR-    Legalisasi dokumen mengikuti rantai paraf/TTD          DIHAPUS dan DIGANTI oleh
007    tercatat sistem                                        BR-007 (baru): Paraf dan tanda
                                                              tangan dokumen dilakukan
                                                              sepenuhnya di luar sistem.
                                                              Dokumen masuk ke sistem
                                                              hanya dalam bentuk final yang
                                                              sudah selesai proses
                                                              administratif. Sistem tidak
                                                              melacak tahapan paraf/TTD.

BR-    Pengajuan bersifat rolling (dibuka sepanjang waktu),   CONFIRMED (ditegaskan ulang
008    tanpa periode pendaftaran resmi maupun aturan H-       #8)
       7/H-3. Mahasiswa bertanggung jawab
       mempertimbangkan sendiri waktu proses sebelum
       tanggal mulai.
ID       Rule                                                     Status

BR-      Mahasiswa yang ditolak dapat mengajukan kembali          CONFIRMED (ditegaskan ulang
009      sebagai pengajuan baru saat kesempatan/kapasitas         #10)
         tersedia; histori lama tetap tersimpan.

BR-      Pengunduran diri setelah status Diterima                 CONFIRMED (ditegaskan ulang
010      memerlukan surat resmi dari kampus dengan alasan         #11)
         jelas & profesional, diverifikasi Sekretariat, sebelum
         status berubah menjadi "Mengundurkan Diri".

BR-011 Dokumen versi lama (saat revisi) tidak dihapus,            PROPOSED (tidak berubah)
       disimpan sebagai histori.

BR-      Penolakan pengajuan dicatat sebagai data (status         CONFIRMED (#1)
012       Ditolak + alasan wajib) dan dikirim sebagai
(baru)   notifikasi ke mahasiswa. Tidak ada dokumen/surat
         penolakan formal yang wajib diterbitkan.

BR-      Mahasiswa tidak perlu memberikan                         CONFIRMED (#5)
013      konfirmasi/acceptance setelah dinyatakan diterima.
(baru)   Begitu Sekretariat menetapkan penerimaan &
         penempatan final, status langsung menjadi
          Diterima tanpa status antara "menunggu
         konfirmasi".

BR-      "Sekretariat" dalam konteks sistem adalah satu peran     CONFIRMED (#2, #3)
014      operasional yang mencakup Kasubag Umum &
(baru)   Kepegawaian beserta staf yang menjalankan fungsi
         administrasi dan verifikasi (termasuk fungsi yang
         sebelumnya disebut "Pelaksana Teknis"). Sekretaris
         (posisi individu) dan Kepala Badan bukan aktor/role
         di dalam sistem — keduanya berperan dalam proses
         administrasi fisik di luar sistem (BR-007).

BR-      Dokumen resmi (surat penerimaan, dokumen akhir           CONFIRMED (#4)
015      magang) dibuat dan diproses secara administratif
(baru)   oleh Bappeda di luar sistem. Setelah final,
         Sekretariat mengunggahnya ke sistem, dan
         mahasiswa mendapat akses untuk
         melihat/mengunduh.
10. Stakeholders & Actor (Updated)
 Aktor                         Status di Sistem    Peran

 Mahasiswa                     User aktif sistem   Mengajukan, memantau status,
                                                   mengunggah dokumen, mengunduh
                                                   dokumen final

 Sekretariat (Kasubag Umum     User aktif sistem   Memeriksa kelengkapan, memberi revisi,
 & Kepegawaian + staf                              mengecek kebutuhan divisi, menetapkan
 administrasi/verifikasi)                          penempatan & keputusan, mencatat
                                                   data pelaksanaan magang, mengunggah
                                                   dokumen final

 Admin Sistem                  User aktif sistem   Role teknis pengelolaan sistem: kelola master data
                                                   (divisi & status kebutuhan) dan akun/role user internal;
                                                   bukan pengambil keputusan penerimaan/penempatan

 Sekretaris (posisi individu   Bukan user sistem   Paraf dokumen secara fisik di luar sistem
 dalam rantai paraf)                               (BR-007)

 Kepala Badan (Kepala          Bukan user sistem   Menandatangani dokumen resmi secara
 Bappeda)                                          fisik di luar sistem (BR-007)

 Divisi/Bidang terkait         Bukan user sistem   Memberi info kebutuhan/ketersediaan
                               (PROPOSED           secara manual (telepon/langsung) kepada
                               default, tidak      Sekretariat, dicatat manual oleh Sekretariat
                               dibantah oleh       ke sistem
                               konfirmasi)


Catatan: penyederhanaan ini menjawab 3 dari 5 pertanyaan P0 di PRD v3.0 (lihat Part C
untuk sisa pertanyaan yang benar-benar masih terbuka).
11. Proposed To-Be Process (Updated)

 Mahasiswa (online) → Submit Pengajuan Individual (surat lamaran + CV +
 transkrip [+opsional])
         ↓
 Sekretariat memeriksa kelengkapan
         ↓
    [Kurang lengkap] → Perlu Revisi → Mahasiswa perbaiki → kembali diperiksa
         ↓
    [Lengkap] → Sekretariat mengecek kebutuhan/kapasitas ke divisi terkait
 (manual, di luar sistem)
         ↓
    Sekretariat menetapkan keputusan:
         ├─ DITERIMA (termasuk penempatan final, boleh berbeda dari
 preferensi,
         │              tanpa perlu approval ulang mahasiswa) → status langsung
 "Diterima"
         │              (TIDAK ADA status "menunggu konfirmasi mahasiswa")
         │
         └─ DITOLAK → alasan dicatat wajib → notifikasi ke mahasiswa
                        (TIDAK ADA surat penolakan formal)
         ↓ (jalur Diterima)
    [Di luar sistem] Bappeda menyiapkan surat penerimaan → proses
 administrasi/paraf/TTD fisik
         ↓
    Dokumen final selesai → Sekretariat mengunggah ke sistem
         ↓
    Mahasiswa mengunduh surat penerimaan
         ↓
    Pelaksanaan magang: Sekretariat mencatat data pembina/penanggung jawab,
 tanggal, status
         ↓
    Selesai magang → [Di luar sistem] Bappeda menyiapkan dokumen akhir (surat
 keterangan
         penilaian, selesai magang, pengembalian peserta) → proses
 administrasi/paraf/TTD fisik
         ↓
    Dokumen final → Sekretariat mengunggah ke sistem → Mahasiswa mengunduh
         ↓
    [Diterima lalu mengundurkan diri] → mahasiswa unggah surat pengunduran
 diri resmi dari
         kampus (alasan jelas & profesional) → Sekretariat verifikasi → status
 "Mengundurkan Diri"
12. Status Lifecycle (Updated & Disederhanakan)
Status          Trigger           Actor         Next State         Visible to User

Draft           Mahasiswa         Mahasiswa     Diajukan /         Ya
                mulai isi form                  Dibatalkan
                                                (Draft)

Diajukan        Submit            Mahasiswa     Diperiksa          Ya

Diperiksa       Otomatis          Sekretariat   Perlu Revisi / Cek Ya
                masuk antrean                   Kebutuhan Divisi
                Sekretariat                     / Ditolak

Perlu Revisi    Sekretariat       Sekretariat   Diajukan           Ya
                menandai                        (setelah submit
                kurang/salah,                   ulang) /
                catatan wajib                   Dibatalkan
                                                (Belum
                                                Diproses)

Cek Kebutuhan   Berkas lengkap    Sekretariat   Diterima /         Ya (label umum)
Divisi                                          Ditolak

Diterima        Sekretariat       Sekretariat   Sedang Magang /    Ya. Dokumen surat
                menetapkan                      Mengundurkan       penerimaan tersedia
                penerimaan                      Diri               sebagai atribut tambahan
                dan                                                ( dokumen_tersedia:
                penempatan                                         ya/tidak ) begitu
                final sekaligus                                    Sekretariat
                                                                   mengunggahnya — bukan
                                                                   status/state terpisah.

Ditolak         Sekretariat       Sekretariat   (status akhir;     Ya, disertai alasan. Tanpa
                menetapkan                      dapat re-apply →   dokumen surat.
                penolakan                       pengajuan baru)

Sedang Magang   Tanggal mulai     Sekretariat   Selesai /          Ya
                tercapai                        Mengundurkan
                                                Diri

Mengundurkan    Mahasiswa         Mahasiswa     (status akhir)     Ya
Diri            unggah surat      →
                pengunduran       Sekretariat
                diri resmi,
                diverifikasi
                Sekretariat
 Status          Trigger           Actor         Next State       Visible to User

 Selesai         Tanggal selesai   Sekretariat   (status          Ya. Dokumen akhir
                 tercapai                        akhir/arsip)     magang tersedia sebagai
                                                                  atribut tambahan begitu
                                                                  diunggah — bukan status
                                                                  terpisah.

 Dibatalkan      Mahasiswa         Mahasiswa     (status akhir)   Ya
 (Draft)         batalkan
                 sebelum
                 submit

 Dibatalkan      Mahasiswa         Mahasiswa     (status akhir)   Ya
 (Belum          batalkan
 Diproses)       sebelum
                 keputusan final


Perubahan kunci dari v3.0: status Ditempatkan , Menunggu Tanda Tangan , dan Surat
Terbit dihapus. Ketersediaan dokumen kini adalah atribut pada status
 Diterima / Selesai , bukan transisi status tersendiri — karena proses paraf/TTD tidak lagi
dilacak sistem (BR-007). Penempatan pada divisi preferensi mahasiswa langsung
berstatus Diterima; penempatan pada divisi lain ditawarkan lebih dulu dan harus
disetujui mahasiswa (BR-013, direvisi pada v4.2).

13. Detailed User Flow — Mahasiswa (Updated)
Registrasi → Verifikasi email → Login → Lengkapi profil → Ajukan magang (preferensi divisi
+ 3 dokumen wajib + opsional) → Submit → Pantau status → (bila diminta) perbaiki &
submit ulang → menerima keputusan: Diterima (dengan penempatan final) atau
Ditolak (dengan alasan); bila Sekretariat menawarkan divisi di luar preferensinya,
mahasiswa menyetujui atau menolak tawaran itu lebih dulu (v4.2) → (bila Diterima)
menunggu & mengunduh surat penerimaan begitu
Sekretariat mengunggahnya → menjalani magang → mengunduh dokumen akhir begitu
tersedia → (bila ingin mengundurkan diri, kapan pun setelah diterima) unggah surat
pengunduran diri resmi dari kampus.
14. Detailed User Flow — Sekretariat (Updated)
Login → Lihat daftar pengajuan (dashboard + filter) → Periksa kelengkapan → Tandai "Perlu
Revisi" (dengan catatan) atau lanjutkan → Cek kebutuhan/ketersediaan divisi (manual, di
luar sistem) → Input hasil pengecekan → Tetapkan keputusan langsung:
     Diterima (sekaligus tentukan penempatan final) → sistem langsung set status
     "Diterima", tanpa langkah generate surat dari sistem
     Ditolak (alasan wajib) → sistem set status "Ditolak", kirim notifikasi, tanpa membuat
     dokumen surat
→ (Di luar sistem) Bappeda menyiapkan & memproses surat penerimaan secara
administratif → Sekretariat mengunggah dokumen final ke sistem → mahasiswa dapat
mengunduh → Selama magang, Sekretariat mencatat data pelaksanaan → Saat selesai, (di
luar sistem) Bappeda menyiapkan dokumen akhir yang memang diterbitkan (misalnya surat
keterangan/penilaian, surat selesai/bukti magang, sertifikat jika diterbitkan, atau dokumen
lainnya) → Sekretariat mengunggah dokumen final → mahasiswa dapat mengunduh.

15. Functional Requirements (Updated — hanya FR yang berubah/dihapus
ditampilkan detail; FR lain dari v3.0 tetap berlaku tanpa perubahan)
 ID         Status      Requirement                                       Actor         Priority

 FR-003     DIHAPUS     Mencatat status paraf per tahap — tidak relevan   —             —
                        lagi, paraf di luar sistem

 FR-018     DIREVISI    Sekretariat dapat mengunggah dokumen final        Sekretariat   P0
 (revisi)               (surat penerimaan) yang telah selesai diproses
                        secara administratif di luar sistem, terhubung
                        ke pengajuan mahasiswa terkait

 FR-019     DIHAPUS     Generate draf daftar nominatif — proses           —             —
            dari MVP    administratif di luar sistem

 FR-020     DIREVISI    Sekretariat dapat mengunggah dokumen akhir        Sekretariat   P0
 (revisi)               magang (surat keterangan penilaian, surat
                        keterangan selesai magang, surat
                        pengembalian peserta) setelah status "Selesai"

 FR-016     DIREVISI    Sekretariat menetapkan keputusan Diterima         Sekretariat   P0
 (revisi    redaksi     (sekaligus penempatan final) atau Ditolak
 kecil)                 (dengan alasan wajib) dalam satu aksi — sistem
                        langsung mengubah status tanpa status antara

 FR-031     BARU        Mahasiswa dapat mengunduh dokumen final           Mahasiswa P0
 (baru)                 (surat penerimaan / dokumen akhir magang)
                        begitu status dokumen_tersedia = ya pada
                        pengajuannya

 FR-032     BARU        Sistem mengirim notifikasi ke mahasiswa saat      Sistem        P0
 (baru)                 status berubah menjadi Ditolak , memuat
                        alasan penolakan, tanpa lampiran dokumen
                        surat


Seluruh FR lain dari PRD v3.0 (FR-001, 002, 004–017, 021–030) tetap berlaku tanpa
perubahan — sudah konsisten dengan 12 konfirmasi final.
16. Role & Permission Matrix (Updated — Disederhanakan)
 Feature                                           Mahasiswa Sekretariat Admin
                                                                         Sistem

 Ajukan magang                                     ✓ (create)   –        –

 Lihat pengajuan sendiri                           ✓            –        –

 Lihat semua pengajuan                             –            ✓        ✓

 Tandai revisi/kelengkapan                         –            ✓        –

 Input kebutuhan divisi & penempatan               –            ✓        –

 Tetapkan keputusan (Diterima/Ditolak)             –            ✓        –

 Unggah dokumen final (surat penerimaan, dokumen   –            ✓        –
 akhir)

 Unduh dokumen final milik sendiri                 ✓            –        –

 Input data pelaksanaan magang                     –            ✓        –

 Verifikasi surat pengunduran diri                 –            ✓        –

 Laporan & export                                  –            ✓        ✓

 Kelola master data (divisi & status kebutuhan)    –            –        ✓

 Kelola akun & role user internal                  –            –        ✓



Tabel ini menggantikan matriks 6-role di v3.0 sepenuhnya — Sekretaris dan Kepala Badan
tidak lagi muncul karena bukan user sistem (BR-014).
17. Document Requirements (Updated)
Dokumen              Mandatory?               Sumber di Sistem       Aktor         Tahap

Surat Lamaran        Wajib                    Upload                 Mahasiswa Pengajuan

CV                   Wajib                    Upload                 Mahasiswa Pengajuan

Transkrip Nilai      Wajib                    Upload                 Mahasiswa Pengajuan

Dokumen/Surat        Opsional                 Upload                 Mahasiswa Pengajuan
Tambahan

Surat Penerimaan     Wajib bila Diterima      Upload dokumen         Sekretariat   Setelah
(final)                                       final (dibuat &                      Diterima
                                              diproses di luar
                                              sistem)

Surat Penolakan      DIHAPUS — tidak          —                      —             —
                     diwajibkan (BR-012)

Surat Keterangan     Sesuai dokumen yang      Upload dokumen         Sekretariat   Selesai
Penilaian             diterbitkan Bappeda      final

Surat Keterangan     Sesuai dokumen yang      Upload dokumen         Sekretariat   Selesai
Selesai Magang        diterbitkan Bappeda      final

Surat Pengembalian  Jika diterbitkan        Upload dokumen         Sekretariat   Selesai
Peserta              oleh Bappeda            final

Sertifikat           Jika diterbitkan        Upload dokumen         Sekretariat   Selesai
                     oleh Bappeda            final

Surat Pengunduran    Wajib bila               Upload (alasan jelas   Mahasiswa Post-
Diri (dari kampus)   mengundurkan diri        & profesional,                   Decision
                                              diverifikasi
                                              Sekretariat)

Daftar Nominatif     DIHAPUS dari cakupan     —                      —             —
                     sistem — proses
                     administratif internal
                     Bappeda


Catatan penting: untuk seluruh dokumen "Upload dokumen final", termasuk surat penerimaan dan dokumen akhir seperti surat selesai/penilaian atau sertifikat jika diterbitkan, sistem tidak
membuat/men-generate draf. Sistem hanya menjadi tempat penyimpanan dan distribusi
dokumen yang sudah final dari proses administratif Bappeda (BR-015).
18. Notification Requirements (Updated)
Event                 Recipient   Isi                   Perubahan

Submit pengajuan      Mahasiswa Konfirmasi diterima     Tidak berubah
                                sistem

Perlu revisi          Mahasiswa Catatan revisi          Tidak berubah

Diterima              Mahasiswa Keputusan diterima +    Tidak ada notifikasi
                                info penempatan final   "menunggu konfirmasi" —
                                                        langsung notifikasi hasil akhir

Ditolak               Mahasiswa Alasan penolakan,       Ditegaskan: tanpa surat (BR-
                                tanpa lampiran          012)
                                dokumen

Dokumen tersedia      Mahasiswa Surat penerimaan /      Menggantikan notifikasi
(baru, menggantikan             dokumen akhir magang    "surat terbit" dari v3.0 yang
"surat terbit")                 siap diunduh            terkait tracking TTD

Status pelaksanaan    Mahasiswa Info status magang      Tidak berubah
dimulai/selesai

Pengunduran diri      Mahasiswa Konfirmasi status       Tidak berubah
terverifikasi                   berubah
19. Edge Cases (Updated — hanya perubahan ditampilkan; nomor mengacu
ke tabel 25 skenario v3.0)
 #        Skenario                     Perubahan di v4.0
 (v3.0)

 6        Mahasiswa tidak melakukan    Tidak berubah — tetap tanpa batas waktu otomatis
          revisi                       (masih ⚠️ NEED CONFIRMATION minor)

 8        Alternatif divisi tersedia   Direvisi v4.2: penempatan pada divisi preferensi
                                       langsung berstatus Diterima; penempatan pada divisi
                                       lain ditawarkan lebih dulu dan harus disetujui
                                       mahasiswa (BR-005, BR-013)

 13       Mahasiswa ditolak            Direvisi: alasan dicatat & dinotifikasi, tanpa proses
                                       pembuatan surat penolakan sama sekali

 15       Mahasiswa mengundurkan       Tidak berubah secara alur, hanya ditegaskan syarat
          diri setelah diterima        "alasan jelas & profesional" (BR-010, konfirmasi #11)

 20       Dokumen akhir belum          Direvisi: mahasiswa melihat status "Selesai" dengan
          tersedia                     atribut dokumen_tersedia: tidak — tanpa
                                       status/substatus "menunggu tanda tangan" yang
                                       eksplisit ditampilkan

 (baru)   Sekretariat menetapkan       Status tetap "Diterima"; mahasiswa melihat pesan
 26       Diterima tapi belum sempat   "dokumen sedang diproses Bappeda", bukan status
          unggah surat penerimaan      terpisah

 (baru)   Mahasiswa bertanya kenapa    Sistem cukup menampilkan alasan penolakan sebagai
 27       tidak ada surat penolakan    teks di halaman status — sesuai BR-012, ini bukan
                                       bug/gap, melainkan desain yang dikonfirmasi


Seluruh 25 skenario lain dari v3.0 (Bagian 34) tetap berlaku tanpa perubahan substansial.

20. Acceptance Criteria (Updated)
AC-05 (revisi dari v3.0 — Pengunduran Diri): Given mahasiswa berstatus "Diterima" atau
"Sedang Magang", When mahasiswa mengunggah surat pengunduran diri resmi dari
kampus dengan alasan jelas & profesional dan Sekretariat memverifikasinya, Then status
berubah menjadi "Mengundurkan Diri".
AC-06 (tidak berubah — Penempatan Alternatif): Given preferensi divisi mahasiswa
sedang tidak tersedia, When Sekretariat menempatkan ke divisi lain yang sesuai &
tersedia, Then sistem mencatat penempatan final tanpa memerlukan persetujuan
tambahan dari mahasiswa.
AC-07 (baru) — Penolakan tanpa Surat: Given Sekretariat menetapkan keputusan Ditolak
dengan alasan terisi, When keputusan disimpan, Then status pengajuan berubah menjadi
"Ditolak", notifikasi berisi alasan dikirim ke mahasiswa, dan sistem tidak
membuat/mewajibkan dokumen surat apa pun untuk status ini.
AC-08 (baru) — Diterima Langsung Tanpa Konfirmasi: Given Sekretariat menetapkan
keputusan Diterima dan penempatan final, When keputusan disimpan, Then status
pengajuan langsung menjadi "Diterima" tanpa status antara yang menunggu
tindakan/konfirmasi dari mahasiswa.
AC-09 (baru) — Unggah Dokumen Final: Given status pengajuan adalah "Diterima" atau
"Selesai", When Sekretariat mengunggah dokumen final terkait (surat penerimaan /
dokumen akhir magang), Then atribut dokumen_tersedia pada pengajuan tersebut
berubah menjadi "ya" dan mahasiswa dapat mengunduhnya, tanpa perubahan status
lifecycle.
AC-01 s.d. AC-04, AC-02 (dari v2.0/v3.0): tetap berlaku tanpa perubahan.

21. Requirement Traceability Matrix (Updated)
 Business Rule      Functional Requirement                         Acceptance Criteria

 BR-001, BR-002     FR-001, FR-016                                 AC-01

 BR-003             FR-002, FR-016                                 —

 BR-005, BR-013     FR-002, FR-015, FR-016                         AC-06, AC-08

 BR-007, BR-015     FR-018, FR-020, FR-031                         AC-09

 BR-008             FR-001, Bagian 23 (Validation, v3.0)           —

 BR-009             FR-017                                         —

 BR-010             FR-022                                         AC-05

 BR-012             FR-016, FR-032                                 AC-07

 BR-014             Bagian 10 (Actor), Bagian 16 (Role Matrix)     —



C. SISA OPEN QUESTIONS (Setelah Rekonsiliasi)
Dari 5 pertanyaan P0 di PRD v3.0, 4 sudah RESOLVED oleh 12 konfirmasi ini:
 Pertanyaan P0 v3.0             Status

 Mekanisme formal penolakan     ✅ RESOLVED (Konfirmasi #1 — BR-012)

 Apakah Sekretaris & Kepala     ✅ RESOLVED (Konfirmasi #2, #3 — tidak butuh, BR-014)
 Badan butuh akun sistem

 PIC persis langkah 10–13 SOP   ✅ TIDAK RELEVAN LAGI — seluruh rantai paraf/TTD di luar
                                sistem, sistem tidak perlu tahu PIC per tahap (Konfirmasi #3)

 Apakah divisi punya akses      ✅ RESOLVED secara implisit (default: tidak, dicek manual oleh
 sistem sendiri                 Sekretariat — konsisten dengan Konfirmasi #12, tidak dibantah)


Yang benar-benar masih terbuka (minor, tidak menghalangi desain inti — level
parameter teknis/operasional, bukan fondasi bisnis):

 # Pertanyaan                    Kenapa Masih Terbuka          Dampak jika Belum Dijawab

 1   Format & ukuran maksimal    Tidak disebutkan di 12        Rendah — dapat memakai default
     file dokumen upload         konfirmasi maupun SOP         umum (PDF, maks 2MB) saat
     (PDF/JPG/PNG? Berapa                                      SRS, disesuaikan kemudian
     MB?)                                                      tanpa mengubah alur bisnis

 2   Apakah mahasiswa boleh      Tidak disebutkan eksplisit    Rendah — dapat dimulai tanpa
     memiliki lebih dari satu                                  pembatasan otomatis, dipantau
     pengajuan aktif secara                                    manual oleh Sekretariat,
     bersamaan                                                 disesuaikan bila terbukti
                                                               bermasalah

 3   Batas waktu mahasiswa       Tidak disebutkan eksplisit    Rendah — tanpa batas waktu
     merespons permintaan        (Konfirmasi #8 hanya          otomatis pada v1.0 tidak
     revisi                      bicara soal waktu             mengganggu jalannya proses inti
                                 pengajuan awal, bukan
                                 revisi)

 4 Kebijakan retensi data        Tidak dibahas                 Rendah — belum ditentukan dan
   pengajuan yang                                              perlu ditetapkan berdasarkan
   ditolak/selesai                                             kebijakan pengelolaan data Bappeda


Keempat poin ini bersifat parameter teknis/operasional minor yang lazim diputuskan pada
tahap SRS/implementasi, bukan pertanyaan fondasi bisnis — sehingga tidak menghalangi
PRD ini dianggap siap secara business-requirement level.
D. FINAL READINESS ASSESSMENT (Updated)
 Dimensi          Status v3.0    Status v4.0      Catatan

 Business         🟡 Needs        🟢 Ready          Jalur penerimaan & penolakan kini
 Process          Confirmation                    sama-sama jelas (BR-012, BR-013)

 Business Rules   🟢 Ready        🟢 Ready          BR-001–BR-015 solid & final

 User Roles       🟡 Needs        🟢 Ready          Disederhanakan jadi 3 role sistem (BR-
                  Confirmation                    014), tidak ada lagi ambiguitas

 Workflow         🟡 Needs        🟢 Ready          To-Be process penuh dari pengajuan
                  Confirmation                    sampai dokumen akhir sudah konsisten

 Functional       🟢 Ready        🟢 Ready          FR diperbarui, tidak ada lagi
 Requirements                                     requirement yang bertentangan dengan
                                                  proses nyata

 Data             🟢 Ready        🟢 Ready          Tidak berubah, tetap konsisten
 Requirements

 Security         🟡 Needs        🟡 Needs          Hanya parameter teknis (format file,
                  Confirmation   Confirmation     retensi) — lihat Part C
                                 (minor)

 NFR              🔴 Blocked      🟡 Needs          Target performa/availability tetap belum
                  (sebagian)     Confirmation     ada dasar resmi, tapi ini parameter SRS,
                                 (minor)          bukan blocker bisnis

 Edge Cases       🟢 Ready        🟢 Ready          Diperbarui, konsisten dengan seluruh
                                                  keputusan final

 Open Questions   🟡 Needs        🟢 Ready (minor   Dari 5 P0 → 0 P0 tersisa; hanya 4 item
                  Confirmation   items tersisa)   teknis minor non-blocking


Kesimpulan Akhir: PRD READY.
Seluruh fondasi bisnis (business process, business rules, role, workflow, functional
requirements) sudah CONFIRMED dan konsisten berdasarkan 12 keputusan final. Tidak
ada lagi pertanyaan P0/blocker yang menghalangi PRD ini dipakai sebagai acuan tahap
SRS, UI/UX, dan desain database. Empat poin yang tersisa di Part C bersifat parameter
teknis/operasional minor (format file, kebijakan multi-pengajuan, batas waktu revisi,
retensi data) yang lazim diputuskan pada tahap SRS tanpa perlu menahan PRD ini di level
"belum siap".


---
## V4.1 — FINAL MICRO-REVISION NOTES

Perubahan yang dikunci:
1. Individual application berarti setiap pengajuan diproses individual; satu mahasiswa tetap dapat memiliki riwayat beberapa pengajuan/re-apply.
2. Paraf, disposisi, dan tanda tangan tidak dikelola di dalam sistem. Sistem hanya menyimpan dan mendistribusikan dokumen final.
3. Sekretariat tetap menjadi satu role operasional yang mencakup Kasubag Umum & Kepegawaian beserta staf administrasi/verifikasi.
4. Admin Sistem berperan sebagai super admin: selain mengelola master data dan akun, berwenang menjalankan seluruh fungsi Sekretariat termasuk memutuskan penerimaan dan penempatan. (Direvisi pada v4.2; pada v4.1 butir ini menyatakan Admin sebagai role teknis yang tidak mengambil keputusan.)
5. Dokumen akhir mengikuti dokumen yang benar-benar diterbitkan Bappeda. Sertifikat dicantumkan hanya jika diterbitkan; Surat Pengembalian Peserta tidak dipaksakan sebagai dokumen wajib bila tidak diterbitkan.
6. Retensi data belum ditentukan; tidak dibuat asumsi penyimpanan tanpa batas waktu.
7. Tidak ada perubahan terhadap alur inti yang telah dikonfirmasi: pengajuan individual → verifikasi → revisi bila perlu → cek kebutuhan divisi → keputusan dan penempatan final → dokumen final diproses di luar sistem → Sekretariat upload → mahasiswa download.

---

## V4.2 — PENYESUAIAN DENGAN SISTEM YANG BERJALAN

Ditulis 20 Agustus 2026. Dasar perubahan: keputusan tim pada 19 Agustus 2026,
yang sebelumnya sudah diambil tetapi belum pernah dituangkan ke dokumen ini.

Selama dua butir di bawah belum diperbarui, keduanya berulang kali muncul
sebagai temuan audit "kode tidak sesuai PRD" — padahal yang tertinggal adalah
dokumennya, bukan kodenya.

**Perlu persetujuan pemilik PRD sebelum dianggap final.**

### 1. Admin adalah super admin, bukan role teknis

| | |
|---|---|
| **Semula (v4.1)** | Admin Sistem adalah role teknis, tidak mengambil keputusan penerimaan maupun penempatan. |
| **Menjadi (v4.2)** | Admin berwenang menjalankan seluruh fungsi Sekretariat, termasuk memutuskan penerimaan dan menetapkan penempatan. |
| **Alasan** | Bappeda menghendaki satu akun yang tetap dapat memproses pengajuan bila Sekretariat berhalangan, tanpa perlu membuat role baru. |
| **Terdampak** | Bagian 10 (Actor), Bagian 16 (Role Matrix), catatan penutup v4.1 butir 4, BR-014. |

Jumlah role login **tidak bertambah**: tetap tiga — mahasiswa, sekretariat,
admin. Yang berubah hanya kewenangan Admin. Larangan membuat role berjenjang
seperti "Kepala" atau "Verifikator" tetap berlaku sepenuhnya.

### 2. Penempatan di luar preferensi harus disetujui mahasiswa

| | |
|---|---|
| **Semula (v4.1)** | Preferensi divisi tidak mengikat. Sekretariat menetapkan divisi final, status langsung Diterima, tanpa persetujuan balik mahasiswa. |
| **Menjadi (v4.2)** | Penempatan pada divisi **preferensi** tetap langsung berstatus Diterima. Penempatan pada divisi **lain** ditawarkan lebih dulu, dan mahasiswa menyetujui atau menolaknya sebelum ditetapkan. |
| **Alasan** | Menempatkan mahasiswa di divisi yang tidak ia pilih tanpa sepengetahuannya menimbulkan pembatalan di kemudian hari. Menawarkan lebih dulu menyelesaikannya sebelum keputusan dikunci. |
| **Terdampak** | Bagian 12 (Status Lifecycle), Bagian 13 (User Flow Mahasiswa), Bagian 14 (User Flow Sekretariat), BR-005, BR-013. |

Semangat BR-005 tidak berubah: **preferensi tetap tidak mengikat** dan
Sekretariat tetap pemegang keputusan penempatan. Yang ditambahkan hanya satu
langkah persetujuan, dan hanya bila penempatannya menyimpang dari preferensi.

Dua status baru menyertainya:

| Status | Arti |
|---|---|
| `menunggu_konfirmasi_tawaran` | Sekretariat sudah menawarkan divisi alternatif, menunggu jawaban mahasiswa |
| `menunggu_finalisasi_sekretariat` | Mahasiswa sudah menyetujui, menunggu Sekretariat mengunci penempatannya |

Bila mahasiswa menolak tawaran, pengajuannya berhenti dengan status
`dibatalkan_oleh_mahasiswa`. Statusnya termasuk status akhir yang membuka kembali
hak mengajukan, sehingga mahasiswa dapat mendaftar ulang dari awal sebagai
pengajuan baru tanpa menghapus riwayat lamanya.

### Yang tidak berubah

Seluruh butir lain pada v4.1 tetap berlaku apa adanya, termasuk: pengajuan
bersifat individual, pendaftaran bergulir tanpa periode global, kapasitas divisi
hanya bersifat informasi, paraf dan tanda tangan di luar sistem, penolakan wajib
disertai alasan, serta versioning dokumen saat revisi.

