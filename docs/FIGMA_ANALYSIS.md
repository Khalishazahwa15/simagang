# FIGMA VISUAL ANALYSIS — SIMAGANG BAPPEDA LAMPUNG (KOREKSI FINAL)

## 1. Visual Source of Truth
Figma Make (beserta tangkapan layar yang diberikan) adalah acuan tunggal untuk *Visual Direction*. 
Meskipun Figma mungkin tidak menyertakan seluruh halaman yang diminta PRD (seperti form pengajuan lengkap, form revisi, tabel peserta aktif, atau upload dokumen akhir), **desain harus diperluas secara konsisten dari bahasa visual yang sudah ada**, BUKAN menggunakan template generik.

## 2. Larangan Estetika
JANGAN MENGGUNAKAN:
- Glassmorphism.
- Gradient berlebihan atau efek neon.
- Shadow yang tebal/floating.
- Card dengan sudut melengkung berlebihan (giant rounded corners).
- Heading raksasa yang merusak hierarki.
- Template dashboard generik seperti AdminLTE atau Bootstrap default tanpa modifikasi menyeluruh.

## 3. Komponen Inti & Bahasa Visual
- **Typography**: Sans-serif yang sangat clean (Inter/Roboto) untuk UI. Hierarki teks sangat terjaga dan proporsional.
- **Warna Utama**: Deep Teal / Dark Green sebagai identitas institusi. Warna pendukung (Warm Gold, Cream/Off-white, White) untuk layouting, dan Muted Gray untuk teks minor.
- **Layout Global**: Background terang (Off-white), Card putih solid dengan border abu-abu sangat tipis.
- **Navbar/Sidebar**: 
  - Navbar publik: Clean, light/cream background.
  - Sidebar aplikasi (semua role): Dark Teal background, konsisten secara struktur (logo di atas, info user, menu aktif ter-highlight, tombol keluar di bawah).
- **Timeline**: Digunakan sebagai indikator proses. Horizontal untuk flow tahap utama (Pengajuan → Verifikasi → Keputusan → Magang → Selesai). Vertikal untuk log/riwayat aktivitas yang mendetail.
- **Form & Input**: Clean outline inputs, tidak menggunakan pill-shape untuk form standar, validasi kontekstual.
- **Tabel**: Header tabel jelas, pencarian dan filter di bagian atas, pagination di bagian bawah.

## 4. Penanganan Halaman Ekstra (Perluasan Desain)
Untuk halaman operasional Sekretariat dan Admin yang tidak ada di referensi Figma secara spesifik, aturan berikut diterapkan:
- Tampilan detail verifikasi dokumen harus bergaya card clean, dengan split view (informasi di kiri, dokumen di kanan) tanpa mengubah hierarki warna.
- Form "Alasan Penolakan" atau "Catatan Revisi" akan menggunakan modal atau inline card yang mengikuti gaya form standar Figma.
- Laman "Pengunduran Diri" akan menggunakan komponen File Upload yang persis sama gaya/styling-nya dengan halaman unggah dokumen awal Mahasiswa.
