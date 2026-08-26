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

$badgeColors = [
    'Publik' => ['bg' => 'var(--accent-soft)', 'text' => 'var(--color-warning-ink)'],
    'Mahasiswa' => ['bg' => 'var(--bg-green-soft)', 'text' => 'var(--primary)'],
    'Sekretariat' => ['bg' => 'var(--color-info-soft)', 'text' => 'var(--color-info)'],
    'Aktif' => ['bg' => 'var(--bg-green-soft)', 'text' => 'var(--primary)'],
    'Selesai' => ['bg' => 'var(--color-info-soft)', 'text' => 'var(--color-info-ink)'],
];
?>

<div class="public-info-page public-process-page min-h-screen bg-slate-50">
    <!-- Page header -->
    <div class="page-header !min-h-0 !bg-white px-6 py-16 sm:px-8 lg:px-12">
        <div class="page-header-bg"></div>
        <div class="page-header-line"></div>
        <div class="page-header-content !max-w-7xl !px-0">
            <div class="page-header-eyebrow">Panduan Lengkap</div>
            <h1 class="page-header-title">Alur Pengajuan Magang</h1>
            <p class="page-header-desc">
                Panduan tahap demi tahap untuk mengajukan magang di Bappeda Provinsi Lampung melalui sistem SIMAGANG.
            </p>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-6 py-12 sm:px-8 lg:px-12 lg:py-16">
        <!-- Quick overview -->
        <div class="grid gap-4 pb-12 md:grid-cols-3 lg:pb-16">
            <?php
            $overviews = [
                ['label' => 'Total Tahap', 'value' => '6 Langkah', 'sub' => 'dari daftar hingga selesai'],
                ['label' => 'Estimasi Proses', 'value' => '5–10 Hari', 'sub' => 'hari kerja sejak pengajuan'],
                ['label' => 'Sistem Buka', 'value' => 'Sepanjang Tahun', 'sub' => 'tidak ada periode pendaftaran tetap'],
            ];
            foreach ($overviews as $i => $item): ?>
                <div class="info-summary-card rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <span class="info-summary-icon" aria-hidden="true"><?= ['01', '02', '03'][$i] ?></span>
                    <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 8px;"><?= $item['label'] ?></div>
                    <div style="font-family: var(--font-display); font-size: 28px; color: var(--primary); letter-spacing: -0.01em; margin-bottom: 4px;"><?= $item['value'] ?></div>
                    <div style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary);"><?= $item['sub'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Steps -->
        <div style="margin-bottom: 80px;">
            <h2 class="mb-8 text-2xl font-bold text-slate-950 sm:text-3xl">Tahapan Proses</h2>
            <div style="display: flex; flex-direction: column; gap: 0;">
                <?php foreach ($alurSteps as $i => $step): 
                    $badge = $badgeColors[$step['badge']] ?? ['bg' => 'var(--accent-soft)', 'text' => 'var(--color-warning-ink)'];
                    $isLast = $i === count($alurSteps) - 1;
                ?>
                    <div class="process-step layout-step" style="gap: 0">
                        <!-- Left: number + connector -->
                        <div style="display: flex; flex-direction: column; align-items: center; padding-top: 4px;">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span style="font-family: var(--font-mono); font-size: var(--text-body-sm); color: var(--color-text-inverse); letter-spacing: 0.05em;"><?= $step['num'] ?></span>
                            </div>
                            <?php if (!$isLast): ?>
                                <div style="width: 2px; flex: 1; background: linear-gradient(to bottom, var(--primary), var(--border)); min-height: 40px; margin-top: 4px;"></div>
                            <?php endif; ?>
                        </div>

                        <!-- Right: content -->
                        <div style="padding-left: 24px; padding-bottom: <?= $isLast ? '0' : '48px' ?>;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
                                <h3 style="font-family: var(--font-display); font-size: var(--text-h3); font-weight: 400; color: var(--text-primary); letter-spacing: -0.01em; margin: 0;"><?= $step['title'] ?></h3>
                                <span style="background: <?= $badge['bg'] ?>; color: <?= $badge['text'] ?>; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.08em; padding: 3px 10px; border-radius: 99px; text-transform: uppercase;"><?= $step['badge'] ?></span>
                            </div>
                            <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 10px;"><?= $step['subtitle'] ?> &middot; <?= $step['duration'] ?></div>
                            <p style="font-family: var(--font-body); font-size: 15px; color: var(--text-primary); line-height: 1.75; margin-bottom: 16px; margin-top: 0;"><?= $step['desc'] ?></p>
                            
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

        <!-- CTA -->
        <div class="bottom-cta">
            <div class="bottom-cta-copy">
                <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.12em; color: var(--accent-dark); text-transform: uppercase; margin-bottom: 8px;">Siap Mengajukan?</div>
                <h3 style="font-family: var(--font-display); font-size: var(--text-h3); font-weight: 400; color: var(--bg-main); line-height: 1.2; margin: 0;">Mulai proses pengajuan Anda sekarang</h3>
            </div>
            <div class="cta-character" aria-hidden="true"><svg viewBox="0 0 240 170" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 169c4-31 22-48 52-48s48 17 52 48H18Z" fill="#3B6FD6"/><path d="M110 169c3-28 21-45 50-45s48 17 54 45h-104Z" fill="#F0AE45"/><circle cx="70" cy="75" r="22" fill="#C9825B"/><path d="M46 74c2-24 42-32 48-1-12-7-25-8-40 4l-8-3Z" fill="#1F2937"/><path d="M51 97c9 9 27 10 37-1v26H51V97Z" fill="#F3C7A7"/><path d="M43 119c16-12 43-12 59 2l-4 48H35l8-50Z" fill="#3B6FD6"/><path d="m54 122 17 22 19-22" stroke="#E7EEFC" stroke-width="5"/><path d="m100 130 21 22" stroke="#F3C7A7" stroke-width="10" stroke-linecap="round"/><circle cx="167" cy="69" r="21" fill="#9D644A"/><path d="M145 68c1-27 44-31 49 0-10-9-31-12-46 4l-3-4Z" fill="#241C1A"/><path d="M149 90c9 9 27 10 37 0v28h-37V90Z" fill="#E9B99D"/><path d="M137 119c17-12 45-11 61 2l8 48h-78l9-50Z" fill="#F0AE45"/><path d="m150 122 18 20 19-20" stroke="#FFF8E7" stroke-width="5"/><path d="m141 132-18 19" stroke="#E9B99D" stroke-width="10" stroke-linecap="round"/></svg></div>
            <div class="cta-actions">
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

