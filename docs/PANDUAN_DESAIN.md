# PANDUAN DESAIN SIMAGANG

Arah visual antarmuka: apa yang menjadi acuan, apa yang dilarang, dan bagaimana
memperluasnya ke halaman yang belum ada di rancangan awal.

## 1. Acuan Visual
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

## 5. Berkas CSS dan Cara Membangun Ulang

Halaman memuat lima berkas, berurutan:

| Berkas | Isi |
|---|---|
| `tokens.css` | Sumber tunggal warna, huruf, jarak, radius, bayangan, z-index |
| `layout.css` | Kerangka halaman: sidebar, topbar, grid |
| `components.css` | Kartu, tabel, formulir, lencana status, halaman informasi publik |
| `responsive.css` | Penyesuaian per lebar layar |
| `tailwind.css` | Utility Tailwind, dibangun lebih dulu (bukan CDN) |

`tailwind.css` adalah hasil build, **bukan berkas untuk disunting tangan**.
Warna di dalamnya diturunkan otomatis dari `tokens.css`, jadi mengubah warna
cukup di `tokens.css` lalu bangun ulang:

```bash
npx @tailwindcss/cli@4.3.3 -i input.css -o public/assets/css/tailwind.css --minify
```

`input.css` berisi `@import "tailwindcss"`, `@source` yang menunjuk
`app/Views`, dan blok `@theme` hasil salinan seluruh token `--color-*` dari
`tokens.css`. Berkas itu tidak disimpan di repositori karena hanya dipakai
sesaat saat membangun; proyek ini sengaja tidak memuat `package.json`.

Palet netral (teks, garis, langkah tidak aktif) dan warna teks status mengikuti
[INA Digital Design System](https://design.inadigital.go.id/). Biru dan emas
institusi ditetapkan tim sendiri.

### Tiga tingkat ketebalan huruf

| Bobot | Dipakai untuk |
|---|---|
| 400 | Badan teks, isi tabel, keterangan |
| 500 | Label, kepala tabel, tautan navigasi, tombol |
| 600 | Judul dan nama instansi |

Bobot 700 dan 800 tidak dipakai. Judul cukup dibedakan lewat 600 dan ukuran;
menaikkannya lebih jauh membuat halaman terasa berat dan justru menghapus
perbedaan antara judul dan isi.

`tailwind.css` mengimpor preflight ke dalam `@layer base` supaya kalah dari CSS
proyek yang tidak berlapis, sementara utility sengaja dibiarkan tanpa lapisan
agar tetap menang atas aturan elemen. Tanpa pengaturan itu preflight menimpa
seluruh aturan dasar `tokens.css`, dan `h1` polos akan dirender 16px alih-alih
mengikuti `--text-h1`.

### Ambang kontras

`tests/TestJalurRusak.php` memeriksa rasio kontras setiap pasangan warna teks
terhadap latarnya dan menolak nilai di bawah 4.5 (WCAG AA). Warna aksen hanya
boleh dipakai untuk latar dan garis, tidak untuk teks. Jalankan tes itu setiap
kali menyentuh `tokens.css`.

## 6. Aset Gambar

Seluruh gambar disimpan sendiri di `public/assets/img/`. Tidak ada gambar yang
ditarik dari host luar; sebelumnya beranda memuat foto dari `images.unsplash.com`
dan foto itu gagal dimuat.

| Berkas | Isi | Sumber dan status |
|---|---|---|
| `logo-lampung.png` | Lambang Provinsi Lampung, 320x460 | Situs resmi Bappeda Provinsi Lampung. Domain publik menurut Pasal 43 UU 28/2014 tentang Hak Cipta, karena merupakan lambang yang diterbitkan pemerintah |
| `logo-lampung-kecil.png` | Versi 96x138 untuk navbar dan sidebar | sama |
| `menara-siger.jpg` | Menara Siger di Bakauheni, 1600x717 | Wikimedia Commons, `Port of Bakauheni and Siger Tower.JPG` oleh Sakurai Midori, lisensi CC BY 2.5 |

Foto Menara Siger dipotong dari berkas aslinya untuk membuang papan iklan
komersial yang ada di bagian bawah bingkai.

**CC BY 2.5 mewajibkan atribusi.** Kredit fotonya tercantum di baris bawah
footer beranda dan tidak boleh dihapus selama foto itu masih dipakai. Bila foto
diganti dengan milik Bappeda sendiri, baris kredit itu ikut dihapus.

Keterangan di atas foto diletakkan pada bilah berwarna solid, bukan di atas
fotonya langsung. Dengan begitu kontrasnya tidak bergantung pada isi gambar:
diukur 9.12, dan tetap sama bila fotonya diganti.
