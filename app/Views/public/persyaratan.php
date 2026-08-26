<?php
// Extracted and derived from Figma Make CSS and PRD v4.1

$eligibles = [
    'Mahasiswa aktif terdaftar di perguruan tinggi negeri atau swasta yang terakreditasi di Indonesia',
    'Minimal semester 3 (enam SKS semester sebelumnya terpenuhi) atau sesuai kebijakan program studi',
    'Tidak sedang menjalani magang, PKL, atau kerja praktik di instansi lain pada periode yang sama',
    'Memiliki nilai akademik yang memenuhi standar minimum (IPK ≥ 2,75 atau sesuai kebijakan program studi)',
    'Mendapat persetujuan atau rekomendasi dari Dosen Pembimbing / Dosen Wali',
    'Bersedia mematuhi tata tertib dan aturan kerja di lingkungan Bappeda Provinsi Lampung',
];

$docsWajib = [
    [
        'title' => 'Surat Lamaran Magang',
        'code' => 'DOC-01',
        'desc' => 'Surat lamaran yang ditujukan kepada Kepala Bappeda Provinsi Lampung. Ditandatangani mahasiswa dan mencantumkan rencana periode magang.',
        'format' => 'PDF',
        'maxSize' => '2 MB',
        'notes' => 'Gunakan kop surat perguruan tinggi jika tersedia',
    ],
    [
        'title' => 'Curriculum Vitae (CV)',
        'code' => 'DOC-02',
        'desc' => 'CV terbaru yang memuat data diri, riwayat pendidikan, pengalaman organisasi, dan keterampilan relevan.',
        'format' => 'PDF',
        'maxSize' => '2 MB',
        'notes' => 'Cantumkan nomor telepon dan email aktif',
    ],
    [
        'title' => 'Transkrip Nilai',
        'code' => 'DOC-03',
        'desc' => 'Transkrip nilai akademik resmi yang dikeluarkan oleh institusi perguruan tinggi. Harus menampilkan IPK terkini.',
        'format' => 'PDF / JPG',
        'maxSize' => '3 MB',
        'notes' => 'Transkrip tidak perlu dilegalisir, namun harus terbaca jelas',
    ],
];

$docsOpsional = [
    [
        'title' => 'Surat Pengantar dari Kampus',
        'code' => 'OPT-01',
        'desc' => 'Surat pengantar resmi dari Dekan, Ketua Program Studi, atau pejabat berwenang di perguruan tinggi.',
        'format' => 'PDF',
        'maxSize' => '2 MB',
    ],
    [
        'title' => 'Portofolio Karya / Proposal Riset',
        'code' => 'OPT-02',
        'desc' => 'Untuk magang di divisi perencanaan atau riset, portofolio atau proposal dapat memperkuat pengajuan.',
        'format' => 'PDF',
        'maxSize' => '5 MB',
    ],
    [
        'title' => 'Sertifikat Pelatihan / Penghargaan',
        'code' => 'OPT-03',
        'desc' => 'Sertifikat kompetensi, pelatihan, atau penghargaan yang relevan dengan divisi yang dituju.',
        'format' => 'PDF / JPG',
        'maxSize' => '2 MB',
    ],
];

// Gunakan data dinamis dari controller jika tersedia
$divisiList = $divisiData ?? [];
?>

<style>
    .accordion-item {
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 12px;
        overflow: hidden;
    }
    
    .accordion-header {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        background: var(--bg-soft);
        border: none;
        cursor: pointer;
        text-align: left;
        transition: background 0.15s;
    }
    
    .accordion-header.active {
        background: var(--bg-green-soft);
    }
    
    .accordion-title {
        font-family: var(--font-display);
        font-size: 20px;
        font-weight: 400;
        color: var(--text-primary);
        letter-spacing: -0.01em;
    }
    
    .accordion-icon {
        font-family: var(--font-mono);
        font-size: 18px;
        color: var(--text-secondary);
        transition: transform 0.2s, color 0.15s;
        display: inline-block;
    }
    
    .accordion-header.active .accordion-icon {
        color: var(--primary);
        transform: rotate(45deg);
    }
    
    .accordion-content {
        padding: 0 24px 24px;
        display: none;
    }
    
    .accordion-item.active .accordion-content {
        display: block;
    }
</style>

<div class="public-info-page public-requirements-page min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="page-header !min-h-0 !bg-white px-6 py-16 sm:px-8 lg:px-12">
        <div class="page-header-bg"></div>
        <div class="page-header-line"></div>
        <div class="page-header-content !max-w-7xl !px-0">
            <div class="page-header-eyebrow">Informasi Resmi</div>
            <h1 class="page-header-title">Persyaratan Magang</h1>
            <p class="page-header-desc">
                Ketentuan eligibilitas, dokumen yang diperlukan, dan informasi divisi tersedia untuk magang di Bappeda Provinsi Lampung.
            </p>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-6 py-12 sm:px-8 lg:px-12 lg:py-16">
        <!-- Alert -->
        <div style="display: flex; gap: 14px; background: var(--accent-soft); border: 1px solid rgba(217, 165, 29, 0.25); border-radius: 10px; padding: 16px 20px; margin-bottom: 48px;">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div style="font-family: var(--font-body); font-size: 14px; color: var(--color-warning-ink); line-height: 1.7;">
                <strong>Pendaftaran terbuka sepanjang tahun.</strong> Tidak ada periode pendaftaran tetap — pengajuan dapat dilakukan kapan saja, tergantung ketersediaan kapasitas divisi.
            </div>
        </div>

        <!-- Quick summary -->
        <div class="grid gap-4 pb-12 md:grid-cols-3 lg:pb-16">
            <?php
            $summaries = [
                [
                    'icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
                    'label' => '6 Kriteria Eligibilitas',
                    'sub' => 'yang harus dipenuhi'
                ],
                [
                    'icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>',
                    'label' => '3 Dokumen Wajib',
                    'sub' => '+ opsional untuk penunjang'
                ],
                [
                    'icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>',
                    'label' => 'Unggah Mandiri',
                    'sub' => 'melalui portal SIMAGANG'
                ],
            ];
            foreach ($summaries as $i => $item): ?>
                <div class="info-summary-card flex gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <span class="info-summary-icon" aria-hidden="true"><?= ['01', '02', '03'][$i] ?></span>
                    <div style="flex-shrink: 0; margin-top: 2px;"><?= $item['icon'] ?></div>
                    <div>
                        <div style="font-family: var(--font-body); font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 2px;"><?= $item['label'] ?></div>
                        <div style="font-family: var(--font-body); font-size: 13px; color: var(--text-secondary);"><?= $item['sub'] ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Accordion sections -->
        <div class="accordion-container">
            <!-- Eligibilitas -->
            <div class="accordion-item active" id="acc-eligible">
                <button class="accordion-header active" onclick="toggleAccordion('acc-eligible')">
                    <span class="accordion-title">Kriteria Eligibilitas</span>
                    <span class="accordion-icon">+</span>
                </button>
                <div class="accordion-content">
                    <div style="padding-top: 16px; display: flex; flex-direction: column; gap: 10px;">
                        <?php foreach ($eligibles as $e): ?>
                            <div style="display: flex; gap: 14px; align-items: flex-start;">
                                <div style="width: 22px; height: 22px; border-radius: 50%; background: var(--bg-green-soft); border: 1px solid rgba(11, 79, 71, 0.19); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <p style="font-family: var(--font-body); font-size: 14.5px; color: var(--text-primary); line-height: 1.7; margin: 0;"><?= $e ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Dokumen Wajib -->
            <div class="accordion-item" id="acc-wajib">
                <button class="accordion-header" onclick="toggleAccordion('acc-wajib')">
                    <span class="accordion-title">Dokumen Wajib</span>
                    <span class="accordion-icon">+</span>
                </button>
                <div class="accordion-content">
                    <div style="padding-top: 16px; display: flex; flex-direction: column; gap: 16px;">
                        <?php foreach ($docsWajib as $doc): ?>
                            <div style="background: var(--bg-soft); border: 1px solid var(--border); border-radius: 10px; padding: 20px 24px;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                    <span style="font-family: var(--font-mono); font-size: 12px; color: var(--primary); background: var(--bg-green-soft); padding: 3px 8px; border-radius: 5px;"><?= $doc['code'] ?></span>
                                    <h4 style="font-family: var(--font-body); font-size: 15px; font-weight: 700; color: var(--text-primary); margin: 0;"><?= $doc['title'] ?></h4>
                                    <span style="margin-left: auto; background: var(--color-danger-soft); color: var(--color-danger-ink); font-family: var(--font-body); font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 99px; letter-spacing: 0.06em;">WAJIB</span>
                                </div>
                                <p style="font-family: var(--font-body); font-size: 14px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 10px; margin-top: 0;"><?= $doc['desc'] ?></p>
                                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                                    <span style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);"><strong style="color: var(--text-primary);">Format:</strong> <?= $doc['format'] ?></span>
                                    <span style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);"><strong style="color: var(--text-primary);">Maks. ukuran:</strong> <?= $doc['maxSize'] ?></span>
                                </div>
                                <?php if (isset($doc['notes'])): ?>
                                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid var(--bg-soft); font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);">
                                        <em>Catatan: <?= $doc['notes'] ?></em>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Dokumen Opsional -->
            <div class="accordion-item" id="acc-opsional">
                <button class="accordion-header" onclick="toggleAccordion('acc-opsional')">
                    <span class="accordion-title">Dokumen Opsional (Penunjang)</span>
                    <span class="accordion-icon">+</span>
                </button>
                <div class="accordion-content">
                    <div style="padding-top: 8px;">
                        <p style="font-family: var(--font-body); font-size: 14px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 20px; margin-top: 0;">
                            Dokumen berikut tidak wajib, namun dapat memperkuat pengajuan Anda, terutama untuk divisi yang kompetitif atau memiliki spesialisasi.
                        </p>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <?php foreach ($docsOpsional as $doc): ?>
                                <div style="background: var(--bg-soft); border: 1px solid var(--border); border-radius: 10px; padding: 16px 20px; display: flex; gap: 16px; align-items: flex-start;">
                                    <span style="font-family: var(--font-mono); font-size: 12px; color: var(--text-secondary); background: var(--bg-soft); padding: 3px 8px; border-radius: 5px; flex-shrink: 0; margin-top: 2px;"><?= $doc['code'] ?></span>
                                    <div>
                                        <div style="font-family: var(--font-body); font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;"><?= $doc['title'] ?></div>
                                        <div style="font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary); line-height: 1.65; margin-bottom: 6px;"><?= $doc['desc'] ?></div>
                                        <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);">
                                            <strong style="color: var(--text-primary);">Format:</strong> <?= $doc['format'] ?> &middot; <strong style="color: var(--text-primary);">Maks:</strong> <?= $doc['maxSize'] ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divisi yang Tersedia -->
            <div class="accordion-item" id="acc-divisi">
                <button class="accordion-header" onclick="toggleAccordion('acc-divisi')">
                    <span class="accordion-title">Divisi yang Tersedia</span>
                    <span class="accordion-icon">+</span>
                </button>
                <div class="accordion-content">
                    <div style="padding-top: 12px;">
                        <p style="font-family: var(--font-body); font-size: 14px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 20px; margin-top: 0;">
                            Ketersediaan kapasitas setiap divisi bersifat dinamis. Status kapasitas ditampilkan saat pengisian formulir pengajuan.
                        </p>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <?php if (empty($divisiList)): ?>
                                <div style="font-family: var(--font-body); font-size: 14px; color: var(--text-secondary); text-align: center; padding: 24px;">
                                    Saat ini belum ada data divisi yang tersedia.
                                </div>
                            <?php else: ?>
                                <?php foreach ($divisiList as $i => $div): ?>
                                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 8px;">
                                        <div style="display: flex; align-items: center; gap: 16px;">
                                            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--bg-green-soft); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <span style="font-family: var(--font-mono); font-size: 12px; color: var(--primary); font-weight: 700;"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                            </div>
                                            <div>
                                                <div style="font-family: var(--font-body); font-size: 14.5px; font-weight: 700; color: var(--text-primary);"><?= htmlspecialchars($div['nama_divisi']) ?></div>
                                                <div style="font-family: var(--font-body); font-size: 13px; color: var(--text-secondary);"><?= htmlspecialchars($div['deskripsi'] ?? 'Tidak ada deskripsi') ?></div>
                                            </div>
                                        </div>
                                        <?php $sisa = max(0, $div['kapasitas'] - ($div['terisi'] ?? 0)); ?>
                                        <div style="flex-shrink: 0; text-align: right;">
                                            <span style="display: inline-block; background: <?= $sisa > 0 ? 'var(--accent-soft)' : 'var(--color-danger-soft)' ?>; color: <?= $sisa > 0 ? 'var(--primary-dark)' : 'var(--color-danger-ink)' ?>; padding: 4px 12px; border-radius: 6px; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.04em;">
                                                <?= $sisa > 0 ? "SISA KUOTA: {$sisa}" : "KUOTA PENUH" ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="bottom-cta" style="margin-top: 48px;">
            <div class="bottom-cta-copy">
                <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.12em; color: var(--accent-dark); text-transform: uppercase; margin-bottom: 8px;">Sudah Siap?</div>
                <h3 style="font-family: var(--font-display); font-size: 26px; font-weight: 400; color: var(--bg-main); line-height: 1.2; margin: 0;">Ajukan magang Anda sekarang</h3>
            </div>
            <div class="cta-character" aria-hidden="true"><svg viewBox="0 0 240 170" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 169c4-31 22-48 52-48s48 17 52 48H18Z" fill="#3B6FD6"/><path d="M110 169c3-28 21-45 50-45s48 17 54 45h-104Z" fill="#F0AE45"/><circle cx="70" cy="75" r="22" fill="#C9825B"/><path d="M46 74c2-24 42-32 48-1-12-7-25-8-40 4l-8-3Z" fill="#1F2937"/><path d="M51 97c9 9 27 10 37-1v26H51V97Z" fill="#F3C7A7"/><path d="M43 119c16-12 43-12 59 2l-4 48H35l8-50Z" fill="#3B6FD6"/><path d="m54 122 17 22 19-22" stroke="#E7EEFC" stroke-width="5"/><path d="m100 130 21 22" stroke="#F3C7A7" stroke-width="10" stroke-linecap="round"/><circle cx="167" cy="69" r="21" fill="#9D644A"/><path d="M145 68c1-27 44-31 49 0-10-9-31-12-46 4l-3-4Z" fill="#241C1A"/><path d="M149 90c9 9 27 10 37 0v28h-37V90Z" fill="#E9B99D"/><path d="M137 119c17-12 45-11 61 2l8 48h-78l9-50Z" fill="#F0AE45"/><path d="m150 122 18 20 19-20" stroke="#FFF8E7" stroke-width="5"/><path d="m141 132-18 19" stroke="#E9B99D" stroke-width="10" stroke-linecap="round"/></svg></div>
            <div class="cta-actions">
                <a href="<?= BASE_URL ?>/alur" class="btn" style="padding: 12px 24px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.25); border-radius: 8px; color: var(--bg-main); font-weight: 600; text-decoration: none;">
                    Lihat Alur
                </a>
                <a href="<?= BASE_URL ?>/register" class="btn" style="padding: 12px 24px; background: var(--accent); border: none; border-radius: 8px; color: var(--primary-dark); font-weight: 700; text-decoration: none;">
                    Daftar Akun
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleAccordion(id) {
        // Toggle the clicked item
        const item = document.getElementById(id);
        const header = item.querySelector('.accordion-header');
        
        const isActive = item.classList.contains('active');
        
        if (isActive) {
            item.classList.remove('active');
            header.classList.remove('active');
        } else {
            // Uncomment the lines below if you want only one section open at a time
            /*
            document.querySelectorAll('.accordion-item').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.accordion-header').forEach(el => el.classList.remove('active'));
            */
            
            item.classList.add('active');
            header.classList.add('active');
        }
    }
</script>

