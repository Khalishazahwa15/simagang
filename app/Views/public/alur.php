<?php
// Extracted and derived from Figma Make CSS and PRD v4.1

$alurSteps = [
    [
        'num' => '01',
        'title' => 'Buat Akun & Masuk',
        'subtitle' => 'Registrasi & Autentikasi',
        'desc' => 'Daftarkan akun menggunakan email aktif dan Nomor Induk Mahasiswa (NIM/NPM). Setelah verifikasi email, Anda dapat masuk ke portal SIMAGANG.',
        'detail' => [
            'Email aktif yang dapat dihubungi',
            'NIM/NPM dari institusi Anda',
            'Kata sandi minimal 8 karakter',
        ],
        'badge' => 'Publik',
        'duration' => '5 menit',
    ],
    [
        'num' => '02',
        'title' => 'Isi Formulir Pengajuan',
        'subtitle' => 'Input Data & Dokumen',
        'desc' => 'Lengkapi formulir empat-langkah: Data Diri, Preferensi Divisi, Unggah Dokumen, dan Review. Pengajuan hanya dapat dikirim setelah semua tahap selesai.',
        'detail' => [
            'Data diri dan institusi asal',
            'Rencana periode magang (awal–akhir)',
            'Pilihan divisi yang diminati',
            'Tiga dokumen wajib diunggah (PDF/JPG)',
        ],
        'badge' => 'Mahasiswa',
        'duration' => '15–30 menit',
    ],
    [
        'num' => '03',
        'title' => 'Pemeriksaan Berkas',
        'subtitle' => 'Verifikasi Sekretariat',
        'desc' => 'Tim Sekretariat memeriksa kelengkapan dan keabsahan dokumen yang diunggah. Jika berkas perlu diperbaiki, Anda akan mendapat notifikasi untuk mengunggah ulang.',
        'detail' => [
            'Pemeriksaan kelengkapan dokumen',
            'Validasi data institusi dan periode',
            'Notifikasi dikirim jika revisi diperlukan',
            'Anda dapat mengunggah ulang dokumen yang diminta',
        ],
        'badge' => 'Sekretariat',
        'duration' => '1–3 hari kerja',
    ],
    [
        'num' => '04',
        'title' => 'Pengecekan Kebutuhan Divisi',
        'subtitle' => 'Konfirmasi Kapasitas',
        'desc' => 'Setelah berkas dinyatakan lengkap, Sekretariat mengkonfirmasi ketersediaan kapasitas divisi yang dipilih. Jika divisi penuh atau tidak menerima, pengajuan dapat ditolak pada tahap ini.',
        'detail' => [
            'Pengecekan kapasitas divisi yang dipilih',
            'Jika diterima: langsung ke status "Diterima"',
            'Jika ditolak: alasan wajib dicantumkan',
            'Notifikasi dikirim ke mahasiswa',
        ],
        'badge' => 'Sekretariat',
        'duration' => '1–2 hari kerja',
    ],
    [
        'num' => '05',
        'title' => 'Pelaksanaan Magang',
        'subtitle' => 'Status Aktif Magang',
        'desc' => 'Setelah diterima, Sekretariat menandai dimulainya pelaksanaan magang. Anda berstatus "Sedang Magang" selama periode berlangsung. Tersedia mekanisme pengunduran diri jika diperlukan.',
        'detail' => [
            'Surat penerimaan diunggah oleh Sekretariat',
            'Status berubah menjadi "Sedang Magang"',
            'Pengunduran diri dapat dilakukan melalui portal',
            'Alasan pengunduran diri wajib diisi',
        ],
        'badge' => 'Aktif',
        'duration' => 'Sesuai periode',
    ],
    [
        'num' => '06',
        'title' => 'Penyelesaian & Dokumen Akhir',
        'subtitle' => 'Arsip & Penutupan',
        'desc' => 'Setelah periode magang berakhir, Sekretariat menandai status "Selesai" dan mengunggah dokumen akhir (sertifikat, surat keterangan). Dokumen tersedia untuk diunduh melalui portal.',
        'detail' => [
            'Sekretariat menandai selesai',
            'Dokumen akhir diunggah (sertifikat, SK, dll.)',
            'Mahasiswa dapat mengunduh dokumen resmi',
            'Riwayat magang tersimpan dalam sistem',
        ],
        'badge' => 'Selesai',
        'duration' => '1–3 hari kerja',
    ],
];

$statusMap = [
    ['key' => 'Draft', 'color' => 'var(--text-secondary)', 'bg' => '#F3F5F3', 'desc' => 'Formulir belum dikirim'],
    ['key' => 'Diajukan', 'color' => '#2563EB', 'bg' => '#EFF6FF', 'desc' => 'Menunggu pemeriksaan'],
    ['key' => 'Diperiksa', 'color' => '#7C3AED', 'bg' => '#F5F3FF', 'desc' => 'Sedang diperiksa Sekretariat'],
    ['key' => 'Perlu Revisi', 'color' => '#B45309', 'bg' => '#FFFBEB', 'desc' => 'Dokumen perlu diperbaiki'],
    ['key' => 'Cek Kebutuhan Divisi', 'color' => '#0369A1', 'bg' => '#F0F9FF', 'desc' => 'Berkas lengkap, konfirmasi divisi'],
    ['key' => 'Diterima', 'color' => 'var(--primary)', 'bg' => 'var(--bg-green-soft)', 'desc' => 'Pengajuan diterima'],
    ['key' => 'Ditolak', 'color' => '#9B2C2C', 'bg' => '#FEF2F2', 'desc' => 'Pengajuan ditolak'],
    ['key' => 'Sedang Magang', 'color' => 'var(--primary)', 'bg' => '#F0F7F5', 'desc' => 'Periode magang aktif berjalan'],
    ['key' => 'Mengundurkan Diri', 'color' => '#92400E', 'bg' => '#FFFBEB', 'desc' => 'Mengundurkan diri dari magang'],
    ['key' => 'Selesai', 'color' => 'var(--primary)', 'bg' => 'var(--bg-green-soft)', 'desc' => 'Magang selesai, dokumen tersedia'],
    ['key' => 'Dibatalkan', 'color' => 'var(--text-secondary)', 'bg' => '#F3F5F3', 'desc' => 'Pengajuan dibatalkan mahasiswa'],
];

$badgeColors = [
    'Publik' => ['bg' => 'var(--accent-soft)', 'text' => '#92400E'],
    'Mahasiswa' => ['bg' => 'var(--bg-green-soft)', 'text' => 'var(--primary)'],
    'Sekretariat' => ['bg' => '#EFF6FF', 'text' => '#2563EB'],
    'Aktif' => ['bg' => 'var(--bg-green-soft)', 'text' => 'var(--primary)'],
    'Selesai' => ['bg' => '#F0F9FF', 'text' => '#0369A1'],
];
?>

<div style="background: var(--bg-main); min-height: 100vh;">
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-bg"></div>
        <div class="page-header-line"></div>
        <div class="page-header-content">
            <div class="page-header-eyebrow">Panduan Lengkap</div>
            <h1 class="page-header-title">Alur Pengajuan Magang</h1>
            <p class="page-header-desc">
                Panduan tahap demi tahap untuk mengajukan magang di Bappeda Provinsi Lampung melalui sistem SIMAGANG.
            </p>
        </div>
    </div>

    <div style="max-width: 900px; margin: 0 auto; padding: 64px 40px;">
        <!-- Quick overview -->
        <div class="grid-3" style="gap: 20px; margin-bottom: 64px">
            <?php
            $overviews = [
                ['label' => 'Total Tahap', 'value' => '6 Langkah', 'sub' => 'dari daftar hingga selesai'],
                ['label' => 'Estimasi Proses', 'value' => '5–10 Hari', 'sub' => 'hari kerja sejak pengajuan'],
                ['label' => 'Sistem Buka', 'value' => 'Sepanjang Tahun', 'sub' => 'tidak ada periode pendaftaran tetap'],
            ];
            foreach ($overviews as $item): ?>
                <div style="background: var(--bg-soft); border: 1px solid var(--border); border-radius: 12px; padding: 24px 28px;">
                    <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 8px;"><?= $item['label'] ?></div>
                    <div style="font-family: var(--font-display); font-size: 28px; color: var(--primary); letter-spacing: -0.01em; margin-bottom: 4px;"><?= $item['value'] ?></div>
                    <div style="font-family: var(--font-body); font-size: 13px; color: var(--text-secondary);"><?= $item['sub'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Steps -->
        <div style="margin-bottom: 80px;">
            <h2 style="font-family: var(--font-display); font-size: 30px; font-weight: 400; color: var(--text-primary); letter-spacing: -0.01em; margin-bottom: 40px;">Tahapan Proses</h2>
            <div style="display: flex; flex-direction: column; gap: 0;">
                <?php foreach ($alurSteps as $i => $step): 
                    $badge = $badgeColors[$step['badge']] ?? ['bg' => 'var(--accent-soft)', 'text' => '#92400E'];
                    $isLast = $i === count($alurSteps) - 1;
                ?>
                    <div class="layout-step" style="gap: 0">
                        <!-- Left: number + connector -->
                        <div style="display: flex; flex-direction: column; align-items: center; padding-top: 4px;">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span style="font-family: var(--font-mono); font-size: 13px; color: var(--accent); letter-spacing: 0.05em;"><?= $step['num'] ?></span>
                            </div>
                            <?php if (!$isLast): ?>
                                <div style="width: 2px; flex: 1; background: linear-gradient(to bottom, var(--primary), var(--border)); min-height: 40px; margin-top: 4px;"></div>
                            <?php endif; ?>
                        </div>

                        <!-- Right: content -->
                        <div style="padding-left: 24px; padding-bottom: <?= $isLast ? '0' : '48px' ?>;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
                                <h3 style="font-family: var(--font-display); font-size: 22px; font-weight: 400; color: var(--text-primary); letter-spacing: -0.01em; margin: 0;"><?= $step['title'] ?></h3>
                                <span style="background: <?= $badge['bg'] ?>; color: <?= $badge['text'] ?>; font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.08em; padding: 3px 10px; border-radius: 99px; text-transform: uppercase;"><?= $step['badge'] ?></span>
                            </div>
                            <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 10px;"><?= $step['subtitle'] ?> &middot; <?= $step['duration'] ?></div>
                            <p style="font-family: var(--font-body); font-size: 15px; color: #3D4844; line-height: 1.75; margin-bottom: 16px; margin-top: 0;"><?= $step['desc'] ?></p>
                            
                            <div style="background: var(--bg-soft); border: 1px solid var(--border); border-radius: 10px; padding: 16px 20px;">
                                <?php foreach ($step['detail'] as $di => $d): 
                                    $isLastDetail = $di === count($step['detail']) - 1;
                                ?>
                                    <div style="display: flex; gap: 10px; align-items: flex-start; margin-bottom: <?= $isLastDetail ? '0' : '8px' ?>;">
                                        <div style="width: 6px; height: 6px; border-radius: 50%; background: var(--accent); flex-shrink: 0; margin-top: 7px;"></div>
                                        <span style="font-family: var(--font-body); font-size: 14px; color: var(--text-secondary); line-height: 1.6;"><?= $d ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Status glossary -->
        <div style="margin-bottom: 64px;">
            <h2 style="font-family: var(--font-display); font-size: 30px; font-weight: 400; color: var(--text-primary); letter-spacing: -0.01em; margin-bottom: 8px;">Kamus Status Pengajuan</h2>
            <p style="font-family: var(--font-body); font-size: 15px; color: var(--text-secondary); margin-bottom: 28px; line-height: 1.7; margin-top: 0;">
                Setiap pengajuan memiliki status yang mencerminkan posisinya dalam alur. Status ditampilkan secara real-time di dashboard Anda.
            </p>
            <div style="display: flex; flex-direction: column; gap: 2px;">
                <?php foreach ($statusMap as $s): ?>
                    <div style="display: flex; align-items: center; gap: 16px; padding: 12px 16px; background: var(--bg-soft); border-radius: 8px; border: 1px solid #EAEEE8;">
                        <span style="background: <?= $s['bg'] ?>; color: <?= $s['color'] ?>; font-family: var(--font-mono); font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 6px; white-space: nowrap; min-width: 210px; display: inline-block; text-align: center;"><?= $s['key'] ?></span>
                        <span style="font-family: var(--font-body); font-size: 14px; color: var(--text-secondary);"><?= $s['desc'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- CTA -->
        <div class="bottom-cta">
            <div>
                <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.12em; color: var(--accent); text-transform: uppercase; margin-bottom: 8px;">Siap Mengajukan?</div>
                <h3 style="font-family: var(--font-display); font-size: 26px; font-weight: 400; color: var(--bg-main); line-height: 1.2; margin: 0;">Mulai proses pengajuan Anda sekarang</h3>
            </div>
            <div style="display: flex; gap: 12px; flex-shrink: 0;">
                <a href="<?= BASE_URL ?>/persyaratan" class="btn" style="padding: 12px 24px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.25); border-radius: 8px; color: var(--bg-main); font-weight: 600; text-decoration: none;">
                    Lihat Persyaratan
                </a>
                <a href="<?= BASE_URL ?>/login" class="btn" style="padding: 12px 24px; background: var(--accent); border: none; border-radius: 8px; color: var(--primary-dark); font-weight: 700; text-decoration: none;">
                    Ajukan Magang
                </a>
            </div>
        </div>
    </div>
</div>

